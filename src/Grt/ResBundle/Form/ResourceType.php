<?php

namespace Grt\ResBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ResourceType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ip',TextType::class, array('label' => 'IP-адрес','attr'=> array('class'=>'form-control')));
        $builder->add('login',TextType::class, array('label' => 'Логин','attr'=> array('class'=>'form-control')));
        $builder->add('annotation',TextType::class, array('label' => 'Примечание','attr'=> array('class'=>'form-control')));
        $builder->add('term', DateType::class, array('label' => 'Term(YYYY-MM-DD)',
            'widget' => 'single_text','format' => 'yyyy-mm-dd','attr'=> array('class'=>'input-group date form-control')));

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Grt\ResBundle\Entity\Resource'
        ));
    }

    public function getBlockPrefix()
    {
        return 'grt_resourcetype';
    }
}