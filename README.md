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
Postgres a besoin d'être lancé sur l'ordinateur ou une machine distante

Utiliser cette commande pour lancer un serveur PHP local

```bash
php -S localhost:8000 -t public
```

## Création de la table Users 
Requis pour utiliser les fonctionnalités liées au profil utilisateur.
À exécuter dans PostrgeSQL : 
```
CREATE SEQUENCE users_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."users" (
    "id" integer DEFAULT nextval('users_id_seq') NOT NULL,
    "username" character varying(50) NOT NULL,
    "name" character varying(50) NOT NULL,
    "lastname" character varying(50) NOT NULL,
    "mail" character varying(100) NOT NULL,
    "password" character varying(60) NOT NULL,
    "creationdate" date DEFAULT CURRENT_DATE NOT NULL,
    "confirmationtoken" character varying(60),
    "confirmationdate" date,
    CONSTRAINT "users_mail_key" UNIQUE ("mail"),
    CONSTRAINT "users_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "users_username_key" UNIQUE ("username")
) WITH (oids = false);

```

## Utilisation de Sass  

Sur VSCode utiliser l'extension __Watch Sass__, qui permet de compiler nos fichiers .scss en __un seul fichier main.css__ sur lequel notre HTML pointe.  
Il ne faut __jamais modifier les fichiers main.map.css et main.css__.  
À chaque création d'un fichier .scss, penser à __l'importer__ dans le fichier main.scss pour qu'il soit compris dans la compilation.  
Voici un guide simple d'utilisation de sass : https://sass-lang.com/guide. Vous pouvez le consulter pour découvrir quelles fonctionnalités ce préprocesseur offre, ou simplement continuer d'écrire en CSS basique.   

## Author
- [@Racoon](https://github.com/cpe-lyon/projet-tidal-4-racoon)

## Documentation

- [Controller](docs/controller.md)
- [Twig](docs/twig.md)
- [Routing](docs/routing.md)
- [Database](docs/database.md)
