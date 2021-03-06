<?php

/*
 * This file is part of the RollerworksMultiUserBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rollerworks\Bundle\MultiUserBundle\Tests\Functional\Bundle\MongoBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Rollerworks\Bundle\MultiUserBundle\DependencyInjection\Factory\UserServicesFactory;

class AcmeMongoExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $factory = new UserServicesFactory($container);
        $factory->create('acme_mongo', array(
            array(
                'path' => '^/mongo',
                'user_class' => 'Rollerworks\Bundle\MultiUserBundle\Tests\Functional\Bundle\MongoBundle\Document\User',
                'services_prefix' => 'acme_mongo',
                'routes_prefix' => 'acme_mongo',
                'firewall_name' => 'mongo',
                'db_driver' => 'mongodb',

                'group' => false,

                'security' => array(
                    'login' => array(
                        'template' => 'AcmeMongoBundle:Security:login.html.twig',
                    )
                ),

                'registration' => array(
                    'template' => array(
                        'register' => 'AcmeMongoBundle:Registration:register.html.twig',
                        'check_email' => 'AcmeMongoBundle:Registration:checkEmail.html.twig',
                    ),
                    'form' => array(
                        'type' => 'acme_mongo_registration',
                        'class' => 'Rollerworks\Bundle\MultiUserBundle\Tests\Functional\Bundle\MongoBundle\Form\Type\RegistrationFormType',
                    ),
                ),

            )
        ));
    }
}
