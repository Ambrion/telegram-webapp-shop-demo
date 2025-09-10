<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Form;

use App\Order\Infrastructure\Filter\OrderFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderFilterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')
            ->add('invoice', TextType::class, [
                'label' => 'Поиск по инвойсу заказа',
                'translation_domain' => 'admin.order.list',
                'required' => false,
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $event): void {
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderFilter::class,
            'csrf_protection' => false,
        ]);
    }
}
