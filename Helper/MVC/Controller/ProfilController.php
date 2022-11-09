<?php

namespace Helper\MVC\Controller;

use Helper\Twig\Page;

class ProfilController extends Controller
{
    public bool $isConnected = true;
    public bool $profileEditing = true;
    public bool $passwordEditing = false;

    public function profile(): Page
    {
        $params = [
            'isConnected' => $this->isConnected,
            'profileEditing' => $this->profileEditing,
            'passwordEditing' => $this->passwordEditing
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
