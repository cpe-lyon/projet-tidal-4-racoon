<?php

namespace Helper\MVC\Controller;

use Helper\MVC\Model\Condition;
use Helper\MVC\Model\Patho;
use Helper\MVC\Model\SymptPatho;
use Helper\App\DB;
use Helper\MVC\Model\KeySympt;
use Helper\MVC\Model\Keywords;
use Helper\MVC\Model\Symptome;
use Helper\Twig\Page;

class HomeController extends Controller
{
    public function index(): Page
    {
        $this->params = [];
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
            if($filter->content == 'symptome') {
                $value .= $filter->filter . '|';
            } else {
                // Ajoute directement la condition pour la bonne table
                $conditions[] = new Condition($filter->content, "%" . $filter->filter . "%", 'LIKE');
            }
        }
        // Des keywords pour les symptomes sont présents, on va récupérer les ids des symptomes
        $symptomes = [];
        if($value != ''){
            // Opérateur pour effectuer plusieurs LIKE sur plusieurs valeurs
            $op = "~*";
            $condition = new Condition('name', substr_replace($value, "", -1), $op);
            $symptomes = $context->getAllJoin(Symptome::class, Keywords::class, KeySympt::class, [$condition]);
        }

        // Récupérations des ids des symptomes
        if(sizeof($symptomes) > 0) {
            $idsArray = '(';
            foreach ($symptomes as $symptome) {
                $idsArray .= $symptome->ids . ', ';
            }
            $idsArray = substr($idsArray, 0, -2) . ')';
            $op = "IN";
            $conditions[] = new Condition('Symptome.ids', $idsArray, $op, $idsArray);
        }
        return $context->getAllJoin(Patho::class, Symptome::class, SymptPatho::class, $conditions);
    }
}