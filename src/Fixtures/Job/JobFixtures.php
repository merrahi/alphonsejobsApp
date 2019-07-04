<?php
// src\App\Fixu=tures\Job\JobFixtures.php
namespace App\Fixtures\Job;

use App\Entity\Job;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;



class JobFixtures extends Fixture  
{
    public function load(ObjectManager $manager)
    {
        $logos=['alphonse.png','sensiolabs.png','bouygues.jpg','huawei.png','orange.png'];
        // create 5 Job! Bam!
        for ($i = 0; $i < 5; $i++) {
            $job = new Job();
            $job->settype("Developpement web".$i);
            $job->setCompany("Alphonse ".$i);  
            $job->setLogo($logos[$i]);    
            $job->setUrl($logos[$i]);    
            $job->setPosition('Evry');
            $job->setLocation('Ile de france');
            $job->setDescription('bababababab'.$i);
            $job->setHowToApply("to apply for this job you can click to :".$i);
            $job->setToken('Token'.mt_rand(10, 100));
            $job->setIsPublic(true);
            $job->setIsValidated(true);
            $job->setEmail('email'.$i.'@gmailcom');
            $job->setCreatedAt(new \DateTime());
            $job->setExpiresAt(date_modify(new \DateTime(), '+50 day'));
            $job->setUpdatedAt(date_modify(new \DateTime(), '+10 day'));
            $job->setCategory($this->getReference(Category::class.'_Graphisme'));
            //dd($job);
            //$job->getCategory()->setName("Informatique");
            $manager->persist($job);
        }

        $manager->flush();
    }
   


}