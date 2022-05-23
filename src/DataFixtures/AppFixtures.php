<?php

namespace App\DataFixtures;

use App\Entity\CategoryProdcut;
use App\Entity\KeyWords;
use App\Entity\Product;
use App\Entity\ShoesSizes;
use App\Entity\TshirtSizes;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = new User();

        $admin->setEmail('admin@hellorse.fr');
        $admin->setNom('Castex');
        $admin->setPrenom('Nicolas');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword('$2y$13$M4DuRcw9sDBLh/kqz9yuEuESufEsvcsd.NGYdvkztZlln/AGPqDy.'); // MDP hellorse (HASHER)

        $manager->persist($admin);

        $keywordsA = new KeyWords();
        $keywordsB = new KeyWords();
        $keywordsC = new KeyWords();
        $keywordsD = new KeyWords();

        $keywordsA->setName('A');
        $keywordsB->setName('B');
        $keywordsC->setName('C');
        $keywordsD->setName('D');


        $manager->persist($keywordsA);
        $manager->persist($keywordsB);
        $manager->persist($keywordsC);
        $manager->persist($keywordsD);


        $tshirtSizesXS = new TshirtSizes();
        $tshirtSizesS = new TshirtSizes();
        $tshirtSizesM = new TshirtSizes();
        $tshirtSizesL = new TshirtSizes();
        $tshirtSizesXL = new TshirtSizes();

        $tshirtSizesXS->setName("XS");
        $tshirtSizesS->setName("S");
        $tshirtSizesM->setName("M");
        $tshirtSizesL->setName('L');
        $tshirtSizesXL->setName("XL");

        $shoes38 = new ShoesSizes();
        $shoes39 = new ShoesSizes();
        $shoes40 = new ShoesSizes();
        $shoes41 = new ShoesSizes();
        $shoes42 = new ShoesSizes();
        $shoes43 = new ShoesSizes();
        $shoes44 = new ShoesSizes();
        $shoes45 = new ShoesSizes();
        $shoes46 = new ShoesSizes();

        $shoes38->setName("38");
        $shoes39->setName('39');
        $shoes40->setName('40');
        $shoes41->setName('41');
        $shoes42->setName('42');
        $shoes43->setName('43');
        $shoes44->setName('44');
        $shoes45->setName('45');
        $shoes46->setName('46');

        $manager->persist($shoes38);
        $manager->persist($shoes39);
        $manager->persist($shoes40);
        $manager->persist($shoes41);
        $manager->persist($shoes42);
        $manager->persist($shoes43);
        $manager->persist($shoes44);
        $manager->persist($shoes45);
        $manager->persist($shoes46);


        $manager->persist($tshirtSizesXS);
        $manager->persist($tshirtSizesS);
        $manager->persist($tshirtSizesM);
        $manager->persist($tshirtSizesL);
        $manager->persist($tshirtSizesXL);

        $categAll = new CategoryProdcut();
        $categTshirt = new CategoryProdcut();
        $categShoes = new CategoryProdcut();

        $categAll->setName("All")->setId(1);
        $categTshirt->setName('Tshirt')->setId(2);
        $categShoes->setName("Shoes")->setId(3);

        $manager->persist($categAll);
        $manager->persist($categTshirt);
        $manager->persist($categShoes);

        $productA = new Product();
        $productB = new Product();
        $productC = new Product();
        $productD = new Product();

        $productA->setName('TshirtA');
        $productB->setName('ShoesB');
        $productC->setName('TshirtC');
        $productD->setName('ShoesD');

        $productA->setDescrip('A');
        $productB->setDescrip('B');
        $productC->setDescrip('C');
        $productD->setDescrip('D');

        $productA->setPrice(9.99);
        $productB->setPrice(9.99);
        $productC->setPrice(9.99);
        $productD->setPrice(9.99);
        

        $productA->setCategory($categTshirt);
        $productB->setCategory($categShoes);
        $productC->setCategory($categTshirt);
        $productD->setCategory($categShoes);

        $productA->addTshirtSize($tshirtSizesXS)->addTshirtSize($tshirtSizesS)->addTshirtSize($tshirtSizesM)->addTshirtSize($tshirtSizesL)->addTshirtSize($tshirtSizesXL);
        $productB->addShoesSize($shoes38)->addShoesSize($shoes39)->addShoesSize($shoes40)->addShoesSize($shoes41)->addShoesSize($shoes42)->addShoesSize($shoes43)->addShoesSize($shoes44)->addShoesSize($shoes45)->addShoesSize($shoes46);
        $productC->addTshirtSize($tshirtSizesL);
        $productD->addShoesSize($shoes38)->addShoesSize($shoes46);

        $productA->addKeyWord($keywordsA);
        $productB->addKeyWord($keywordsD);
        $productC->addKeyWord($keywordsB);
        $productD->addKeyWord($keywordsC);

        $manager->persist($productA);
        $manager->persist($productB);
        $manager->persist($productC);
        $manager->persist($productD);



        $manager->flush();
    }
}
