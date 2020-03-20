<?php
// src\App\Fixu=tures\Category\CatgoryFixtures.php
namespace App\Fixtures\Category;

use App\Entity\Category;
use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;



class CategoryFixtures extends Fixture
{
        //  const CATEGORY_REFERENCE='categories';
          public $cats;
  
          // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
          public function load(ObjectManager $manager)
          {
            // Liste des noms de catégorie à ajouter
            $names = array(
              'programming',
              'mobile',
              'graphisme',
              'integration',
              'network'
            );
        
            foreach ($names as $name) {
              // On crée la catégorie
              $category = new Category();
              $category->setName($name);

              // On la persiste
              $manager->persist($category);
              $this->addReference('category-'.$name, $category);
            }
        
            // On déclenche l'enregistrement de toutes les catégories
            $manager->flush();
            // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant

          }
        

}