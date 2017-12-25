<?php

namespace Grt\ResBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastname',TextType::class, array('label' => 'Last Name','attr'=> array('class'=>'form-control')));
        $builder->add('firstname',TextType::class, array('label' => 'First Name','attr'=> array('class'=>'form-control')));
        $builder->add('middlename',TextType::class, array('label' => 'Middle Name','attr'=> array('class'=>'form-control')));
        $builder->add('domainname',TextType::class, array('label' => 'Domain Name','attr'=> array('class'=>'form-control')));
        $builder->add('location', EntityType::class, array('label' => 'ПТО',
            'class' => 'Grt\ResBundle\Entity\Location',
            'placeholder' => 'Выбирите ПТО',
            'required' => true,

        ));
        $builder->add('department', EntityType::class, array('label' => 'Отдел',
            'class' => 'Grt\ResBundle\Entity\Department',
            'placeholder' => 'Выбирите отдел',
            'required' => true,
            'choice_label' => 'name'
        ));


    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Grt\ResBundle\Entity\User'
        ));
    }

    public function getBlockPrefix()
    {
        return 'grt_usertype';
    }
}
