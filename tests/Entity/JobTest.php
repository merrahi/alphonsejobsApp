<?php
// src/Controller/Job/JobController.php
// tests/Entity/JobTest.php
namespace App\Tests\Entity;

use App\Entity\Job;
use PHPUnit\Framework\TestCase;
use  Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class JobTest extends KernelTestCase
{

    public $validator;

    protected function setUp()
    {
        $this->validator    = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
       // $this->validator =self::$container->get('validator');
    }

    /**
     * @param $email
     * @dataProvider getValidEmails
     */
    public function testEmailValidate($email)
    {
        $job = new Job();
        $job->setEmail($email);
        // assert that email has been created
        $this->assertEquals($email, $job->getEmail());
        // validate email constraint
        $violations =$this->validator->validate($job);
        //$violations=$validator->validate($job);
        $messages=[];
        foreach ($violations as $violation) {
            $messages[]=$violation->getPropertyPath() .' => '. $violation->getMessage();
        }
        /** @var TYPE_NAME $violations */
        $this->assertCount(0,$violations,implode(',',$messages));;
    }
    public function getValidEmails(){
        return [
            ['fabien@symfony.com'],
            ['example@example.co.uk'],
            ['fabien_potencier@example.fr'],
            ['example@example.co..uk'],
            ['example@example.co..uk'],
            ['example@-example.com'],
            [sprintf('example@%s.com', str_repeat('a', 64))],
        ];
    }
    /**
     * @param $email
     * @dataProvider getInValidEmails
     */
    public function testEmailInValidate($email)
    {
        $job = new Job();
        $job->setEmail($email);
        // assert that email has been created
        $this->assertEquals($email, $job->getEmail());
        // validate email constraint
        $violations =$this->validator->validate($job);
        //$violations=$validator->validate($job);
        $messages=[];
        foreach ($violations as $violation) {
            $messages[]=$violation->getPropertyPath() .' => '. $violation->getMessage();
        }
        /** @var TYPE_NAME $violations */
        $this->assertCount(1,$violations,implode(',',$messages));;
    }
    public function getInValidEmails(){
        return [
            ['fabien'],
            ['example@example?.co.uk'],
            ['fabien_potencier@example.fr29999999'],
            ['example@example.#co..uk'],
            ['Abc..123@examplecom'],
            [sprintf('example*@%s.com', str_repeat('a', 64))],
        ];
    }


    protected function createValidator()
    {
        // TODO: Implement createValidator() method.
    }
}