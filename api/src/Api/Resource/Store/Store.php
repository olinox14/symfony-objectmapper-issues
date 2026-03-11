<?php
declare(strict_types=1);

namespace App\Api\Resource\Store;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\ExactFilter;
use ApiPlatform\Doctrine\Orm\Filter\PartialSearchFilter;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\QueryParameter;
use App\Api\Dto\Store\CreateStore;
use App\Api\Dto\Store\StoreCollectionItem;
use App\Api\Dto\Store\UpdateStore;
use App\Api\Embedded\Store\Category;
use App\Api\Embedded\Store\Manager;
use App\Api\Embedded\Store\Supplier;
use App\Entity\Store as StoreEntity;
use App\ObjectMapper\Transform\PhoneTransformer;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Store',
    stateOptions: new Options(entityClass: StoreEntity::class)
)]
#[Get]
#[Get(
    uriTemplate: '/stores/{id}',
    uriVariables: ['id']
)]
#[GetCollection(
    uriTemplate: '/stores',
    output: StoreCollectionItem::class,
    parameters: [
        'title' => new QueryParameter(filter: new PartialSearchFilter()),
        'published' => new QueryParameter(filter: new BooleanFilter()),
        'siret' => new QueryParameter(
            filter: new ExactFilter(),
            constraints: [new Assert\Length(exactly: 14)],
        ),
    ],
)]
#[Post(
    uriTemplate: '/stores',
    input: CreateStore::class
)]
#[Patch(
    uriTemplate: '/stores/{id}',
    uriVariables: ['id'],
    input: UpdateStore::class
)]
#[Delete(
    uriTemplate: '/stores/{id}',
    uriVariables: ['id']
)]
#[Map(source: StoreEntity::class)]
class Store
{
    #[ApiProperty(identifier: true)]
    public int $id;

    public string $title;

    public ?string $siret = null;

    public ?int $surface = null;

    public ?bool $published = null;

    #[Map(source: 'contact?.email')]
    public ?string $email = null;

    #[Map(source: 'contact?.phone', transform: PhoneTransformer::class)]
    public ?string $phone = null;

    public ?\DateTimeInterface $openingDate = null;

    #[ApiProperty(genId: false)]
    public ?Manager $manager = null;

    /** @var Toy[] */
    #[ApiProperty(readableLink: true, genId: false)]
    public array $toys = [];

    /** @var Category[] */
    #[ApiProperty(genId: false)]
    public array $categories = [];

    /** @var Supplier[] */
    #[ApiProperty(genId: false)]
    public array $suppliers = [];
}
