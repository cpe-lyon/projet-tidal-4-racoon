<?php

namespace Helper\MVC;

use Helper\Twig\Page;

class ProfilController extends Controller
{
    public bool $isConnected = false;

    // À l'arrivée sur cette page, check si un user est connecté
    // -> Si oui : Page de profil
    // -> Si non : Page du choix de la connexion
    public function profil(): Page
    {
        if (!empty($_POST)) {
            $this->checkFormSubmission();
        }

        $params = [
            'isConnected' => $this->isConnected
        ];

        return new Page('profil/profil.tpl.twig', $params);
    }

    protected function checkFormSubmission(): void
    {
        // Traitement connexion

    }
}
