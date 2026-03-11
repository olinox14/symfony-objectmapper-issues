<?php

namespace App\Factory\Story;

use App\Enum\ColorEnum;
use App\Factory\BadgeFactory;
use App\Factory\CategoryFactory;
use App\Factory\ContactFactory;
use App\Factory\ManagerFactory;
use App\Factory\StoreFactory;
use App\Factory\SupplierFactory;
use App\Factory\ToyFactory;
use libphonenumber\PhoneNumberUtil;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;
use function Zenstruck\Foundry\faker;
use function Zenstruck\Foundry\Persistence\save;

#[AsFixture(name: 'app:seed')]
final class AppStory extends Story
{
    public function build(): void
    {
        faker()->seed(1234);

        // Base reference data
        $categories = CategoryFactory::createMany(6);
        $badges = [
            BadgeFactory::createOne(['label' => 'Standard', 'color' => ColorEnum::GREEN]),
            BadgeFactory::createOne(['label' => 'Premium', 'color' => ColorEnum::ORANGE]),
            BadgeFactory::createOne(['label' => 'Legacy', 'color' => ColorEnum::VIOLET]),
        ];

        // 1) Store avec toutes les relations (manager+badge, toys, categories)
        $fullManager = ManagerFactory::createOne([
            'name' => 'Manager Full',
            'badge' => $badges[0],
        ]);
        $fullStore = StoreFactory::createOne([
            'title' => 'Store Full',
            'siret' => '12345678901234',
            'manager' => $fullManager,
            'contact' => ContactFactory::createOne([
                'address' => '123 Rue de la Paix, Paris',
                'email' => 'contact@store-full.fr',
                'phone' => PhoneNumberUtil::getInstance()->parse('+33123456789'),
            ]),
        ]);
        // Toys liés à ce store
        ToyFactory::createOne([
            'description' => 'Toy 1',
            'store' => $fullStore,
        ]);
        ToyFactory::createOne([
            'description' => 'Toy 2',
            'store' => $fullStore,
        ]);

        // 3 catégories
        $fullStore->addCategory($categories[0]);
        $fullStore->addCategory($categories[1]);
        $fullStore->addCategory($categories[2]);

        // 2) Store sans relations
        StoreFactory::createOne([
            'title' => 'Store Empty',
            'manager' => null,
        ]);

        // 3) Autres stores variés
        // a) store avec seulement des catégories
        $storeWithCategories = StoreFactory::createOne([
            'title' => 'Store with Categories',
        ]);
        $storeWithCategories->addCategory($categories[3]);
        $storeWithCategories->addCategory($categories[4]);

        // b) store avec seulement des toys
        $storeWithToys = StoreFactory::createOne([
            'title' => 'Store with Toys',
        ]);
        ToyFactory::createOne(['description' => 'Toy only 1', 'store' => $storeWithToys]);

        // c) store avec seulement un manager (et badge optionnel)
        StoreFactory::createOne([
            'title' => 'Store with Manager',
            'manager' => ManagerFactory::createOne([
                'name' => 'Manager Simple',
                'badge' => $badges[1],
            ]),
        ]);

        // Suppliers: 1 lié au store 1, 1 lié au store 4, 1 lié aux deux
        $supplierStore1 = SupplierFactory::createOne(['name' => 'Supplier Store 1']);
        $supplierStore4 = SupplierFactory::createOne(['name' => 'Supplier Store 4']);
        $supplierShared = SupplierFactory::createOne(['name' => 'Supplier Shared']);

        $fullStore->addSupplier($supplierStore1);
        $fullStore->addSupplier($supplierShared);

        $storeWithToys->addSupplier($supplierStore4);
        $storeWithToys->addSupplier($supplierShared);

        // Les modifications de relation faites en fin de story doivent être explicitement persistées.
        save($fullStore);
        save($storeWithToys);
    }
}
