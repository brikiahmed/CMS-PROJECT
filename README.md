# Projet gestion de stock avec Symfony
## Autres outils: AJAX(pour le non rechargement des pages et la recuperation de données) ,ChartJS(Pour les graphiques)
<br><br>
Pour executer le projet suivez les etapes suivantes:
<br><br>
1)Installer les dependances avec la commmande  `composer install`<br>
2) Creer la base de donnees avec la commande `php bin/console doctrine:database:create`<br>
3)Puis faites les migrations  `php bin/console make:migration`<br>
4)Executez les migrations  `php bin/console doctrine:migrations:migrate`<br>
5)Enfin executer la commande `php -S localhost:8000 -t public/` pour demarrer le serveur(l'application)<br><br>
**NB: Nous n'avons pas utilisé de fixtures ni la librarie Faker les donnees generees proviennent d'un generateur dont le site est https://www.mockaroo.com/** 
