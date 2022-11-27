# Twig

## Somaire

- [Utilisation de Twig dans le Controller](#utilisation-de-twig-dans-le-controller)
- [Utilisation de Twig dans le Template](#utilisation-de-twig-dans-le-template)

## Utilisation de twig dans le controller

Comme vous pouvez le voir dans l'exemple précédent, on utilise
la méthode `render` pour renvoyer une réponse au navigateur.
Cette méthode prend en paramètre le chemin vers le fichier twig
à utiliser et un tableau associatif contenant les variables
à utiliser dans le fichier twig.

```php
namespace Helper\MVC\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $path = 'home/index.html.twig';
        $variables = ['color' => 'red'];
        return $this->render($path, $variables);
    }
}
```

## Utilisation de twig dans le template

On peut utiliser les variables définies dans le controller
dans le template twig en les utilisant entre double accolades.

```HTML
<h1 style="color: {{ color }}">Hello world</h1>
```

Pour un tableau associatif, on peut utiliser la syntaxe suivante
pour accéder à une clé.

```HTML
<h1 style="color: {{ color.key }}">Hello world</h1>
```

Pour un tableau à parcourir, comme dans une liste, on peut utiliser
la syntaxe suivante.

```HTML
<ul>
    {% for item in items %}
        <li>{{ item }}</li>
    {% endfor %}
</ul>
```

Pour afficher un contenu conditionnellement, on peut utiliser la
syntaxe suivante.

```HTML
{% if condition %}
    <p>Condition is true</p>
{% else %}
    <p>Condition is false</p>
{% endif %}
```

Pour inclure un fichier twig, on peut utiliser la syntaxe suivante.

```HTML
{% include 'path/to/file.html.twig' %}
```

Le reste de la doc peut être trouvée sur le site officiel de twig.
[https://twig.symfony.com/doc/2.x/](https://twig.symfony.com/doc/2.x/)