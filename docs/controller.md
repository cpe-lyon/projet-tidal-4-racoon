# Controller

Les controllers sont utilisé pour recptionner des requetes de l'utilisateur et renvoyer une réponse approprié.

## Somaire

- [Création d'un controller](#cration-dun-controller)
- [Utilisation de paramètres](#utilisation-de-paramtres)
- [Paramètre de la route](#paramtres-de-route)

## Création d'un controller

Un controller doit suivre un certain modèle pour
fonctionner correctement. Il doit hériter de la
classe `Controller` et se trouver dans le dossier
`Helper/MVC/Controller`. 

Il doit avoir le nom de la classe qui hérite de
`Controller` suivi de `Controller`. 

Par exemple, si on veut créer un controller pour la
page d'accueil, on peut créer un fichier 
`Helper/MVC/Controller/HomeController.php` et y écrire
le code suivant :

```php
namespace Helper\MVC\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->render('home/index.html.twig');
    }
}
```

Ici notre exemple est très simple, il n'y a pas de paramètre.

## Utilisation de paramètres

Si on veut utiliser des paramètres dans notre controller,
on peut les récupérer dans la méthode `index` et attendre 
un parametre dans l'url tel que `/?color=red` de la manière
suivante :

```php
namespace Helper\MVC\Controller;

use Helper\App\Routes\Request;class HomeController extends Controller
{
    public function index(Request $request)
    {
        $color = $request->get('color');
        return $this->render(
            'home/index.html.twig',
            ['color' => $color]
        );
    }
}
```

Ici, le paramètre `color` est récupéré dans l'url, c'est à 
dire dans la requete utilisateur. On utilise donc l'objet 
`Request` pour récupérer ce paramètre.

L'objet `Request` est un objet qui contient toutes les paramètres
de la requete utilisateur. Il contient aussi des méthodes pour
récupérer les paramètre POST d'un formulaire, les paramètres
FILES pour les fichiers envoyés par l'utilisateur, etc.

```php

public function index(Request $request)
{
    if($request->has('color')) {
        $color = $request->get('color');
    }
    $file = $request->file('file');
    $post = $request->post('post', 'default');
    $cookie = $request->cookie('cookie');
    $session = $request->session('session');
    return $this->render(
        'home/index.html.twig',
        ['color' => $color]
    );
}
```

## Paramètres de route

On peut aussi récupérer des paramètres de route dans notre
controller. Par exemple, si on veut récupérer l'id d'un
utilisateur dans l'url, on peut utiliser la syntaxe suivante

```php
namespace Helper\MVC\Controller;

use Helper\App\Routes\Request;class HomeController extends Controller
{
    public function index(Request $request, $id)
    {
        return $this->render(
            'home/index.html.twig',
            ['id' => $id]
        );
    }
}
```

Ici, le paramètre `$id` est récupéré dans l'url, Les paramètres
de route sont récupérés dans l'ordre dans lequel ils sont
définis dans le fichier `routes.php`.
