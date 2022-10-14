# projet-tidal-4-racoon



<img src="https://images.alphacoders.com/596/thumb-1920-596047.jpg" alt="Bonjour" width="200"/>


## Installation

```bash
composer install
```

```bash
bash ./init.sh
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

## Author
- [@Racoon](https://github.com/cpe-lyon/projet-tidal-4-racoon)