<?php

namespace App\Test\Controller;

use App\Tests\NeedLogin;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class PageControllerTest extends WebTestCase
{
    use FixturesTrait;
    use NeedLogin;
    /**
     * Test Controller avec la method static createClient()
     * et verification de la réponse HTTP avec assertResponseStatusCodeSame()
     */
    public function testHelloPage()
    {
        $client = static::createClient();
        $client->request('GET', '/hello');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * Test de l'existance d'elements HTML sur le page
     * avec assertSelectorTextContains()
     */
    public function testH1HelloPage()
    {
        $client = static::createClient();
        $client->request('GET', '/hello');
        $this->assertSelectorTextContains('h1', 'Bienvenue sur le site');
    }

    public function testRedirectToLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/auth');
        $this->assertResponseRedirects('/login');
    }

    public function testAuthenticatedUserAccessAuth()
    {
        $users = $this->loadFixtureFiles([__DIR__ . "/users.yaml"]);
        $client = static::createClient();
        $this->login($client, $users['user_user']);
        $client->request('GET', '/auth');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAdminRequireAdminRole()
    {
        $users = $this->loadFixtureFiles([__DIR__ . "/users.yaml"]);
        $client = static::createClient();
        $this->login($client, $users['user_user']);
        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminHasAdminRole()
    {
        $users = $this->loadFixtureFiles([__DIR__ . "/users.yaml"]);
        $client = static::createClient();
        $this->login($client, $users['user_admin']);
        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}