# Routing

## Sommaire

- [Routes](#routes)
- [Les différentes méthodes](#les-différentes-méthodes)
  - [GET](#get)
  - [POST](#post)
  - [PUT](#put)
  - [DELETE](#delete)
- [Les paramètres](#paramtres)
- [Groupes de routes](#groupes-de-routes)
- [Les fichiers de routes](#les-fichiers)

## Routes

Une route est une association entre une URL et une
action à effectuer.

Par exemple, la route suivante permet d'associer l'URL
`/` à l'action `home` du contrôleur `MainController` :

```php
$router->get('/', 'MainController', 'home');
```

## Les différentes méthodes

4 méthodes sont disponibles pour définir une route,
celles-ci suivent la logique de CRUD :

### get

Pour récupérer des données.

```php
$router->get($path, $controller, $action);
```

### post

Pour créer des données.

```php
$router->post($path, $controller, $action);
```

### put

Pour mettre à jour des données.

```php
$router->put($path, $controller, $action);
```

### delete

Pour supprimer des données.

```php
$router->delete($path, $controller, $action);
```

## Paramètres

Il est possible d'ajouter des paramètres à une route :

```php
$router->get('/user/@@', 'UserController', 'show');
```

Ici, le paramètre `@@` sera remplacé par une valeur
dynamique et reutilisé dans le controller.

## Groupes de routes

Il est possible de regrouper des routes en utilisant
des groupes :

```php
$router->group('/admin', function (Router $router) {
    $router->get('/user', 'UserController', 'list');
    $router->get('/user/@@', 'UserController', 'show');
});
```

Ici toutes les routes commenceront par `/admin`.
Et donc la combinaison `/admin/user` sera associée
à l'action `list` du contrôleur `UserController`.

## Les fichiers

Les routes sont définies dans deux fichiers 
`routes/routes.php` et `routes/api.php`.

Le fichier `routes/routes.php` contient les routes
utilisées par l'application web.
Notamment les routes utilisées pour l'affichage des
pages.

Le fichier `routes/api.php` contient les routes
utilisées comme API.
Ces routes sont utilisées pour récupérer des données
en JSON.