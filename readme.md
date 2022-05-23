Au préalable vous aurez besoin d'un environnement comprenant MYSQL ou MARIADB ET PHP >=8.0.2

Afin de récupérer le projet je vous invite à réaliser la commande suivante :

``` BASH
git clone https://github.com/NexXry/Little-Symfony.git

```

Suite à cela in nous faut installer les dépendences du projet.

``` BASH

composer install // pour symfony

npm install // pour webpack

npm run build // charger les ASSETS

```

Une fois cela fait il vous faut configurer le .env au niveau Base de données :

``` BASH

exemple : 
DATABASE_URL="mysql://NomBASE:MotDePasse@127.0.0.1:3306(3007 SI mariaDB)/hello?serverVersion=10.5.15-MariaDB-1:10.5.15+maria~focal &charset=utf8mb4" (La version de la BDD peut varier ajouter la votre.)


```
Nous pouvons maintenant créer la base de données ainsi que les données de test .

``` BASH
// Si jamais php bin/console doctrine:database:drop --force permet la suppresion de la base en cas de rater

Pour créer la base : php bin/console doctrine:database:create

Pour créer les tables : php bin/console doctrine:schema:create

Pour ajouter les données : php bin/console doctrine:fixtures:load ( Careful, database "hello" will be purged. Do you want to continue? (yes/no) [no]:
 > Press yes) 

```