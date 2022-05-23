<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
  
   
   public function findByKeyWordOrCategAndOrSizes($valueK,$valueC,$valueT,$valueS): array
   {

   

       $data=[
        $valueK,$valueC,$valueT,$valueS
       ];

    //    dd($data);

    $requete = $this->createQueryBuilder('p')
    ->leftJoin("p.shoesSizes", "s")
    ->leftJoin("p.tshirtSizes", "t")
    ->leftJoin("p.KeyWords", "k")
    ->innerJoin("p.Category", "c");

  /*
   ->orderBy('c.id',"DESC")
   ->getQuery()
   ->getResult()
  */

    switch ($data) {
            case ($data[0]==null && $data[1]==null) && ($data[2] ==null && $data[3] ==null):
                return self::findAll();
            break;
            case ($data[0]!=null && $data[1]==null) && ($data[2] ==null && $data[3] ==null):
                $requete->andWhere('k.name = :keywords')
                ->setParameter('keywords', $data[0])
                 ->orderBy('k.name',"DESC")
                ;

                return $requete->getQuery()->getResult();
            break;
            case ($data[0] == null && $data[1]!=null) && (($data[2]?->isEmpty() || $data[2] == null) &&($data[3]?->isEmpty() || $data[3] == null)):
                // dd($data[1]->getName());
                $requete->andWhere('c.name = :categorie')
                ->setParameter('categorie', $data[1]->getName())
                 ->orderBy('c.name',"DESC")
                ;
             
                return $requete->getQuery()->getResult();
            break;
            case ($data[0] ==null && $data[1]!=null) && (($data[2] != [] || $data[2] == null) && ($data[3] != [] || $data[3] == null)):
                // dd("categ and sizes");
                $requete->andWhere($requete->expr()->in('t.name', ':tshirt').' or '.$requete->expr()->in('s.name', ':tshirt').'and c.name = :categorie')
                ->setParameter('categorie', $data[1]->getName());
            
                $arr=[];
                foreach ($data[2] != null ? $data[2] : $data[3] as $value) {
                    $arr[]=$value->getName();
                }
                $requete->setParameter('tshirt', $arr, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY);

                $requete ->orderBy('c.name',"DESC");
           
                return $requete->getQuery()->getResult();
            break;
            case ($data[0]!=null && $data[1]!=null) && (($data[2]?->isEmpty() || $data[2] == null) && ($data[3]?->isEmpty() || $data[3] == null)):
                $requete->andWhere('k.name = :keywords or c.name = :categorie')
                ->setParameter('categorie', $data[1]->getName())
                ->setParameter('keywords', $data[0])
                 ->orderBy('c.name',"DESC")
                ;

                return $requete->getQuery()->getResult();
            break;
            // case ($data[0]!=null && $data[1]!=null) && ($data[2] != [] || $data[2] == null && $data[3] != [] || $data[3] == null):
            //     dd("keyword and categ and sizes");
            // break;
        default:
            return self::findAll();
            break;
    }


   }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
