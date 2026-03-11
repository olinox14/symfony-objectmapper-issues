<?php
declare(strict_types=1);

namespace Functional\Store;

use App\Api\Resource\Store;
use App\Tests\Functional\Ap2iTestCase;

class StoreTest extends Ap2iTestCase
{
    /**
     * GET d'un item et vérification d'une propriété scalaire
     */
    public function testGetStoreBaseData(): void
    {
        $response = $this->get('/api/stores/1');

        static::assertMatchesResourceItemJsonSchema(Store::class);

        $this->assertArraySubset(
            [
                '@type' => 'Store',
                '@id' => '/api/stores/1',
                'title' => 'Store Full',
            ]
        , $response);
    }

    /**
     * GET d'un item et vérification d'une propriété relationnelle 1-1
     */
    public function testGetStoreRelations(): void
    {
        $response = $this->get('/api/stores/1');

        static::assertMatchesResourceItemJsonSchema(Store::class);

        $this->assertArrayHasKey('manager', $response);

        $this->assertArraySubset(
            [
                '@type' => 'Manager',
                'id' => 1,
                'name' => 'Manager Full'
            ]
            , $response['manager']
        );

        $this->assertArrayHasKey('toys', $response);
        $this->assertCount(5, $response['toys']);
        $this->assertArraySubset(
            [
                '@type' => 'Manager',
                'id' => 1,
                'description' => 'Manager Full'
            ]
            , $response['toys'][0]
        );
        // On vérifie que pas d'id auto-généré
        $this->assertArrayNotHasKey('@id', $response['toys'][0]);

        $this->assertArrayHasKey('suppliers', $response);
        $this->assertCount(2, $response['suppliers']);
        $this->assertArraySubset(
            [
                '@type' => 'Manager',
                'id' => 1,
                'name' => 'Manager Full'
            ]
            , $response['suppliers'][0]
        );
        // On vérifie que pas d'id auto-généré
        $this->assertArrayNotHasKey('@id', $response['suppliers'][0]);
    }

    /**
     * GET d'un item et vérification d'une propriété indirecte
     */
    public function testGetStoreIndirectProp(): void
    {
        $response = $this->get('/api/stores/1');

        static::assertMatchesResourceItemJsonSchema(Store::class);

        $this->assertSame('contact@store-full.fr', $response['email']);
    }

    /**
     * GET d'une liste d'items et vérification du nombre d'items retournés et
     * d'une propriété scalaire d'un des membres de la liste
     */
    public function testGetStores(): void
    {
        $response = $this->get('/api/stores');

        static::assertMatchesResourceItemJsonSchema(Store::class);

        $this->assertCount(5, $response['member']);

        $this->assertSame('Store Full', $response['member'][0]['title']);
    }

    /**
     * GET d'une liste d'items et vérification du nombre d'items retournés et
     * d'une propriété scalaire d'un des membres de la liste
     */
    public function testPatchStoreWithScalarProp(): void
    {
        $this->patch('/api/stores/2', [ 'surface' => 101 ]);

        static::assertMatchesResourceItemJsonSchema(Store::class);

        $newValue = $this
            ->executeDbQuery('SELECT surface FROM store WHERE id = 2')
            ->fetchOne();

        $this->assertSame(101, $newValue);
    }

    /**
     * GET d'une liste d'items et vérification du nombre d'items retournés et
     * d'une propriété scalaire d'un des membres de la liste
     */
    public function testPatchStoreWithRelation1To1(): void
    {
        $this->patch('/api/stores/1', [ 'manager' => '/api/managers/2' ]);

        static::assertMatchesResourceItemJsonSchema(Store::class);

        $newValue = $this
            ->executeDbQuery('SELECT manager_id FROM store WHERE id = 1')
            ->fetchOne();

        $this->assertSame(2, $newValue);
    }

    /**
     * PATCH d'une relation 1-n
     * On met à jour les Toys liés à un Store
     */
    public function testPatchStoreWithRelation1ToN(): void
    {
        $this->patch(
            '/api/stores/1',
            [ 'toys' =>
                [
                    '/api/toys/1'
                ]
            ]
        );

        static::assertMatchesResourceItemJsonSchema(Store::class);

        $newValue = $this
            ->executeDbQuery('SELECT count(*) FROM toy WHERE store_id = 1')
            ->fetchOne();

        $this->assertSame(1, $newValue);
    }

    /**
     * PATCH d'une relation 1-n
     * On met à jour les Categories liés à un Store
     */
    public function testPatchStoreWithRelationNToN(): void
    {
        $this->patch(
            '/api/stores/1',
            [ 'suppliers' =>
                [
                    '/api/suppliers/1'
                ]
            ]
        );

        static::assertMatchesResourceItemJsonSchema(Store::class);

        $newValue = $this
            ->executeDbQuery('SELECT count(*) FROM store_supplier WHERE store_id = 1')
            ->fetchOne();

        $this->assertSame(1, $newValue);
    }
}
