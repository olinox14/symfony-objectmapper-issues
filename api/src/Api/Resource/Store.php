<?php
declare(strict_types=1);

namespace App\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Api\Dto\Store\StoreCollectionItem;
use App\Api\Embedded\Store\Category;
use App\Api\Embedded\Store\Manager;
use App\Entity\Store as StoreEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\ObjectMapper\Transform\MapCollection;

#[ApiResource(
    shortName: 'Store',
    stateOptions: new Options(entityClass: StoreEntity::class)
)]
#[Get(
    uriTemplate: '/stores/{id}',
    uriVariables: ['id']
)]
#[GetCollection(
    uriTemplate: '/stores',
    output: StoreCollectionItem::class
)]
#[Post(
    uriTemplate: '/stores'
)]
#[Patch(
    uriTemplate: '/stores/{id}',
    uriVariables: ['id']
)]
#[Delete(
    uriTemplate: '/stores/{id}',
    uriVariables: ['id']
)]
#[Map(source: StoreEntity::class)]
class Store
{
    #[ApiProperty(identifier: true)]
    #[Map(source: 'id')]
    public int $id;

//    #[Map(source: 'title')]
    public string $title;

    #[Map(source: 'manager')]
    public ?Manager $manager = null;

    /** @var Category[] */
    #[Map(source: 'categories', transform: MapCollection::class)]
    public array $categories = [];
}
