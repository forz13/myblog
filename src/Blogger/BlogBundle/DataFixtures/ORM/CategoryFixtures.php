<?php
/**
 * Created by PhpStorm.
 * User: forz
 * Date: 06.01.17
 * Time: 20:40
 */

namespace Blogger\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Blogger\BlogBundle\Entity\Category;

class CategoryFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('PHP');
        $category->setSort(1);
        $category->setCreated(new \DateTime("2011-07-23 10:22:46"));
        $manager->persist($category);

        $category = new Category();
        $category->setName('Javascript');
        $category->setSort(2);
        $category->setCreated(new \DateTime("2011-08-23 10:22:46"));
        $manager->persist($category);

        $category = new Category();
        $category->setName('Node.js');
        $category->setSort(3);
        $category->setCreated(new \DateTime("2011-09-23 10:22:46"));
        $manager->persist($category);

        $category = new Category();
        $category->setName('Linux');
        $category->setSort(4);
        $category->setCreated(new \DateTime("2011-10-23 10:22:46"));
        $manager->persist($category);

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}