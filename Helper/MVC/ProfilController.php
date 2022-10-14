<?php

namespace Helper\MVC;

use Helper\Twig\Page;

class ProfilController extends Controller
{
    public bool $isConnected = false;

    public function profile(): Page
    {
        $params = [
            'isConnected' => $this->isConnected
        ];

        return new Page('profil/profil.tpl.twig', $params);
    }

    public function register(): Page
    {
        if (!empty($_POST)) {
            $this->checkRegisterForm();
        }

        return new Page('profil/register.tpl.twig');
    }

    public function login(): Page
    {
        if (!empty($_POST)) {
            $this->checkLoginForm();
        }

        return new Page('profil/login.tpl.twig');
    }

    protected function checkRegisterForm(): void
    {
        // Traitement inscription
    }

    protected function checkLoginForm(): void
    {
        // Traitement connexion
    }
}
