<?php

/*
 * This file is part of the RollerworksMultiUserBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rollerworks\Bundle\MultiUserBundle;

use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Rollerworks\Bundle\MultiUserBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;
use Rollerworks\Bundle\MultiUserBundle\DependencyInjection\Compiler\RegisterUserPass;
use Rollerworks\Bundle\MultiUserBundle\DependencyInjection\Compiler\RemoveParentServicesPass;

/**
 * RollerworksMultiUserBundle.
 *
 * Provides user management functionality (authentication, authorization, etc).
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 */
class RollerworksMultiUserBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideServiceCompilerPass());
        $container->addCompilerPass(new RemoveParentServicesPass());
        $container->addCompilerPass(new RegisterUserPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }

    /**
     * {@inheritdoc}
     */
    public function registerCommands(Application $application)
    {
        if (!is_dir($dir = $this->getPath().'/Command')) {
            return;
        }

        $finder = new Finder();
        $finder->files()->name('*Command.php')->in($dir);

        // Don't enable the generate command when SensioGeneratorBundle is not installed
        if (!class_exists('Sensio\\Bundle\\GeneratorBundle\\Command\\GenerateBundleCommand')) {
            $finder->notName('GenerateUserSysCommand.php');
        }

        $prefix = $this->getNamespace().'\\Command';
        foreach ($finder as $file) {
            $ns = $prefix;
            if ($relativePath = $file->getRelativePath()) {
                $ns .= '\\'.strtr($relativePath, '/', '\\');
            }
            $class = $ns.'\\'.$file->getBasename('.php');

            $r = new \ReflectionClass($class);
            if ($r->isSubclassOf('Symfony\\Component\\Console\\Command\\Command') && !$r->isAbstract() && !$r->getConstructor()->getNumberOfRequiredParameters()) {
                $application->add($r->newInstance());
            }
        }
    }
}
