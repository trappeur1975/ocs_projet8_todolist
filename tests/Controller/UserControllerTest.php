<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /**
     * constant represent a email with role is USER
     */
    const EMAIL_USER = 'user1@hotmail.com';

    /**
     * constant represents the email of a user used for tests 
     */
    const EMAIL = 'user2@hotmail.com';

    /**
     * constant represent a email with role is ADMIN
     */
    const EMAIL_ADMIN = 'admin@hotmail.com';

    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testListUsersNotConnect(): void
    {
        $this->client->request('GET', '/users');
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testListUsersWithRoleUser()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER);
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        // $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $this->client->followRedirect();
        // $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertSelectorExists('.alert.alert-danger', "VOUS AVEZ ETE REDIRIGE SUR CETTE PAGE CAR : N'étant pas administrateur de ce site vous n'avez pas accès à la ressource que vous avez demandez");
    }

    public function testListUsersWithRoleAdmin()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_ADMIN);
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Liste des utilisateurs');
    }

    public function testCreateUserWithRoleUser()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER);
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        // $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $this->client->followRedirect();
        // $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertSelectorExists('.alert.alert-danger', "VOUS AVEZ ETE REDIRIGE SUR CETTE PAGE CAR : N'étant pas administrateur de ce site vous n'avez pas accès à la ressource que vous avez demandez");
    }

    // !ATTENTION! NE PAS OUBLIER DANS LA BBD DE SUPPRIMER CETTE LIGNE POUR POUVOIR REFAIRE UN TEST PLUS TARD CAR SINON LE TEST ECHOUERA (FAILED)
    public function testCreateUserWithRoleADMIN()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_ADMIN);
        $this->client->loginUser($testUser);

        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/users/create');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'testcreateUser';
        $form['user[password][first]'] = 'testcreateUserpassword';
        $form['user[password][second]'] = 'testcreateUserpassword';
        $form['user[email]'] = 'testcreateUser@hotmail.com';
        // $form['user[roles]'] = 'ROLE_ADMIN'; //taditionnelle
        $form['user[roles]']->select('ROLE_ADMIN');  //pour les select ou les choices(dans mon cas)
        // $form['user[roles]']->tick();    //que pour les checkBox moi ces t un choice

        $crawler = $this->client->submit($form);

        // POURQUOI CA NE FONCTIONNE PAS !!!!!!!!!!!!!!
        // $this->client->followRedirect();
        // $this->assertResponseRedirects('/users');

        // $this->assertResponseRedirects();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('.alert.alert-success', "Superbe ! L'utilisateur a bien été ajouté. ");

        $this->assertNotNull($userRepository->findOneBy(['email' => 'testcreateUser@hotmail.com']));
    }


    public function testEditUserWithRoleUSER()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_USER);
        $this->client->loginUser($testUser);

        $userEdit = $userRepository->findOneBy(['email' => SELF::EMAIL]);
        $userEdit_id = $userEdit->getId();

        $this->client->request('GET', "/users/$userEdit_id/edit");
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        // $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $this->client->followRedirect();
        // $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertSelectorExists('.alert.alert-danger', "VOUS AVEZ ETE REDIRIGE SUR CETTE PAGE CAR : N'étant pas administrateur de ce site vous n'avez pas accès à la ressource que vous avez demandez");
    }

    public function testEditUserWithRoleADMIN()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail(SELF::EMAIL_ADMIN);
        $this->client->loginUser($testUser);

        $userEdit = $userRepository->findOneBy(['email' => SELF::EMAIL]);
        $userEdit_id = $userEdit->getId();


        $this->client->followRedirects();

        $crawler = $this->client->request('GET', "/users/$userEdit_id/edit");
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[password][first]'] = 'nico44';
        $form['user[password][second]'] = 'nico44';

        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('.alert.alert-success', "Superbe ! L'utilisateur a bien été modifié");
    }
}
