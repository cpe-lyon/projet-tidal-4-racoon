<?php
namespace Helper\MVC\Controller;

use Helper\App\DB\DB;
use Helper\MVC\Model\Keywords;
use Helper\Twig\Page;

class KeywordsController extends Controller
{
    private array|false $keywords = [];

    public function __construct()
    {
        parent::__construct();
        $context = new DB();
        $this->keywords = $context->get(Keywords::class);
    }


    public function getAll(): array
    {
        $context = new DB();
        return $context->get(Keywords::class);
    }

    private function mapKeywords(Keywords $keyword) {
        return $keyword->name;
    }

    public function filterKeyword($request): array
    {
        $keyword = $request->get('filter', null);
        $keywords = array_map([$this, 'mapKeywords'], $this->keywords);
        $result = [];
        foreach ($keywords as $k => $item) {
            if (preg_match('~' . $keyword . '~', $item)) {
                $result[] = $item;
            }
        }
        return $result;
    }
}