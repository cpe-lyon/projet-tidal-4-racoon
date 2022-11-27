# Database

## Sommaire

- [Installation](#installation)
- [Usage](#usage)
  - [Récupérer des données](#récupérer-des-données)
    - [Conditions](#conditions)
  - [Insérer des données](#insérer-des-données)
  - [Modifier des données](#modifier-des-données)
  - [Requête personnalisée](#requete-personnalisée)

## Installation

```bash
# TODO
```

## Usage

Pour utiliser la base de données, il faut d'abord
créer une instance de la classe `DB` :

```php
$db = new DB();
```

Ensuite on peut utiliser les méthodes de la classe
`DB` pour effectuer des requêtes SQL.

### Récupérer des données

Pour récupérer toute les données d'une table, on utilise
le Model associé à cette table :

```php
$users = $db->getAll(User::class);
```

ici on récupère toutes les données de la table `user`

Pour récupérer une seule donnée, on utilise la méthode
`getItem` avec les conditions souhaitées :

```php
$user = $db->getItem(User::class);
```

Ici on récupère le premier utilisateur de la table `user`

#### Conditions
Il est possible de spécifier des conditions pour
récupérer les données souhaitées :

```php
$conditions = [new Condition("id", 5)];
$user = $db->getItem(User::class, $conditions);
```

Ici on récupère l'utilisateur dont l'id est égal à 5

Il est possible de spécifier plusieurs conditions :

```php
$conditions = [
    new Condition("id", 5),
    new Condition("name", "John")
];
$user = $db->getItem(User::class, $conditions);
```

Ici on récupère l'utilisateur dont l'id est égal à 5
et dont le nom est égal à "John"

Il est aussi de possible de spécifier des conditions
de comparaison :

```php
$conditions = [
    new Condition("name", "Marie", "!="),
    new Condition("age", "5", "=")
];
$user = $db->getAll(User::class, $conditions);
```

Ici on récupère tous les utilisateurs dont le nom
est différent de "Marie" et dont l'age est égal à 5

### Insérer des données

Pour insérer des données dans une table, on utilise
la méthode `insert` :

```php
$user = new User();
$user->setName("John");
$user->setAge(25);
$db->insert($user);
```

### Pivot/Jointure

Pour faire un pivot entre deux tables, il faut utiliser
la méthode `getAllJoin` :

```php
$users = $db->getAllJoin(User::class, UserToRole::class, Role::class, $conditions);
```

Ici un utilisateur peut avoir plusieurs rôles, et un
rôle peut être attribué à plusieurs utilisateurs.

La table `user_to_role` est donc une table pivot qui 
permet de faire le lien entre les tables `user` et
`role`, elle sert de pivot.

### Modifier des données

Pour changer des données dans une table, on utilise
la méthode `update` :

```php
$conditions = [
    new Condition("id", 5),
];
$user = $db->getItem(User::class, $conditions);
$user->setName("John");
$user->setAge(25);
$db->update($user, 5);
```

### Requete personnalisée

Pour faire une requête personnalisée, on utilise
la méthode `rawQuery` :

```php
$users = $db->rawQuery("SELECT * FROM user");
```