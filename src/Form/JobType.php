<?php
namespace App\Form;

use App\Entity\Category;
use App\Entity\Job;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
class JobType extends AbstractType
{
    /**
    * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('type', ChoiceType::class, [
            'choices' => array_combine(Job::TYPES, Job::TYPES),
            'expanded' => true,
            'constraints' => [
                       new NotBlank(),
            ],
            'attr'  => [
                'class' => 'form-check'
            ],
        ])
        ->add('company', TextType::class,[
            'attr'  => [
                'class' => 'form-control form-control-lg'
            ],
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ]

        ])
        ->add('logo', FileType::class, [
            'required' => false,
            'constraints' => [
                new Image(),
            ],
            'attr'  => [
                'class' => 'form-control form-control-lg'
            ],
        ])
        ->add('url', UrlType::class,[
            'required' => false,
            'attr'  => [
                'class' => 'form-control form-control-lg'
            ],
            'constraints' => [
                new Length(['max' => 255]),
            ]
        ])
        ->add('position', TextType::class, [
            'attr'  => [
                'class' => 'form-control form-control-lg'
            ],
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ]
        ])
        ->add('location', TextType::class, [
            'attr'  => [
                'class' => 'form-control form-control-lg'
            ],
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ]
        ])
        ->add('description', TextareaType::class,[
            'attr'  => [
                'class' => 'form-control form-control-lg'
            ],
        ])
        ->add('howToApply', TextType::class, [
            'attr'  => [
                'class' => 'form-control form-control-lg'
            ],
            'label' => 'How to apply?',
        ])
        ->add('is_public', ChoiceType::class, [
            'choices'  => [
                'Yes' => true,
                'No' => false,
            ],
            'label' => 'Public?',
            'attr'  => [
                'class' => 'form-control form-control-lg'
            ],
        ])
        ->add('is_validated', ChoiceType::class, [
            'choices'  => [
                'Yes' => true,
                'No' => false,
            ],
            'attr'  => [
                'class' => 'form-control form-control-lg'
            ]
        ])
        ->add('email', EmailType::class,[
            'attr'  => [
                'class' => 'form-control form-control-lg'
            ],
            'constraints'=>[
                new Email(),
                new NotBlank()

            ]
        ])
        ->add('category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'name',
            'attr'  => [
                'class' => 'form-group form-control form-control-lg'
            ],
            'constraints'=>[
                new NotBlank(),
            ]
        ]);
        

    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);

    }
}