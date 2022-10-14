<?php

namespace Helper\MVC;

use Helper\Twig\Page;

class HomeController extends Controller
{
    public function index(): Page
    {
        $this->params = [];
        return new Page('home.tpl.twig', $this->params);
    }

    public function account($id = 0, $name = 0){
        $this->params = [
            'title' => 'Account',
            'name' => $name,
            'age' => $id,
        ];
        return new Page('home.tpl.twig', $this->params);
    }
}