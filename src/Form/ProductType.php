<?php

namespace App\Form;

use App\Entity\CategoryProdcut;
use App\Entity\KeyWords;
use App\Entity\Product;
use App\Entity\Sizes;
use App\Repository\CategoryProdcutRepository;
use App\Repository\KeyWordsRepository;
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
    private $categoryProdcutRepository;
    private $keyWordsRepository;
    public function __construct(CategoryProdcutRepository $categoryProdcutRepository, KeyWordsRepository $keyWordsRepository) {
        $this->categoryProdcutRepository = $categoryProdcutRepository;
        $this->keyWordsRepository = $keyWordsRepository;
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
            'multiple' => true,
            'required' => true,
            'attr' => [
                'class' => 'form-control'
            ],

        ])
        ->add('Category', ChoiceType::class, [
            'placeholder' => 'Choose a category',
            "label" => "Choose a category",
            'choices' => $this->categoryProdcutRepository->findAll(),
            "choice_label" => "name",
            'attr' => [
                'class' => 'form-control'
            ],
        ])
        ->add('KeyWords', EntityType::class, [
            'class'=> KeyWords::class,
            'placeholder' => 'Chooses Key words',
            "label" => "Choose some keywords",
            "multiple" => true,
            "choice_label" => "name",
            "choice_attr" => function ($choice, $key, $value) {
                return ['class' => 'form-control'];
            },
            'attr' => [
                'class' => 'form-control'
            ],
        ])
        ->add('Save', SubmitType::class, [
            'label' => 'save product',
            'attr' => [
                'class' => 'btn btn-primary mt-2'
            ],
        ])
        ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $categ = $event->getData();
            // dd($categ);
            $form = $event->getForm();
            if ($categ != null && is_array($categ) && isset($categ['Category'])) {
                if (!is_array($categ['Category'])) {
                    if ($categ['Category']->getId() == 1) {
                        $form->add('tshirtSizes', EntityType::class, [
                            "class" => TshirtSizes::class,
                            "multiple" => true,
                            'mapped' => false,
                            "expanded" => true,
                            'attr' => [
                                'class' => 'my-2',
                                'label' => 'Choose some sizes'
                            ],
                        ]);
                    } else {
                        $form->add('shoesSizes', EntityType::class, [
                            "class" => ShoesSizes::class,
                            "multiple" => true,
                            'mapped' => false,
                            "expanded" => true,
                            'attr' => [
                                'class' => 'my-2',
                                'label' => 'Choose some sizes'
                            ],
                        ]);
                    }
                }
            }
        })
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
