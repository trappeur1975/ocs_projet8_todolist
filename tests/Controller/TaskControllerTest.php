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
    const EMAIL_USER1 = 'titi@hotmail.com';

    /**
     * constant represent a email with role is USER
     */
    const EMAIL_USER2 = 'tata@test.com';

    /**
     * constant represent a email with role is ADMIN
     */
    const EMAIL_ADMIN = 'yeye@hotmail.com';

    /**
     * constant represent a task that we are going to create 
     */
    const TASK_TITLE = 'test create Tasks';

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

        // $this->client->followRedirect();
        // $this->assertSelectorExists('.alert.alert-danger', "VOUS AVEZ ETE REDIRIGE SUR CETTE PAGE CAR : N'étant pas administrateur de ce site vous n'avez pas accès à la ressource que vous avez demandez");
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
        $form['task[content]'] = 'ceci est un test de creation de tache';

        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('.alert.alert-success', "Superbe ! La tâche a été bien été ajoutée.");
        $this->assertNotNull($taskRepository->findOneBy(['title' => SELF::TASK_TITLE]));
    }

    public function testDeleteTasksNoLogged(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $AuthorTask = $userRepository->findOneByEmail(SELF::EMAIL_USER1);

        $task = $taskRepository->findOneBy([
            'title' => SELF::TASK_TITLE,
            'author' => $AuthorTask
        ]);

        $idTaskDelete = $task->getId();

        $this->client->request('GET', "/tasks/$idTaskDelete/delete");

        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testDeleteTaskWichNoAuthor()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $AuthorTask = $userRepository->findOneByEmail(SELF::EMAIL_USER1);
        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER2);

        $task = $taskRepository->findOneBy([
            'title' => SELF::TASK_TITLE,
            'author' => $AuthorTask
        ]);

        $idTaskDelete = $task->getId();

        $this->client->loginUser($testUser);

        // $this->client->followRedirects();

        $crawler = $this->client->request('GET', "/tasks/$idTaskDelete/delete");

        // ----------------CA MARCHE--------------
        $this->assertResponseRedirects('/');
        $this->client->followRedirect();
        // ----------------CA MARCHE--------------

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('.alert.alert-danger', "VOUS AVEZ ETE REDIRIGE SUR CETTE PAGE CAR : cette tache ne vous appartient pas ou vous n'etes pas admin ce site, vous n'avez donc pas le droit de la supprimer");

        $this->assertNotNull($taskRepository->findOneBy(['title' => SELF::TASK_TITLE]));
    }

    public function testEditasksNoLogged(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $AuthorTask = $userRepository->findOneByEmail(SELF::EMAIL_USER1);

        $task = $taskRepository->findOneBy([
            'title' => SELF::TASK_TITLE,
            'author' => $AuthorTask
        ]);

        $idTaskEdit = $task->getId();

        $this->client->request('GET', "/tasks/$idTaskEdit/edit");

        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testEditasksUserLogged()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER1);

        $task = $taskRepository->findOneBy([
            'title' => SELF::TASK_TITLE,
            'author' => $testUser
        ]);

        $idTaskEdit = $task->getId();

        $this->client->loginUser($testUser);

        // $this->client->followRedirects();

        $crawler = $this->client->request('GET', "/tasks/$idTaskEdit/edit");
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'task[content]' => 'content modifié'
        ]);
        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks', Response::HTTP_FOUND);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert.alert-success', "Superbe ! La tâche a bien été modifiée.");
        $this->assertNotNull($taskRepository->findOneBy(['id' => $idTaskEdit]));
        $taskUpdated = $taskRepository->findOneBy(['id' => $idTaskEdit]);
        $this->assertSame($taskUpdated->getContent(), 'content modifié');
    }

    public function testToggleTasksNoLogged(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $AuthorTask = $userRepository->findOneByEmail(SELF::EMAIL_USER1);

        $task = $taskRepository->findOneBy([
            'title' => SELF::TASK_TITLE,
            'author' => $AuthorTask
        ]);

        $idTaskToggle = $task->getId();

        $this->client->request('GET', "/tasks/$idTaskToggle/toggle");

        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testToggleaTaskUserLogged(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER1);

        $task = $taskRepository->findOneBy([
            'title' => SELF::TASK_TITLE,
            'author' => $testUser
        ]);

        $idTaskToggle = $task->getId();

        $this->client->loginUser($testUser);

        $crawler = $this->client->request('GET', "/tasks/$idTaskToggle/toggle");

        $this->assertResponseRedirects('/tasks', Response::HTTP_FOUND);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert.alert-success', "Superbe ! La tâche ma tache a bien été marquée comme faite.");
    }


    // ----------------------------

    public function testDeleteTaskWichAuthor()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER1);

        $task = $taskRepository->findOneBy([
            'title' => SELF::TASK_TITLE,
            'author' => $testUser
        ]);

        $idTaskDelete = $task->getId();

        $this->client->loginUser($testUser);

        $this->client->followRedirects();

        $crawler = $this->client->request('GET', "/tasks/$idTaskDelete/delete");

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert.alert-success', "Superbe ! La tâche a bien été supprimée.");
        $this->assertNull($taskRepository->findOneBy(['id' => $idTaskDelete]));
    }

    // REVOIR CETTE FUNCTION CAR N ARRIVE PAS A PASSER LA TASK CREER AVEC UN AUTHOR NULL DONC DOIT INTERVENIR MANUELLEMENT DANS LA BASE DE DONNE
    // public function testDeleteTaskAuthorNullByADMIN()
    // {
    //     $userRepository = static::getContainer()->get(UserRepository::class);
    //     $taskRepository = static::getContainer()->get(TaskRepository::class);

    //     $testUser = $userRepository->findOneByEmail(SELF::EMAIL_ADMIN);
    //     // $this->client->loginUser($testUser);
    //     // $this->client->followRedirects();
    //     // $crawler = $this->client->request('GET', '/tasks/create');
    //     // $form = $crawler->selectButton('Ajouter')->form();
    //     // $form['task[title]'] = 'test tache null';
    //     // $form['task[content]'] = 'ceci est un test de creation de tache null';

    //     // $crawler = $this->client->submit($form);
    //     // $task = $taskRepository->findOneBy(['title' => 'test tache null']);
    //     // $task->setAuthor(null); //MARCHE PAS POURQUOI??

    //     $task = $taskRepository->findOneBy(['title' => 'test tache null']);

    //     $idTaskDelete = $task->getId();

    //     $this->client->loginUser($testUser);

    //     $this->client->followRedirects();

    //     $crawler = $this->client->request('GET', "/tasks/$idTaskDelete/delete");

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorExists('.alert.alert-success', "Superbe ! La tâche a bien été supprimée.");
    //     $this->assertNull($taskRepository->findOneBy(['id' => $idTaskDelete]));
    // }



}
