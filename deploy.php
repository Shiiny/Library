<?php

/* On inclut la recette pour une application Symfony 3. 
 * Si vous êtes encore sur Symfony 2, il faut inclure la recette recipe/symfony.php */
require 'recipe/symfony3.php';

/* Dans notre exemple, on a qu'un serveur. On le nomme localhost car on déploie directement sur ce serveur.
 * Ce qui explique l’utilisation de la fonction localServer() sinon il faut utiliser la fonction server().
 * On le met dans la catégorie "prod" car il s'agit d'un serveur de production. 
 * On indique le répertoire où doit être déployé le projet. */
localServer('localhost')
    ->stage('prod')
    ->env('deploy_path', 'sevigne.web-shiny.fr');

/* On utilise Git pour récupérer le projet : on indique l'URL du dépôt du projet */
set('repository', 'git@github.com:Shiiny/Library.git');

/* Ce qui vient par la suite est optionnel. Il existe plein de variables personnalisables (Cf documentation). 
 * Ici, cela permet de demander à Deployer de ne pas utiliser la commande sudo pour changer les droits des fichiers. 
 * C'est utile par exemple quand cette commande n'est pas disponible sur le serveur */
set('writable_use_sudo', false);

/* A chaque déploiement, l'ancienne version est archivée. 
 * Cette variable permet d'indiquer le nombre maximum de version que l'on souhaite conserver. */
set('keep_releases', 5);