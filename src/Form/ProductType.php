<?php

namespace App\Form;

use App\Entity\CategoryProdcut;
use App\Entity\Product;
use App\Entity\Sizes;
use App\Repository\SizesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    private $sizesRepository;
    private $request;
    public function __construct(SizesRepository $sizesRepository, RequestStack $request) {
        $this->sizesRepository = $sizesRepository;
        $this->request = $request;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                  ],
            ])
            ->add('descrip', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                  ],
            ])
            ->add('images', FileType::class, [
                'label' => 'Image',
                'data_class' => null,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                  ],
            ])
            ->add('Category', EntityType::class, [
                'class' => CategoryProdcut::class,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control'
                  ],
                'expanded' => false,
            ]) 
            ->add('Save', SubmitType::class, [
                'label' => 'save product',
                'attr' => [
                    'class' => 'btn btn-primary mt-2'
                  ],
            ])
        ;

        // adding Sizes conditionnaly on category
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $product = $event->getData();

            $form = $event->getForm();
            // if ($product && $product->getId()) {
            //     $form->add('Sizes', EntityType::class, [
            //         "class"=> Sizes::class,
            //         "multiple" => true,
            //         'attr' => [
            //             'class' => 'form-control'
            //           ],
            //         "choices"=> $this->sizesRepository->findBy(['category'=>$product->getCategory()]),
            //     ]);
            // }
        });


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
