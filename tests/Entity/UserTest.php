<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{
    private $myUser;

    public function setUp(): void
    {
        self::bootKernel();
        $this->myUser = new User();
        $this->myUser->setUsername('username');
        $this->myUser->setEmail('userEmail@test.com');
        $this->myUser->setPassword('password');
    }

    public function testValidUserEntity(): void
    {
        // to recover the "validator" service
        $container = static::getContainer();
        $error = $container->get(ValidatorInterface::class)->validate($this->myUser);

        $this->assertCount(0, $error);
    }

    public function testInvalidUsernameBlank(): void
    {
        $this->myUser->setUsername('');

        // to recover the "validator" service
        $container = static::getContainer();
        $error = $container->get(ValidatorInterface::class)->validate($this->myUser);

        $this->assertCount(1, $error);
    }

    public function testValidRoleDefault(): void
    {
        $this->assertSame(['ROLE_USER'], $this->myUser->getRoles());
    }

    public function testInvalidEmailBlank(): void
    {
        $this->myUser->setEmail('');

        // to recover the "validator" service
        $container = static::getContainer();
        $error = $container->get(ValidatorInterface::class)->validate($this->myUser);
        $this->assertCount(1, $error);
    }

    public function testInvalidEmailFormat(): void
    {
        $this->myUser->setEmail('monemail');

        // to recover the "validator" service
        $container = static::getContainer();
        $error = $container->get(ValidatorInterface::class)->validate($this->myUser);
        $this->assertCount(1, $error);
    }

    public function testValidNoTasks()
    {
        $this->assertEmpty($this->myUser->getTasks());
    }

    public function testValidTask(): void
    {
        $task1 = new Task();
        $this->myUser->addTask($task1);
        $this->assertSame($task1->getAuthor(), $this->myUser);
        $this->assertCount(1, $this->myUser->getTasks());
    }

    public function testValidRemoveTasks()
    {
        $task1 = new Task;
        $this->myUser->addTask($task1);
        $this->assertCount(1, $this->myUser->getTasks());
        $this->myUser->removeTask($task1);
        $this->assertCount(0, $this->myUser->getTasks());
    }
}
