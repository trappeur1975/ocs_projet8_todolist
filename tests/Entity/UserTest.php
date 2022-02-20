<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{

    public function getUserEntity(): User
    {
        self::bootKernel();
        // return new User();
        $theUser = new User();
        $theUser->setUsername('username');
        $theUser->setEmail('userEmail@test.com');
        $theUser->setPassword('password');

        return $theUser;
    }

    public function testValidUserEntity(): void
    {
        $myUser = $this->getUserEntity();

        // to recover the "validator" service
        $container = static::getContainer();
        $error = $container->get(ValidatorInterface::class)->validate($myUser);

        $this->assertCount(0, $error);
    }

    public function testInvalidUsernameBlank(): void
    {
        $myUser = $this->getUserEntity();
        $myUser->setUsername('');

        // to recover the "validator" service
        $container = static::getContainer();
        $error = $container->get(ValidatorInterface::class)->validate($myUser);

        $this->assertCount(1, $error);
    }

    public function testValidRoleDefault(): void
    {
        $myUser = $this->getUserEntity();
        $this->assertSame(['ROLE_USER'], $myUser->getRoles());
    }

    public function testInvalidEmailBlank(): void
    {
        $myUser = $this->getUserEntity();
        $myUser->setEmail('');

        // to recover the "validator" service
        $container = static::getContainer();
        $error = $container->get(ValidatorInterface::class)->validate($myUser);
        $this->assertCount(1, $error);
    }

    public function testInvalidEmailFormat(): void
    {
        $myUser = $this->getUserEntity();
        $myUser->setEmail('monemail');

        // to recover the "validator" service
        $container = static::getContainer();
        $error = $container->get(ValidatorInterface::class)->validate($myUser);
        $this->assertCount(1, $error);
    }

    public function testValidNoTasks()
    {
        $myUser = $this->getUserEntity();
        $this->assertEmpty($myUser->getTasks());
    }

    public function testValidTask(): void
    {
        $myUser = $this->getUserEntity();
        $task1 = new Task;
        $myUser->addTask($task1);
        $this->assertSame($task1->getAuthor(), $myUser);
        $this->assertCount(1, $myUser->getTasks());
        // dd($myUser->getTasks());
        // $this->assertInstanceOf(Collection::class, $myUser->getTasks()); //pourquoi ne fonctionne pas allaors que dd($myUser->getTasks() confime que c est une Collection
    }

    public function testValidRemoveTasks()
    {
        $myUser = $this->getUserEntity();
        $task1 = new Task;
        $myUser->addTask($task1);
        $this->assertCount(1, $myUser->getTasks());
        $myUser->removeTask($task1);
        $this->assertCount(0, $myUser->getTasks());
    }
}
