<?php

namespace Helper\MVC;

use Helper\App\Menu\MenuHelper;

class MenuController extends Controller
{
    public function account(): array
    {
        return MenuHelper::account();
    }
}