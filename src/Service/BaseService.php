<?php declare(strict_types=1);

namespace JACQ\Service;

use Doctrine\DBAL\Result;
use Doctrine\ORM\EntityManagerInterface;

readonly abstract class BaseService
{
    public function __construct(protected EntityManagerInterface $entityManager, protected JacqNetworkService $jacqNetworkService)
    {
    }

    /**
     * @param string $sql
     * @param mixed[] $params
     * @param mixed[] $types
     * @return Result
     */
    protected function query(string $sql, array $params = [], array $types = []): Result
    {
        return $this->entityManager->getConnection()->executeQuery($sql, $params, $types);
    }

}
