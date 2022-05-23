<?php

namespace App\Controller;

use App\Entity\CategoryProdcut;
use App\Entity\Images;
use App\Entity\KeyWords;
use App\Entity\Product;
use App\Entity\ShoesSizes;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\TshirtSizes;
use App\Form\ProductType;
use App\Repository\CategoryProdcutRepository;
use App\Repository\KeyWordsRepository;
use App\Repository\ProductRepository;
use App\Repository\ShoesSizesRepository;
use App\Repository\SizesRepository;
use App\Repository\TshirtSizesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Choice;

#[Route('/backoffice/product')]
class ProductController extends AbstractController
{

    private $tshirtsizes;
    private $shoessizes;
    private $isFirstLoad;

    public function __construct(TshirtSizesRepository $tshirtsizes,ShoesSizesRepository $shoessizes)
    {
        $this->tshirtsizes = $tshirtsizes;
        $this->shoessizes = $shoessizes;
        $this->isFirstLoad = true;
    }

    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request,KeyWordsRepository $keyWordsRepository, TshirtSizesRepository $tshirtSizesRepository,ShoesSizesRepository $shoesSizesRepository, ProductRepository $productRepository, CategoryProdcutRepository $categoryProdcutRepository): Response
    {
        $prePoppulate = null;
        if ($request->get('form') != null) {
                $prePoppulate = $request->get('form')["Category"] ?? null;
        }

        $product = new Product();

        $form = $this->createFormBuilder(["Category" => $prePoppulate != null ? $categoryProdcutRepository->find($prePoppulate) : $categoryProdcutRepository->findAll()], array('allow_extra_fields' => true))
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
            ->add('price', NumberType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('image', FileType::class, [
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
                'choices' => $categoryProdcutRepository->findAll(),
                "choice_label" => "name",
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('KeyWords', ChoiceType::class, [
                'placeholder' => 'Chooses Key words',
                "label" => "Choose some keywords",
                'choices' => $keyWordsRepository->findAll(),
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
                if ($categ != null && isset($categ['Category'])) {
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
            ->getForm();


        
        
            $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid() && $prePoppulate != null) {
            //  dd($form->getData(),$form->getExtraData());
            $product->setName($form->getData()['name']);
            $product->setDescrip($form->getData()['descrip']);
            $product->setCategory($form->getData()['Category']);
            $product->setPrice($form->getData()['price']);
            
            $images = $form->get('image')->getData();
    
            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $img = new Images();
                $img->setName($fichier);
                $product->addImage($img);
                // dd($product);
            }

           
            if(isset($form->getData()['KeyWords']) && sizeof($form->getData()['KeyWords'])>0){
                foreach ($form->getData()['KeyWords'] as $value) {
                    $product->addKeyWord($value);
                }
            }
        
            if(sizeof($form->getExtraData())>0){
                if($form->getData()['Category']->getId() == 1){
                    foreach ($form->getExtraData()['tshirtSizes'] as $size) {
                        $product->addTshirtSize($tshirtSizesRepository->find($size));
                    }
                }else{
                    foreach ($form->getExtraData()['shoesSizes'] as $size) {
                        $product->addShoesSize($shoesSizesRepository->find($size));
                    }
                }
            }
            $productRepository->add($product, true);
             return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        $keyword = new KeyWords();
        $formKeyWords = $this->createFormBuilder(null ,array('allow_extra_fields' => true))
        ->add('name', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
        ])
        ->add('Save', SubmitType::class, [
            'label' => 'add some keywords to product',
            'attr' => [
                'class' => 'btn btn-primary mb-5 mt-2'
            ],
        ])->getForm();


        $formKeyWords->handleRequest($request);


        if ($formKeyWords->isSubmitted() && $formKeyWords->isValid()) {
            $keyword->setName($formKeyWords->getData()['name']);
            
            $keyWordsRepository->add($keyword, true);
            return $this->redirectToRoute('app_product_new', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
            'formKey' => $formKeyWords,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository,KeyWordsRepository $keyWordsRepository, TshirtSizesRepository $tshirtSizesRepository,ShoesSizesRepository $shoesSizesRepository, CategoryProdcutRepository $categoryProdcutRepository): Response
    {
        $prePoppulate = null;
        if ($request->get('form') != null) {
                $prePoppulate = $request->get('form')["Category"] ?? null;
        }

        $form = $this->createFormBuilder([
            "Category" => $product->getCategory(),
            "name" => $product->getName(),
            "descrip" => $product->getName(),
            "KeyWords" => $product->getKeyWords(),
            "ShoesSizes" => $product->getShoesSizes(),
            "TshirtSizes" => $product->getTshirtSizes(),
            "price" => $product->getPrice(),
    ], array('allow_extra_fields' => true))
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
            ->add('price', NumberType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'data_class' => null,
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],

            ])
            ->add('Category', EntityType::class, [
                "class" => CategoryProdcut::class,
                "data" => $product->getCategory(),
                'attr' => [
                    'class' => 'form-control',
                    "readonly" => true
                ],
            ])->add('tshirtSizes', EntityType::class, [
                                "class" => TshirtSizes::class,
                                "multiple" => true,
                                'mapped' => false,
                                "data" => $product->getTshirtSizes(),
                                "disabled" => count($product->getTshirtSizes()) > 0 ? false : true,
                                "choices"=> count($product->getTshirtSizes()) >0 ? $tshirtSizesRepository->findAll():[],
                                "expanded" => true,
                                'attr' => [
                                    'class' => 'my-2',
                                    'label' => 'Choose some sizes'
                                ],
                            ])
                            ->add('shoesSizes', EntityType::class, [
                                "class" => ShoesSizes::class,
                                "multiple" => true,
                                'mapped' => false,
                                "data" => $product->getShoesSizes(),
                                "disabled" => count($product->getShoesSizes()) >0 ? false:true,
                                "choices"=> $shoesSizesRepository->findAll(),
                                "expanded" => true,
                                'attr' => [
                                    'class' => 'my-2',
                                    'label' => 'Choose some sizes'
                                ],
                            ])
            ->add('KeyWords', EntityType::class, [
                "class"=> KeyWords::class,
                'placeholder' => 'Chooses Key words',
                "label" => "Choose some keywords",
                "choices"=> $keyWordsRepository->findAll(),
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
            ->getForm();


        
        
            $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid() && $prePoppulate != null) {
            //  dd($form->getData(),$form->getExtraData());
            $product->setName($form->getData()['name']);
            $product->setDescrip($form->getData()['descrip']);
            $product->setCategory($form->getData()['Category']); 
            $product->setPrice($form->getData()['price']); 
            
            $images = $form->get('image')->getData();
    
            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $img = new Images();
                $img->setName($fichier);
                $product->addImage($img);
                // dd($product);
            }

           
            if(isset($form->getData()['KeyWords']) && sizeof($form->getData()['KeyWords'])>0){
                foreach ($form->getData()['KeyWords'] as $value) {
                    $product->addKeyWord($value);
                }
            }
        
            if(sizeof($form->getExtraData())>0){
                
                if($form->getData()['Category']->getId() == 1){
                    foreach ($product->getTshirtSizes() as  $value) {
                        $product->removeTshirtSize($value);
                    }
                    foreach ($form->getExtraData()['tshirtSizes'] as $size) {
                        $product->addTshirtSize($tshirtSizesRepository->find($size));
                        // dd($product);
                    }
                }else{
                    foreach ($form->getExtraData()['shoesSizes'] as $size) {
                        $product->addShoesSize($shoesSizesRepository->find($size));
                    }
                }
            }
            $productRepository->add($product, true);
             return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
