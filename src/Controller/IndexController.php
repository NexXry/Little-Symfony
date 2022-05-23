<?php

namespace App\Controller;

use App\Entity\CategoryProdcut;
use App\Entity\ShoesSizes;
use App\Entity\TshirtSizes;
use App\Repository\CategoryProdcutRepository;
use App\Repository\ProductRepository;
use PhpParser\Node\Stmt\Break_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{


    #[Route('/', name: 'app_index')]
    public function index(Request $request,ProductRepository $productRepository, CategoryProdcutRepository $categoryProdcutRepository): Response
    {

        $product = $productRepository->findAll();

        $prePoppulate = null;

        if ($request->get('form') != null) {
                $prePoppulate = $request->get('form')["categorie"] ?? null;
        }

        $form = $this->createFormBuilder(["categorie" => $prePoppulate != null ? $categoryProdcutRepository->find($prePoppulate) : $categoryProdcutRepository->findAll()], array('allow_extra_fields' => true))
            ->add('keywords', TextType::class, [
                'required' => false,
                'attr' => [
                    "placeholder" => "search by keywords or name of product",
                    'class' => 'form-control'
                ],
                'label' => 'Search',
            ])
            ->add('categorie',ChoiceType::class, [
                'choices' => $categoryProdcutRepository->findAll(),
                "empty_data" => null,
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Category',
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $categ = $event->getData();
                $form = $event->getForm();
                if ($categ != null && isset($categ['categorie'])) {
                    if (!is_array($categ['categorie'])) {
                        if ($categ['categorie']->getId() == 1) {
                            $form->remove('research');
                            $form->add('tshirt',EntityType::class, [
                                'class' => TshirtSizes::class,
                                "placeholder" => "Select a size",
                                'choice_label' => 'name',
                                "multiple" => true,
                                'required' => false,
                                'expanded' => true,
                                'attr' => [
                                    'class' => 'form-control mt-2'
                                ],
                                'required' => false,
                                'label' => 'T-shirt Sizes',
                            ])->add('research', SubmitType::class, [
                                'label' => 'Search',
                                'attr' => [
                                    'class' => 'btn btn-primary my-3'
                                ],
                            ]);
                        } elseif ($categ['categorie']->getId() == 2 ) {
                            $form->remove('research');
                            $form->add('shoes',EntityType::class, [
                                'class' => ShoesSizes::class,
                                "placeholder" => "Select a size",
                                'choice_label' => 'name',
                                "multiple" => true,
                                'required' => false,
                                'expanded' => true,
                                'required' => false,
                                'attr' => [
                                    'class' => 'form-control mt-2'
                                ],
                                'label' => 'Shoes Sizes',
                            ])->add('research', SubmitType::class, [
                                'label' => 'Search',
                                'attr' => [
                                    'class' => 'btn btn-primary my-3'
                                ],
                            ]);
                        }
                    }
                }
            })
            ->add('research', SubmitType::class, [
                'label' => 'Search',
                'attr' => [
                    'class' => 'btn btn-primary my-3'
                ],
            ])
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $product = $productRepository->findByKeyWordOrCategAndOrSizes(
                isset($data['keywords'])? $data['keywords'] : null,
                isset($data['categorie'])? $data['categorie'] : null,
                isset($data['tshirt'])? $data['tshirt'] : null,
                isset($data['shoes'])? $data['shoes'] : null);
            
        

        }


        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'products' => $product,
            "form" => $form->createView(),
        ]);
    }

    #[Route('/about', name: 'app_index_about')]
    public function about(): Response
    {
        return $this->render('index/about.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
