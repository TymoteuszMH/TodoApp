<?php
namespace App\Form\Type;

use App\Entity\TodoList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
        $builder
            ->add('title', TextType::class, ['required' => true])
            ->add('elements', CollectionType::class, array(
                'entry_type'   => ElementType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'label' => false
              ))
            ->add('save', SubmitType::class, ['label' => 'Save list']);
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TodoList::class,
        ]);
    }

    
}
?>