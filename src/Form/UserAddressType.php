<?php

namespace App\Form;

use App\Entity\UserAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use User;

class UserAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('name')
            ->add('firstName')
            ->add('phone')
            ->add('address')
            ->add('cp')
            ->add('city')
            ->add('country');
        if (in_array('ROLE_ADMIN', $options['role'])) {
            $builder->add('user');
        }
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserAddress::class,
            'role' => ['ROLE_USER']
        ]);
    }
}
