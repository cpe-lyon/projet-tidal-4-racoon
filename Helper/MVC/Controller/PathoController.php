<?php

namespace Helper\MVC\Controller;

use Helper\App\DB\AndCondition;
use Helper\App\DB\DB;
use Helper\App\Routes\Request;
use Helper\MVC\Model\Patho;
use Helper\Twig\Page;
use Helper\Twig\Twig;

class PathoController extends Controller
{
    public function get(Request $request, int $id): array
    {
        $context = new DB();
        $query = '
        SELECT patho.idp as "patho_idp", patho.mer as "patho_mer", 
               patho.type as "patho_type", patho."desc" as "patho_desc",
               sy.ids as "symptome_ids", sy."desc" as "symptome_desc", 
               kw.idk as "keyword_idk", kw.name as "keyword_name"
        FROM patho
          INNER JOIN symptpatho s on patho.idp = s.idp
          INNER JOIN symptome sy on s.ids = sy.ids
          INNER JOIN keysympt k on s.ids = k.ids
          INNER JOIN keywords kw on k.idk = kw.idk
        WHERE patho.idp = :idp;';
        $params = [':idp' => $id];

        $result = $context->query($query, $params);
        $patho = [
            'idp' => $result[0]['patho_idp'],
            'mer' => $result[0]['patho_mer'],
            'type' => $result[0]['patho_type'],
            'desc' => $result[0]['patho_desc'],
            'symptomes' => []
        ];
        foreach ($result as $line) {
            $patho['symptomes'][$line['symptome_ids']] = [
                'ids' => $line['symptome_ids'],
                'desc' => $line['symptome_desc'],
                'keywords' => []
            ];
            $patho['symptomes'][$line['symptome_ids']]['keywords'][$line['keyword_idk']] = [
                'idk' => $line['keyword_idk'],
                'name' => $line['keyword_name']
            ];
        }
        return $patho;
    }

    public function getInterfaces(): string
    {
        $page = new Page('pathology/pathology.tpl.twig');
        return $page->display();
    }
}