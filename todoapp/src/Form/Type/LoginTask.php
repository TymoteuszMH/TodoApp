<?php
namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, ['required' => true, 'attr' => ['class' => 'form-control']])
            ->add('password', PasswordType::class, ['required' => true, 'attr' => ['class' => 'form-control']])
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
                if($data){
                    if($data->getId()>0)
                        $form->add('save', SubmitType::class, ['label' => 'Update data']);
                    else
                        $form->add('save', SubmitType::class, ['label' => 'Sign In']);
                }
                else
                    $form->add('save', SubmitType::class, ['label' => 'Sign Up']);
                
            }
        );
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    
}
?>