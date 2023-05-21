<?php
namespace App\Form\Type;

use App\Entity\TodoList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
        $builder
            ->add('title', TextType::class, ['required' => true, 'attr' => ['class' => 'form-control']])
            ->add('elements', CollectionType::class, array(
                'entry_type'   => ElementType::class,
                'allow_add'    => true,
                'allow_delete' => true
              ))
            ->add('save', SubmitType::class, ['label' => 'Save list']);
        ;

        // $formModifier = function (FormInterface $form, TodoList $list = null) {
        //     $elements = null === $list ? [] : $list->getElements();

        //     foreach ($elements as $element){
        //         $form->add($element->getId(), ElementType::class, [
        //             'class' => TodoElement::class,
        //             'placeholder' => '',
        //         ]);
        //     }
        // };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            // function (FormEvent $event) use ($formModifier) {
            function (FormEvent $event){
                // this would be your entity, i.e. SportMeetup
                // $data = $event->getData();
                // $form = $event->getForm();

                // if($data)
                //     $form->add('save', SubmitType::class, ['label' => 'Update list']);
                // else
                //     $form->add('save', SubmitType::class, ['label' => 'Add list']);

                // $formModifier($form, $data);
            }
        );
    }

    public function setupSubmitLabel(FormInterface $form, string $submitName){
        $form->add('save', SubmitType::class, ['label' => $submitName]);
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TodoList::class,
        ]);
    }

    
}
?>