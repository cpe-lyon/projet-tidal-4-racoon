<?php

namespace Helper\MVC\Controller;

use Helper\App\DB\AndCondition;
use Helper\App\DB\DB;
use Helper\MVC\Model\Patho;
use Helper\MVC\Model\SymptPatho;
use Helper\MVC\Model\KeySympt;
use Helper\MVC\Model\Keywords;
use Helper\MVC\Model\Symptome;
use Helper\Twig\Page;
use Helper\MVC\Controller\ProfileController;

class HomeController extends Controller
{
    public function index(): Page
    {
        $pc = new ProfileController;
        $isLogged = $pc->isLogged();

        $this->params = ["isLogged" => $isLogged];
        return new Page('home.tpl.twig', $this->params);
    }
    public function about(): Page
    {
        $this->params = [];
        return new Page('about.tpl.twig', $this->params);
    }

    public function search($request): array
    {
        // Récupération de la liste des filtres
        $filterList = json_decode($request->post('filters', null));

        // Init
        $context = new DB();
        $value = '';
        $conditions = [];

        // Pour chaque symptome on le format dans une liste de string
        // On ajoute la condition unqiuement si des symptomes ont été trouvés
        foreach ($filterList as $filter) {
            if ($filter->content == 'symptome') {
                $value .= $filter->filter . '|';
            } else {
                // Ajoute directement la condition pour la bonne table
                $conditions[] = new AndCondition($filter->content, "%" . $filter->filter . "%", 'LIKE');
            }
        }
        // Des keywords pour les symptomes sont présents, on va récupérer les ids des symptomes
        $symptomes = [];
        if ($value != '') {
            // Opérateur pour effectuer plusieurs LIKE sur plusieurs valeurs
            $op = "~*";
            $condition = new AndCondition('name', substr_replace($value, "", -1), $op);
            $symptomes = $context->getJoin(Symptome::class, KeySympt::class, Keywords::class, [$condition]);
        }

        // Récupérations des ids des symptomes
        if (sizeof($symptomes) > 0) {
            $idsArray = '(';
            foreach ($symptomes as $symptome) {
                $idsArray .= $symptome->ids . ', ';
            }
            $idsArray = substr($idsArray, 0, -2) . ')';
            $op = "IN";
            $conditions[] = new AndCondition('Symptome.ids', $idsArray, $op, $idsArray);
        }
        return $context->getJoin(Patho::class, SymptPatho::class, Symptome::class, $conditions);
    }
}
