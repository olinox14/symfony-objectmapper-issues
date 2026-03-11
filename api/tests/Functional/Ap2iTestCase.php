<?php
declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;

class Ap2iTestCase extends ApiTestCase
{
    protected static ?bool $alwaysBootKernel = true;

    protected function setUp(): void
    {
        $this->em = static::getContainer()->get('api_platform.iri_converter');

        parent::setUp();
//        self::bootKernel();
//        AppStory::load();
    }

    protected function executeDbQuery(string $query): Result
    {
        $cnn = static::getContainer()->get(Connection::class);

        return $cnn->executeQuery($query);
    }

    public function request(string $method, string $uri, array $options = []): array
    {
        $response = static::createClient()->request($method, $uri, $options);

        static::assertResponseIsSuccessful();

        return $response->toArray();
    }

    protected function addBodyOption(array $options, array|string $body): array
    {
        $option = is_array($body) ? ['json' => $body] : ['body' => $body];

        return array_merge($options, $option);
    }

    public function get(string $uri, array $options = []): array
    {
        return $this->request(HttpOperation::METHOD_GET, $uri, $options);
    }

    public function patch(string $uri, array $body, array $options = []): array
    {
        $options = $this->addBodyOption($options, $body);

        $options['headers'] = array_merge($options['headers'] ?? [], ['Content-Type' => 'application/merge-patch+json']);

        return $this->request(HttpOperation::METHOD_PATCH, $uri, $options);
    }

    public function post(string $uri, array $body, array $options = []): array
    {
        $options = $this->addBodyOption($options, $body);

        return $this->request(HttpOperation::METHOD_POST, $uri, $options);
    }

    public function put(string $uri, array $body, array $options = []): array
    {
        $options = $this->addBodyOption($options, $body);

        return $this->request(HttpOperation::METHOD_PUT, $uri, $options);
    }

    public function delete(string $uri, array $options = []): array
    {
        return $this->request(HttpOperation::METHOD_DELETE, $uri, $options);
    }
}
