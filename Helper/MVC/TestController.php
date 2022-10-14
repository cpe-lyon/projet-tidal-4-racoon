<?php

namespace Helper\MVC;

use Helper\App\DB;
use Helper\Models\Condition;
use Helper\Models\KeySympt;
use Helper\Models\Keywords;
use Helper\Models\Meridien;
use Helper\Models\Symptome;
use Helper\Models\SymptPatho;

use Helper\Twig\Page;

class TestController extends Controller
{
    public function index(): Page
    {
        $Context = new DB();


        //PLEIN D'EXEMPLES A LA CON



//        $❤️ = $Context->getAllJoin(Keywords::class, Symptome::class, KeySympt::class);
//        var_dump($❤️[1]);
        //$🤔 = $Context->getAll(Meridien::class);
        //var_dump(end($🤔));

        // $m = new Meridien();
        // $m->code = "A";
        // $m->nom = "aaa";
        // $m->element = "b";
        // $m->yin = true;
        //$a = $Context->insert($m);
        //var_dump($a);

        // $🤔 = $Context->getAll(Meridien::class);
        // var_dump(end($🤔));

        // $m->element = "a";
        // $a = $Context->update($m);
        // var_dump($a);

        // $🤔 = $Context->getAll(Meridien::class);
        // var_dump(end($🤔));

        // $🤔 = $Context->getAll(SymptPatho::class);
        // var_dump(end($🤔));

        // $s = new SymptPatho();
        // $s->ids = 448;
        // $s->idp = 113;
        // $s->aggr = false;

        // $a = $Context->update($s, array("ids", "idp"));
        // var_dump($a);

        // $🤔 = $Context->getAll(SymptPatho::class);
        // var_dump(end($🤔));


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
    //    $🤔 = $Context->getAll(Meridien::class);
    //    var_dump($🤔);
//

        return new Page('test.tpl.twig', $this->params);
    }
}