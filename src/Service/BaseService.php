<?php declare(strict_types=1);

namespace JACQ\Service;

use Doctrine\DBAL\Result;
use Doctrine\ORM\EntityManagerInterface;

readonly abstract class BaseService
{
    public function __construct(protected EntityManagerInterface $entityManager, protected JacqNetworkService $jacqNetworkService)
    {
    }

    protected function query(string $sql, array $params = [], array $types = []): Result
    {
        return $this->entityManager->getConnection()->executeQuery($sql, $params, $types);
    }

}
