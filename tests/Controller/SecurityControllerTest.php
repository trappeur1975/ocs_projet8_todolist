<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testDisplayLoginPage()
    {
        $this->client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
    }

    public function testDisplayLoginForm(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testAuthentificationSuccess()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'username' => 'nico',
            'password' => 'nico'
        ]);
        $this->client->submit($form);

        // $this->assertTrue($this->client->getResponse()->isRedirection());

        // $this->assertResponseRedirects('/');
        // $this->client->followRedirect();

        $this->assertSelectorExists('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");

        // echo $client->getResponse()->getContent();
    }

    public function testAuthentificationWithInvalidCredential()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'username' => 'nico',
            'password' => 'falsePassword'
        ]);
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirection());

        $this->assertResponseRedirects('/login');

        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-danger', 'Invalid credentials');
    }

    public function testLogout()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('titi@hotmail.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/logout');

        $this->client->followRedirect();

        $this->assertResponseRedirects("/login", Response::HTTP_FOUND);

        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        // $this->assertResponseIsSuccessful()
        // $this->assertResponseRedirects("/", Response::HTTP_OK);

    }

    public function testLogoutWitButton()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('titi@hotmail.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);

        $crawler = $this->client->request('GET', '/');

        // $this->client->request('GET', '/logout');
        $link = $crawler->selectLink('Se déconnecter')->link();
        $crawler = $this->client->click($link);

        $this->client->followRedirect();

        $this->assertResponseRedirects("/login", Response::HTTP_FOUND);

        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
    }
}
