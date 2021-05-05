# bank_app
Lien de l'application : https://ecf-bamba-bank.herokuapp.com/

Pour plus d'information sur l'élaboration du projet veuillez suivre le lien Trello :
https://trello.com/bamba86/home

Vous y trouverez l'élaboration du maquettage de la BDD (diagramme de classe + Use case) mais également des maquettes graphiques (zoning + charte graphique).

Environnement :
Pour le Front j’ai choisi : Twig, css, javascript, bootstrap.
Pour le Back j’ai choisi : Php 7.4 avec le framwork Symfony 5.
Pour la Base de données j’ai choisi : Mysql + Doctrine.
Mon IDE : Visual Studio Code.
SERVER : MAMP.
Logiciel d’exploitation : MAC OS Big Sur.
Mise en ligne avec heroku.


Pour vous authentifier dans l'accès professionnel :
username : conseiller1
mdp : conseiller1

Deploiement en prod via Heroku :
  1. Créer un compte sur https://www.heroku.com/
  2. Installez le client Heroku
  3. Depuis votre ligne de commande utiliser le commande : heroku login
  4. Depuis le dossier de l'app, lancez la commande : git init -> git add -A -> git commit git commit -m "initial import"
  5. Ajouter le procfile avec la ligne de commande : echo 'web: heroku-php-apache2 public/' > Procfile 
  6. Ajouter le via les commandes : git add Procfile -> git commit -m "Heroku Procfile"
  7. Configurer l'environnement prod : heroku config:set APP_ENV=prod
  8. Envoyer l'app vers Heroku : git push heroku master

Afin que l'application fonctionne, veuillez vérifier vos variables d'environnement pour convenir à votre base de données.


