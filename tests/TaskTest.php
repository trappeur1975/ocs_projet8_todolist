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
        return new Task();
    }

    public function testValidTitle(): void
    {
        $myTask = $this->getTaskEntity();
        $myTask->setTitle(SELF::TASK_TITLE);
        $this->assertSame(SELF::TASK_TITLE, $myTask->getTitle());
    }

    public function testValidContent(): void
    {
        $myTask = $this->getTaskEntity();
        $myTask->setContent(SELF::TASK_CONTENT);
        $this->assertSame(SELF::TASK_CONTENT, $myTask->getContent());
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


    public function testInvalidTitleBlank(): void  //ok
    // public function testInvalidTitleBlank(ValidatorInterface $validator): void
    {
        self::bootKernel();
        $myTask = $this->getTaskEntity();
        $myTask->setTitle('');

        // to recover the "validator" service
        $container = static::getContainer();
        // $error = $container->get('validator')->validate($myTask); //deprecier
        $error = $container->get(ValidatorInterface::class)->validate($myTask); //ok

        $this->assertCount(2, $error);
    }
}
