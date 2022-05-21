<?php

namespace App\Controller;

use App\Entity\CategoryProdcut;
use App\Entity\Product;
use App\Entity\ShoesSizes;
use App\Entity\Sizes;
use App\Entity\TshirtSizes;
use App\Form\ProductType;
use App\Repository\CategoryProdcutRepository;
use App\Repository\ProductRepository;
use App\Repository\ShoesSizesRepository;
use App\Repository\SizesRepository;
use App\Repository\TshirtSizesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice/product')]
class ProductController extends AbstractController
{

    private $sizes;
    private $isFirstLoad;

    public function __construct(SizesRepository $sizes)
    {
        $this->sizes = $sizes;
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
    public function new(Request $request, TshirtSizesRepository $tshirtSizesRepository,ShoesSizesRepository $shoesSizesRepository, ProductRepository $productRepository, CategoryProdcutRepository $categoryProdcutRepository): Response
    {
        $prePoppulate = null;
        if ($request->get('form') != null) {
            $prePoppulate = $request->get('form')["Category"];
        }

        $product = new Product();

        $form = $this->createFormBuilder(["Category" => $prePoppulate != null ? $categoryProdcutRepository->find($prePoppulate) : $categoryProdcutRepository->findAll()], array('allow_extra_fields' => true))
            ->add('name', TextType::class, [
                "required" => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('descrip', TextType::class, [
                "required" => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'data_class' => null,
                'required' => false,
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
                                "choice_attr" => function ($choice, $key, $value) {
                                    return ['class' => 'form-control'];
                                },
                                'attr' => [
                                    'class' => 'form-control'
                                ],
                            ]);
                        } else {
                            $form->add('shoesSizes', EntityType::class, [
                                "class" => ShoesSizes::class,
                                "multiple" => true,
                                'mapped' => false,
                                "choice_attr" => function ($choice, $key, $value) {
                                    return ['class' => 'form-control'];
                                },
                                'attr' => [
                                    'class' => 'form-control'
                                ],
                            ]);
                        }
                    }
                }
            })
            ->getForm();


        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $product->setName($form->getData()['name']);
            $product->setDescrip($form->getData()['descrip']);
            $product->setImage('listForNone');
            $product->setCategory($form->getData()['Category']);
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
            // return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
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
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
