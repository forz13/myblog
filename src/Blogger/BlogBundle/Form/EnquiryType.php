<?php

namespace Blogger\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['attr' => ['placeholder' => 'name'], 'label' => false]);
        $builder->add('email', EmailType::class, ['attr' => ['placeholder' => 'email'], 'label' => false]);
        $builder->add('subject', TextType::class, ['attr' => ['placeholder' => 'subject'], 'label' => false]);
        $builder->add('body', TextareaType::class, ['label' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'contact';
    }
}
