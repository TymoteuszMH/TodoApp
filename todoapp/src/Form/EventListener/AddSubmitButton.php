<?php
namespace App\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddSubmitButton implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

    public function preSetData(FormEvent $event, string $buttonName): void
    {
        $submit = $event->getForm()
            ->add('save', SubmitType::class, ['label' => $buttonName])
            ;
    }
}
?>