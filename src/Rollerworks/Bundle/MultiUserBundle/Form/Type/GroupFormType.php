<?php

/*
 * This file is part of the RollerworksMultiUserBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rollerworks\Bundle\MultiUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupFormType extends AbstractType
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $typeName;

    /**
     * @param string $class    The User class name
     * @param string $typeName The FormType name
     */
    public function __construct($class, $typeName)
    {
        $this->class = $class;
        $this->typeName = $typeName;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array('label' => 'form.group_name', 'translation_domain' => 'FOSUserBundle'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'group',
        ));
    }

    public function getName()
    {
        return $this->typeName;
    }
}
