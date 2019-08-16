<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class ProductRepository extends EntityRepository
{
    public function insertProduct($product)
    {
        $result = $this->findBy([
            'code' => $product->getCode()
        ]);
        // This check is added to make sure we do not duplicate rows into the DB
        if (!empty($result)) {
            return false;
        } else {
            try {
                $this->getEntityManager()->persist($product);
            } catch (ORMException $e) {
            }
            try {
                $this->getEntityManager()->flush($product);
            } catch (OptimisticLockException $e) {
            } catch (ORMException $e) {
            }
            return true;
        }
    }
}