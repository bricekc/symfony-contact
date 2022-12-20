# Symfony-contacts
## KUCA Brice
## utilisation de composer pendant ce projet :
composer about

composer self-update

composer require friendsofphp/php-cs-fixer --dev

pour lancer le serveur : composer start

pour lancer la commande de vérification du code par PHP CS Fixer : composer test:cs

pour lancer la commande de correction du code par PHP CS Fixer : composer fix:cs

pour pouvoir afficher les filtres :  composer require twig/intl-extra

pour lancer les tests:csfixer et test:codeception : composer test

pour installer le bunde orm-fixtures : composer require --dev orm-fixtures

pour lance la destruction forcée de la base de données,
créer la base de données,
applique les migrations successives 
et génère les données factices sans questions interactives : composer db

destruction siliencieuse forcé de la base de données, création silencieuse de la base de données, création silencieuse du schéma de la bd et execution des tests codeception :composer test:codeception

pour se connecter en Role_Admin : root@example.com:test

pour se connecter en Role_User : user@example.com:test