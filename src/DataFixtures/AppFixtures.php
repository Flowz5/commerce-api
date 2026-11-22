<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Client;
use App\Entity\Employee;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\Supplier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Jeu d'essai repris de la base « Boutique » du cours de BDD (mêmes données, même ordre d'insertion,
 * donc mêmes identifiants sur une base neuve). Le prix d'achat est fictif (55 % du prix de vente).
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [];
        foreach ([
            [1, 'Électronique'],
            [2, 'Mobilier'],
            [3, 'Papeterie'],
            [4, 'Alimentation'],
            [5, 'Plomberie'],
            [6, 'Informatique'],
            [7, 'Médical'],
        ] as [$id, $label]) {
            $categories[$id] = (new Category())->setLabel($label);
            $manager->persist($categories[$id]);
        }

        $suppliers = [];
        foreach ([
            [1, 'Amazon', false, true, 'amazon@amazon.com'],
            [2, 'Microsoft', false, true, 'satya.nadella@microsoft.com'],
            [3, 'Plomberie Mario', false, true, 'itsame@gmail.com'],
            [4, 'CDiscount', false, true, null],
            [5, 'Legallais', false, true, null],
            [6, 'Leroy Merlin', false, true, null],
            [7, 'Lapeyre', false, true, null],
            [8, 'Kiloutou', false, true, null],
        ] as [$id, $name, $isLocal, $isActive, $email]) {
            $suppliers[$id] = (new Supplier())->setSupplierName($name)->setIsLocal($isLocal)->setIsActive($isActive)->setEmail($email);
            $manager->persist($suppliers[$id]);
        }

        // [id, référence, nom, prix de vente, stock, actif, catégorie, fournisseur, prix d'achat]
        $products = [];
        foreach ([
            [1, 'P-001', 'Clé USB 32 Go', 10.32, 45, true, 1, null, 5.68],
            [2, 'P-002', 'Casque audio', 59.99, 12, true, 1, null, 32.99],
            [3, 'P-003', 'Souris sans fil', 24.50, 0, true, 1, null, 13.48],
            [4, 'P-004', 'Chaise de bureau', 149.00, 8, true, 2, null, 81.95],
            [5, 'P-005', 'Lampe LED', 29.90, 25, true, 2, null, 16.45],
            [6, 'P-006', 'Ramette papier A4', 4.99, 200, true, 3, null, 2.74],
            [7, 'P-007', 'Stylo bille (lot de 10)', 3.50, 150, true, 3, null, 1.93],
            [8, 'P-008', 'Café en grains 1 kg', 18.50, 30, true, 4, null, 10.18],
            [9, 'P-009', 'Écran 24 pouces', 189.00, 4, true, 1, null, 103.95],
            [10, 'P-010', 'Bureau réglable', 449.00, 2, false, 2, null, 246.95],
            [11, 'P-011', 'Tuyau PVC diamètre 10cm longueur 2m', 15.99, 50, true, 5, 3, 8.79],
            [12, 'P-013', 'Tuyau PVC diamètre 5cm longueur 2m', 22.99, 300, true, 5, 3, 12.64],
            [13, 'P-014', 'Cable HDMI', 5.00, 1000, true, 1, 1, 2.75],
            [14, 'P-015', 'Cable coaxial', 5.00, 100, false, 1, null, 2.75],
        ] as [$id, $reference, $name, $price, $stock, $active, $category, $supplier, $purchasePrice]) {
            $products[$id] = (new Product())
                ->setReference($reference)
                ->setName($name)
                ->setUnitPrice($price)
                ->setStock($stock)
                ->setActive($active)
                ->setCategory($categories[$category])
                ->setSupplier(null === $supplier ? null : $suppliers[$supplier])
                ->setPurchasePrice($purchasePrice);
            $manager->persist($products[$id]);
        }

        $clients = [];
        foreach ([
            [1, 'Dupont', 'Marie', 'marie.dupont@email.fr', 'Lyon', '2024-09-12'],
            [2, 'Martin', 'Julien', 'julien.martin@email.fr', 'Paris', '2024-10-03'],
            [3, 'Bernard', 'Sophie', null, 'Lyon', '2024-11-20'],
            [4, 'Petit', 'Thomas', 'thomas.petit@email.fr', 'Marseille', '2025-01-15'],
            [5, 'Durand', 'Emma', 'emma.durand@email.fr', 'Paris', '2025-03-08'],
            [6, 'Moreau', 'Lucas', 'lucas.moreau@email.fr', 'Nantes', '2025-06-21'],
            [7, 'Burgun', 'Frederic', 'frederic.burgun@email.fr', 'Nice', '2026-11-03'],
            [8, 'Duchmoll', 'Julien', 'julien.duchmoll@email.fr', 'Perpignan', '2024-10-03'],
            [9, 'Vader', 'Darth', 'darthvader@email.fr', 'Tatooine', '2024-11-20'],
            [10, 'Grand', 'Thomas', 'thomasgrand@email.fr', 'Pointe-à-Pitre', '2025-01-15'],
            [11, 'Durand', 'Emma', 'emmadurand@autreemail.fr', 'Paris', '2023-10-08'],
        ] as [$id, $lastName, $firstName, $email, $city, $date]) {
            $clients[$id] = (new Client())->setLastName($lastName)->setFirstName($firstName)->setEmail($email)
                ->setCity($city)->setRegistrationDate(new \DateTimeImmutable($date));
            $manager->persist($clients[$id]);
        }

        $employees = [];
        foreach ([
            [1, 'Martin', 'Claire', 'claire.martin@boutique.fr', 'cmartin'],
            [2, 'Lefevre', 'Antoine', 'antoine.lefevre@boutique.fr', 'alefevre'],
            [3, 'Nguyen', 'Linh', 'linh.nguyen@boutique.fr', 'lnguyen'],
            [4, 'Roux', 'Bastien', 'bastien.roux@boutique.fr', 'broux'],
            [5, 'Zimmer', 'Marie', null, 'mzimmer'],
        ] as [$id, $lastName, $firstName, $email, $login]) {
            $employees[$id] = (new Employee())->setLastName($lastName)->setFirstName($firstName)->setEmail($email)->setLogin($login);
            $manager->persist($employees[$id]);
        }

        $orders = [];
        foreach ([
            [1, 1, '2025-02-10', 'livree'],
            [2, 1, '2025-03-05', 'livree'],
            [3, 2, '2025-04-18', 'livree'],
            [4, 3, '2025-05-02', 'annulee'],
            [5, 2, '2025-06-14', 'expediee'],
            [6, 4, '2025-09-30', 'livree'],
            [7, 5, '2025-11-11', 'en_cours'],
            [8, 5, '2025-12-24', 'en_cours'],
            [9, 6, '2026-01-19', 'expediee'],
            [10, 3, '2026-02-08', 'livree'],
            [11, 1, '2026-03-12', 'en_cours'],
            [12, 2, '2026-04-25', 'expediee'],
        ] as [$id, $client, $date, $status]) {
            $orders[$id] = (new Order())->setClient($clients[$client])->setOrderDate(new \DateTimeImmutable($date))->setStatus($status);
            $manager->persist($orders[$id]);
        }

        foreach ([
            [1, 1, 2, null],
            [1, 6, 5, null],
            [2, 2, 1, null],
            [2, 5, 1, null],
            [3, 4, 2, null],
            [3, 7, 3, null],
            [4, 9, 1, null],
            [5, 8, 4, null],
            [5, 1, 1, null],
            [6, 6, 10, null],
            [6, 7, 2, null],
            [7, 2, 1, null],
            [8, 5, 2, null],
            [8, 8, 2, null],
            [9, 9, 1, null],
            [9, 1, 3, null],
            [10, 4, 1, null],
            [11, 6, 8, null],
            [11, 8, 1, null],
            [11, 11, 10, null],
            [12, 2, 2, null],
            [12, 5, 1, null],
        ] as [$order, $product, $quantity, $employee]) {
            $manager->persist((new OrderItem())->setOrder($orders[$order])->setProduct($products[$product])
                ->setQuantity($quantity)->setEmployee(null === $employee ? null : $employees[$employee]));
        }

        $manager->flush();
    }
}
