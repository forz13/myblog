<?php

namespace Blogger\BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{

    public function getCategoryList()
    {
        $em = $this->getEntityManager();
        $resultCategoryList = [];
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->addOrderBy('c.sort');

        $categoryList = $qb->getQuery()
            ->getResult();
        if ($categoryList && count($categoryList) > 0) {
            foreach ($categoryList as $cat) {
                $count = $em->getRepository('BloggerBlogBundle:Blog')->getCountBlogsWithCategory($cat->getId());
                $resultCategoryList[] = [
                    'id'          => $cat->getId(),
                    'name'        => $cat->getName(),
                    'blogs_count' => $count,
                ];
            }
        }
        return $resultCategoryList;
    }

}