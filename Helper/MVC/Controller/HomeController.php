<?php

namespace Helper\MVC\Controller;

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
}