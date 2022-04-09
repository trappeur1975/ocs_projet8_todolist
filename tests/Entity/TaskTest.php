<?php

namespace App\Tests;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskTest extends KernelTestCase
{
    protected const TASK_TITLE = "task title";
    protected const TASK_CONTENT = "task content";

    private $myTask;

    public function setUp(): void
    {
        self::bootKernel();
        $this->myTask = new Task();
        $this->myTask->setTitle('the title');
        $this->myTask->setContent('the content task');
    }

    public function testValidTaskEntity(): void
    {
        // to recover the "validator" service
        $container = static::getContainer();
        $error = $container->get(ValidatorInterface::class)->validate($this->myTask);

        $this->assertCount(0, $error);
    }

    // public function testInvalidTitleBlank(ValidatorInterface $validator): void
    public function testInvalidTitleBlank(): void  //ok
    {
        $this->myTask->setTitle('');

        // to recover the "validator" service
        $container = static::getContainer();
        // $error = $container->get('validator')->validate($myTask); //deprecier
        $error = $container->get(ValidatorInterface::class)->validate($this->myTask); //ok

        $this->assertCount(1, $error);
    }

    public function testValidCreatedAt(): void
    {
        $this->assertInstanceOf(DateTime::class, $this->myTask->getCreatedAt());
    }

    public function testValidDoneDefault(): void
    {
        $this->assertNotTrue($this->myTask->getIsDone());
    }

    public function testValidToggle(): void
    {
        $this->myTask->toggle(true);
        $this->assertTrue($this->myTask->getIsDone());
    }

    public function testValidAuthor(): void
    {
        $user = new User();
        $this->myTask->setAuthor($user);
        $this->assertEquals($user, $this->myTask->getAuthor());
    }
}
