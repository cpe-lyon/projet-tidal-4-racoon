<?php

namespace Helper\MVC\Controller;

use Helper\App\Menu\MenuHelper;

class MenuController extends Controller
{
    public function account(): array
    {
        return MenuHelper::account();
    }
}