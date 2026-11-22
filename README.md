# Commerce API — dépôt de départ

Projet Symfony 8.1 (PHP 8.4) et Doctrine pour les TP « API & Cybersécurité » de BTS SIO2 SLAM.
Les entités reprennent le modèle de la base **Boutique** du cours de bases de données.

## Contenu

| Entité | Table | Remarques |
|---|---|---|
| `Category` | `category` | |
| `Supplier` | `supplier` | |
| `Product` | `product` | + `purchasePrice` (prix d'achat, donnée interne, absente de Boutique) |
| `Client` | `client` | |
| `Employee` | `employee` | |
| `Order` | `orders` | `order` est un mot réservé en SQL |
| `OrderItem` | `order_item` | |

Le jeu d'essai (`src/DataFixtures/AppFixtures.php`) reprend les données de Boutique : sur une base neuve,
les identifiants sont les mêmes (14 produits, 7 catégories, 12 commandes…). Le prix d'achat est fictif.

Paquets installés : Doctrine ORM et migrations, Serializer ; en développement : fixtures et maker.
Le Validator s'installe pendant la séance 2 (`composer require validator`).

## Prérequis

- PHP 8.4 avec les extensions `pdo_pgsql`, `intl`, `mbstring`, `openssl`, `zip`, `fileinfo` (vérifier avec `php -m`).
- **Composer** : la commande `composer` doit fonctionner dans un terminal (installeur Windows : Composer-Setup.exe sur getcomposer.org).
- Un serveur **PostgreSQL** et un identifiant autorisé à créer une base.
- Git.
- Facultatif : la Symfony CLI. Sans elle, le serveur se lance avec `php -S 127.0.0.1:8000 -t public`.

## Distribuer le dépôt

Ne pas copier les dossiers `vendor/` et `var/` : `composer install` les reconstruit. Le `.gitignore` les exclut déjà
si le dépôt est partagé avec Git.

## Mise en route

1. Créer `.env.local` à la racine avec l'accès à votre serveur PostgreSQL. L'enregistrer en UTF-8 **sans BOM** :
   sinon Symfony refuse de le lire (« Loading files starting with a byte-order-mark (BOM) is not supported »).

   ```
   DATABASE_URL="postgresql://UTILISATEUR:MOT_DE_PASSE@127.0.0.1:5432/commerce?serverVersion=16&charset=utf8"
   ```

2. Installer et remplir la base :

   ```
   composer install
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate -n
   php bin/console doctrine:fixtures:load -n
   symfony serve -d          # ou : php -S 127.0.0.1:8000 -t public
   ```

3. Vérifier : `php bin/console dbal:run-sql "SELECT id, reference, name FROM product"` affiche 14 produits.

## Remettre la base à zéro

```
php bin/console doctrine:schema:drop --force --full-database
php bin/console doctrine:migrations:migrate -n
php bin/console doctrine:fixtures:load -n
```

## Choix techniques

- Pas de collection inverse (`Category::$products`, `Order::$items`…) : on évite ainsi les références circulaires
  lors de la sérialisation des premières séances. Elles pourront être ajoutées plus tard.
- `Product::$category` est facultatif, pour pouvoir créer un produit par l'API sans gérer la relation dès la séance 1.
- Les prix sont des `float` : avec un type `DECIMAL`, Doctrine manipule des chaînes de caractères et le Serializer
  refuserait un nombre en entrée.
