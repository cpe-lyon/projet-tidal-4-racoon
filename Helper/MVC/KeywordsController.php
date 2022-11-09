<?php
namespace Helper\MVC;

use Helper\App\DB;
use Helper\Models\Condition;
use Helper\Models\Keywords;
use Helper\Twig\Page;

class KeywordsController extends Controller
{
    private array|false $keywords = [];

    public function __construct()
    {
        $context = new DB();
        $this->keywords = $context->getAll(Keywords::class);;
    }


    public function getAll(): array
    {
        $context = new DB();
        return $context->getAll(Keywords::class);
    }

    public function filterKeyword($keyword) {
        return preg_grep('~' . $keyword . '~', $this->keywords->name);
    }
}