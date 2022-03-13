<?php

namespace App\Tests;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    /**
     * constant represent a email with role is USER
     */
    const EMAIL_USER1 = 'user1@hotmail.com';

    /**
     * constant represent a email with role is USER
     */
    const EMAIL_USER2 = 'user2@hotmail.com';

    /**
     * constant represent a email with role is ADMIN
     */
    const EMAIL_ADMIN = 'admin@hotmail.com';

    /**
     * constant that represents the title of the task 
     */
    const TASK_TITLE = 'test create Tasks';

    /**
     * constant that represents the content of the task 
     */
    const TASK_CONTENT = 'ceci est un contenu de tache';


    /**
     * constant represent a task of user1
     */
    const TASK_ID_AUTHOR1 = 4;

    /**
     * constant represent a task of user1
     */
    const TASK_ID_AUTHORNULL = 1;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testListTasksNoLogged(): void
    {
        $this->client->request('GET', '/tasks');
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('label', 'Mot de passe');
    }

    public function testListTasksWithRoleUSER()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER1);
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCreateTasksNoLogged(): void
    {
        $this->client->request('GET', '/tasks');
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('label', 'Mot de passe');
    }

    public function testCreateTaskWithRoleUSER()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER1);
        $this->client->loginUser($testUser);

        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/tasks/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('label', 'Title');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = SELF::TASK_TITLE;
        $form['task[content]'] = SELF::TASK_CONTENT;

        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('.alert.alert-success', "Superbe ! La tâche a été bien été ajoutée.");
        $this->assertNotNull($taskRepository->findOneBy(['title' => SELF::TASK_TITLE]));
    }

    public function testDeleteTasksNoLogged(): void
    {
        $this->client->request('GET', '/tasks/' . SELF::TASK_ID_AUTHOR1 . '/delete');

        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testDeleteTaskWichNoAuthor()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER2);

        $this->client->loginUser($testUser);

        // $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/tasks/' . SELF::TASK_ID_AUTHOR1 . '/delete');

        // ----------------CA MARCHE--------------
        $this->assertResponseRedirects('/');
        $this->client->followRedirect();
        // ----------------CA MARCHE--------------

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('.alert.alert-danger', "VOUS AVEZ ETE REDIRIGE SUR CETTE PAGE CAR : cette tache ne vous appartient pas ou vous n'etes pas admin ce site, vous n'avez donc pas le droit de la supprimer");

        $this->assertNotNull($taskRepository->find(SELF::TASK_ID_AUTHOR1));
    }

    public function testEditasksNoLogged(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $AuthorTask = $userRepository->findOneByEmail(SELF::EMAIL_USER1);

        $task = $taskRepository->findOneBy(['author' => $AuthorTask]);

        $idTaskEdit = $task->getId();

        $this->client->request('GET', "/tasks/$idTaskEdit/edit");

        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testEditasksUserLogged()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER1);

        $task = $taskRepository->findOneBy(['author' => $testUser]);

        $idTaskEdit = $task->getId();

        $this->client->loginUser($testUser);

        // $this->client->followRedirects();

        $crawler = $this->client->request('GET', "/tasks/$idTaskEdit/edit");
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'task[content]' => SELF::TASK_CONTENT
        ]);
        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks', Response::HTTP_FOUND);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert.alert-success', "Superbe ! La tâche a bien été modifiée.");
        $this->assertNotNull($taskRepository->findOneBy(['id' => $idTaskEdit]));
        $taskUpdated = $taskRepository->findOneBy(['id' => $idTaskEdit]);
        $this->assertSame($taskUpdated->getContent(), SELF::TASK_CONTENT);
    }

    public function testToggleTasksNoLogged(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $AuthorTask = $userRepository->findOneByEmail(SELF::EMAIL_USER1);

        $task = $taskRepository->findOneBy(['author' => $AuthorTask]);

        $idTaskToggle = $task->getId();

        $this->client->request('GET', "/tasks/$idTaskToggle/toggle");

        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testToggleaTaskUserLogged(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER1);

        $task = $taskRepository->findOneBy(['author' => $testUser]);

        $idTaskToggle = $task->getId();

        $this->client->loginUser($testUser);

        $crawler = $this->client->request('GET', "/tasks/$idTaskToggle/toggle");

        $this->assertResponseRedirects('/tasks', Response::HTTP_FOUND);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert.alert-success', "Superbe ! La tâche ma tache a bien été marquée comme faite.");
    }

    public function testDeleteTaskWichAuthor()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER1);

        $this->client->loginUser($testUser);

        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/tasks/' . SELF::TASK_ID_AUTHOR1 . '/delete');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert.alert-success', "Superbe ! La tâche a bien été supprimée.");
        $this->assertNull($taskRepository->find(SELF::TASK_ID_AUTHOR1));
    }

    public function testDeleteTaskAuthorNullByUSER()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER1);

        $this->client->loginUser($testUser);

        $crawler = $this->client->request('GET', '/tasks/' . SELF::TASK_ID_AUTHORNULL . '/delete');

        $this->assertResponseRedirects('/');
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('.alert.alert-danger', "VOUS AVEZ ETE REDIRIGE SUR CETTE PAGE CAR : cette tache ne vous appartient pas ou vous n'etes pas admin ce site, vous n'avez donc pas le droit de la supprimer");
        $this->assertNotNull($taskRepository->find(SELF::TASK_ID_AUTHORNULL));
    }

    public function testDeleteTaskAuthorNullByADMIN()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $testAdmin = $userRepository->findOneByEmail(SELF::EMAIL_ADMIN);

        $this->client->loginUser($testAdmin);

        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/tasks/' . SELF::TASK_ID_AUTHORNULL . '/delete');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert.alert-success', "Superbe ! La tâche a bien été supprimée.");
        $this->assertNull($taskRepository->find(SELF::TASK_ID_AUTHORNULL));
    }
}
