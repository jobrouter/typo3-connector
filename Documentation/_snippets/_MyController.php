<?php

declare(strict_types=1);

use JobRouter\AddOn\RestClient\Exception\ExceptionInterface;
use JobRouter\AddOn\Typo3Connector\Domain\Entity\Connection;
use JobRouter\AddOn\Typo3Connector\Domain\Repository\ConnectionRepository;
use JobRouter\AddOn\Typo3Connector\Exception\ConnectionNotFoundException;
use JobRouter\AddOn\Typo3Connector\RestClient\RestClientFactory;
use Psr\Http\Message\ResponseInterface;

final class MyController
{
    private ConnectionRepository $connectionRepository;
    private RestClientFactory $restClientFactory;

    public function __construct(
        ConnectionRepository $connectionRepository,
        RestClientFactory $restClientFactory,
    ) {
        $this->connectionRepository = $connectionRepository;
        $this->restClientFactory = $restClientFactory;
    }

    public function myAction(): ResponseInterface
    {
        try {
            /** @var Connection $connection */
            $connection = $this->connectionRepository->findByHandle('example');
        } catch (ConnectionNotFoundException) {
            // The connection is not found or disabled
        }

        try {
            $client = $this->restClientFactory->create($connection, 60);
        } catch (ExceptionInterface) {
            // Maybe authentication failure or HTTP error
        }

        // Now you can call the request() method of the $client
    }
}
