<?php

namespace Helper\MVC;

use Helper\App\DB;
use Helper\Models\Condition;
use Helper\Models\KeySympt;
use Helper\Models\Keywords;
use Helper\Models\Meridien;
use Helper\Models\Symptome;

use Helper\Twig\Page;

class TestController extends Controller
{
    public function index(): Page
    {
        $Context = new DB();

//        $❤️ = $Context->getAllJoin(Keywords::class, Symptome::class, KeySympt::class);
//        var_dump($❤️[1]);
//        $m = new Meridien();
//        $m->nom = "aaa";
//        $m->element = "b";
//        $m->yin = true;
//        $a = $Context->insert($m);
//
//        $c = [new Condition("idk", 5)];
//        $🤣 = $Context->getItem("keywords", $c);
//        var_dump($🤣);
//
////$c = [new Condition("element", "'f'"), new Condition("yin", "TRUE")]; //on créé une liste de filtres avec new Condition(clé, valeur) ou new Condition(clé, valeur, operateur)
//        $c = [new Condition("element", "f"), new Condition("yin", true)];
//        $🤣 = $Context->getItem("meridien", $c); //on fait ensuite la requete dans la table meridian avec la liste de conditions
//        var_dump($🤣);
//
//        $🤔 = $Context->getAll("keywords");
//        var_dump($🤔[5]);
//
//        $🤔 = $Context->getAll(Meridien::class);
//        var_dump($🤔);
//

        return new Page('test.tpl.twig', $this->params);
    }
}