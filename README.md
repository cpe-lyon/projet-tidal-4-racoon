# projet-tidal-4-racoon



<img src="https://images.alphacoders.com/596/thumb-1920-596047.jpg" alt="Bonjour" width="200"/>


## Installation

```bash
composer install
```

```bash
./init.sh
```

## launch
You need to have a postgresql database running on your computer

Use this command to launch php server
```bash
php -S localhost:8000 -t public
```


## Utilisation de Sass  

Sur VSCode utiliser l'extension __Watch Sass__, qui permet de compiler nos fichiers .scss en __un seul fichier main.css__ sur lequel notre HTML pointe.  
Il ne faut __jamais modifier les fichiers main.map.css et main.css__.  
À chaque création d'un fichier .scss, penser à __l'importer__ dans le fichier main.scss pour qu'il soit compris dans la compilation.  
Voici un guide simple d'utilisation de sass : https://sass-lang.com/guide. Vous pouvez le consulter pour découvrir quelles fonctionnalités ce préprocesseur offre, ou simplement continuer d'écrire en CSS basique.   


## Database Context

Systeme inspiré de .NET et Entity Framework.  
Il s'agit d'une classe wrapper autour de la base de donnés en place.  
Il permet avec une simple ligne `$Context = new DB();` de créer une connexion a la base de donnée et de facilement faire des requetes sans ecrire la requete manuellement.  
Le systeme est flexible et peut gérer les modeles de la BDD en passant le nom ou directement le type :
```php
        $c = [new Condition("idk", 5)]; //Condition ou le champ 'idk' est égal a 5
        $query = $Context->getItem("keywords", $c); //récupération des données

        $c = [new Condition("element", "'f'", "like"), new Condition("yin", true)]; //on créé une liste de filtres avec new Condition(clé, valeur) ou new Condition(clé, valeur, operateur)
        $query = $Context->getItem("meridien", $c); //on fait ensuite la requete dans la table meridian avec la liste de conditions

        $request = $Context->getAll(SymptPatho::class); //On peut aussi passer la classe en parametre

```

Ainsi le systeme peut s'adapter facilement aux evolutiond de la BDD en ajoutant ou modifiant les modeles correspondants a la BDD. 

Une documentation détaillée sur chacune des fonctions est disponible dans l'IDE en passant la souris sur une fonction.


## Authors
- [@Racoon](https://github.com/cpe-lyon/projet-tidal-4-racoon)
- [@Youreastonefox (Luisa Chaduc)](https://github.com/Youreastonefox)
- [@ValkyrieHD (Martin Jourjon)](https://github.com/ValkyrieHD42)
- [@Foxlider (Alexis Lonchambon)](https://github.com/Foxlider)
- [@R3C-0N (Mathis Medard)](https://github.com/R3C-0N)