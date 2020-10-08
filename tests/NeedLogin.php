<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait NeedLogin
{   
    /**
    * permet de simuler un user connecter
    * On recupére un user depuis le fichier user.yaml 
    * On recupére la session et on crée un token
    * On inject le token dans la session et on save
    */
    public function login(KernelBrowser $client, User $user)
    {
        $session = $client->getContainer()->get('session');
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();

        /**
         * On crée un cookie pour l'associer au client
         * On le stock dans la boite a cookie avec getCookieJar();
         */
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}