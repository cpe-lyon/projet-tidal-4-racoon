<?php

namespace Helper\MVC\Controller;

use Helper\App\DB;
use Helper\MVC\Model\KeySympt;
use Helper\MVC\Model\Keywords;
use Helper\MVC\Model\Symptome;
use Helper\Twig\Page;

class TestController extends Controller
{
    public function index(): Page
    {
        $Context = new DB();


        //PLEIN D'EXEMPLES A LA CON



        $query = $Context->getAllJoin(Keywords::class, Symptome::class, KeySympt::class);
//        var_dump($query);
//        $request = $Context->getAll(Meridien::class);

//         $m = new Meridien();
//         $m->code = "A";
//         $m->nom = "aaa";
//         $m->element = "b";
//         $m->yin = true;
//        $a = $Context->insert($m);
//        var_dump($a);

//         $request = $Context->getAll(Meridien::class);
//         var_dump(end($request));

//         $m->element = "a";
//         $a = $Context->update($m);
//         var_dump($a);

//         $request = $Context->getAll(Meridien::class);
//         var_dump(end($request));

//         $request = $Context->getAll(SymptPatho::class);
//         var_dump(end($request));

//         $s = new SymptPatho();
//         $s->ids = 448;
//         $s->idp = 113;
//         $s->aggr = false;

//         $a = $Context->update($s, array("ids", "idp"));
//         var_dump($a);

//         $request = $Context->getAll(SymptPatho::class);
//         var_dump(end($request));


//
//        $c = [new Condition("idk", 5)];
//        $query = $Context->getItem("keywords", $c);
//        var_dump($query);
//
//        $c = [new Condition("element", "'f'"), new Condition("yin", "TRUE")]; //on créé une liste de filtres avec new Condition(clé, valeur) ou new Condition(clé, valeur, operateur)
//        $c = [new Condition("element", "f"), new Condition("yin", true)];
//        $query = $Context->getItem("meridien", $c); //on fait ensuite la requete dans la table meridian avec la liste de conditions
//        var_dump($query);
//
//        $request = $Context->getAll("keywords");
//        var_dump($request[5]);
//
//        $request = $Context->getAll(Meridien::class);
//        var_dump($request);
//

        return new Page('test.tpl.twig', $this->params);
    }
}