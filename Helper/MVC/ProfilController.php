<?php

namespace Helper\MVC;

use Helper\Twig\Page;

class ProfilController extends Controller
{
    public bool $isConnected = false;

    // À l'arrivée sur cette page, check si un user est connecté
    // -> Si oui : Page de profil
    // -> Si non : Page du choix de la connexion
    public function profile(): Page
    {
        if (!empty($_POST)) {
            $this->checkFormSubmission();
        }

        $params = [
            'isConnected' => $this->isConnected
        ];

        return new Page('profil/profil.tpl.twig', $params);
    }

    public function register(): Page
    {
        return new Page('profil/register.tpl.twig');
    }

    public function login(): Page
    {
        return new Page('profil/login.tpl.twig');
    }

    protected function checkFormSubmission(): void
    {
        // Traitement connexion
    }


    protected function redirectTo(string $url): void
    {
        $this->isConnected = true;
    }
}
