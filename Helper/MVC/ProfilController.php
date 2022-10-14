<?php

namespace Helper\MVC;

use Helper\Twig\Page;

class ProfilController extends Controller
{
    public bool $isConnected = true;
    public bool $profileEditing = false;
    public bool $passwordEditing = true;

    public function profile(): Page
    {
        $params = [
            'isConnected' => $this->isConnected,
            'profileEditing' => $this->profileEditing,
            'passwordEditing' => $this->passwordEditing,
            'handlePassordEdit' => $this->handlePassordEdit()
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

    protected function handleProfileEdit(): void
    {
        $this->profileEditing = $this->profileEditing ? false : true;
    }

    protected function handlePassordEdit(): void
    {
        $this->passwordEditing = $this->passwordEditing ? false : true;
    }
}
