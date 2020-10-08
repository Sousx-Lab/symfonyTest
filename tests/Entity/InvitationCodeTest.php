<?php

namespace App\Tests\Entity;

use App\Entity\InvitationCode;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InvitationCodeTest extends KernelTestCase 
{
    use FixturesTrait;

    public function getEntity(): InvitationCode
    {
        return (new InvitationCode())
            ->setCode('12345')
            ->setDescription('Description du test')
            ->setExpireAt(new \DateTime());

    }

    public function assertHasErrors(InvitationCode $code, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($code);
        $messages = [];

        /** @var ConstraintViolation $error */
        foreach($errors as $error)
        {
            $messages[] = $error->getPropertyPath() . " => " . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
      $this->assertHasErrors($this->getEntity(), 0);
       
    }

    public function testInvalidCodeEntity()
    {
       $this->assertHasErrors($this->getEntity()->setCode('1a345'), 1);
       $this->assertHasErrors($this->getEntity()->setCode('1345'), 1);
      
    }

    public function testInvalidBlankCodeEntity()
    {
       $this->assertHasErrors($this->getEntity()->setCode(''), 1);
    }

    public function testInvalidBlankDescriptionEntity()
    {
        $this->assertHasErrors($this->getEntity()->setDescription(''), 1);
    }

    public function testInvalidUsedCode()
    {   
        $this->loadFixtureFiles([dirname(__DIR__) . "/Fixtures/Invitation_codes.yaml"]);
        $this->assertHasErrors($this->getEntity()->setCode('54321'), 1);
    }
}