<?php

namespace Helper\MVC;

use Helper\Twig\Page;

class HomeController extends Controller
{
    public function index(): Page
    {
        $this->params = [
            'title' => 'Home',
            'name' => 'John Doe',
            'items' => [
                'item1',
                'item2',
                'item3',
            ],
        ];
        return new Page('home.tpl.twig', $this->params);
    }

    public function account(array $params): Page
    {
        $id = $this->urlInput($params, 0);
        $age = $this->urlInput($params, 1);

        $this->params = [
            'title' => 'Account',
            'name' => 'John Doe',
            'id' => $id,
            'age' => $age,
        ];
        return new Page('account.tpl.twig', $this->params);
    }
}