<?php

namespace App\Tests;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskTest extends KernelTestCase
{
    const TASK_TITLE = "task title";
    const TASK_CONTENT = "task content";

    // public $mytask = new Task();

    public function getTaskEntity(): Task
    {
        self::bootKernel();
        // return new Task();
        $theTask = new Task();
        $theTask->setTitle('the title');
        $theTask->setContent('the content task');

        return $theTask;
    }

    public function testValidTaskEntity(): void
    {
        $myTask = $this->getTaskEntity();

        // to recover the "validator" service
        $container = static::getContainer();
        $error = $container->get(ValidatorInterface::class)->validate($myTask);

        $this->assertCount(0, $error);
    }

    public function testInvalidTitleBlank(): void  //ok
    // public function testInvalidTitleBlank(ValidatorInterface $validator): void
    {
        $myTask = $this->getTaskEntity();
        $myTask->setTitle('');

        // to recover the "validator" service
        $container = static::getContainer();
        // $error = $container->get('validator')->validate($myTask); //deprecier
        $error = $container->get(ValidatorInterface::class)->validate($myTask); //ok

        $this->assertCount(1, $error);
    }

    public function testInvalidContentBlank(): void
    {
        $myTask = $this->getTaskEntity();
        $myTask->setContent('');

        // to recover the "validator" service
        $container = static::getContainer();
        // $error = $container->get('validator')->validate($myTask); //deprecier
        $error = $container->get(ValidatorInterface::class)->validate($myTask); //ok

        $this->assertCount(1, $error);
    }

    public function testValidCreatedAt(): void
    {
        $myTask = $this->getTaskEntity();
        $this->assertInstanceOf(DateTime::class, $myTask->getCreatedAt());
    }

    public function testValidDoneDefault(): void
    {
        $myTask = $this->getTaskEntity();
        $this->assertNotTrue($myTask->getIsDone());
    }

    public function testValidToggle(): void
    {
        $myTask = $this->getTaskEntity();
        $myTask->toggle(true);
        $this->assertTrue($myTask->getIsDone());
    }

    public function testValidAuthor(): void
    {
        $myTask = $this->getTaskEntity();
        $user = new User();
        $myTask->setAuthor($user);
        $this->assertEquals($user, $myTask->getAuthor());
    }
}
