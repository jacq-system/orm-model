<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search;

use Doctrine\ORM\QueryBuilder;

final class SpecimenSearchJoinManager
{
    private array $joins = [];
    private array $appliedFlags = [];


    public function leftJoin(QueryBuilder $qb, string $path, string $alias): void
    {
        if (isset($this->joins[$alias])) {
            return;
        }

        $qb->leftJoin($path, $alias);
        $this->joins[$alias] = true;
    }

    public function innerJoin(QueryBuilder $qb, string $path, string $alias): void
    {
        if (isset($this->joins[$alias])) {
            return;
        }

        $qb->innerJoin($path, $alias);
        $this->joins[$alias] = true;
    }

    /**
     * used for non-join related duplicities prevention
     */
    public function addFlag(string $flag): bool
    {
        if (isset($this->appliedFlags[$flag])) {
            return false;
        }

        $this->appliedFlags[$flag] = true;
        return true;
    }

    public function hasFlag(string $flag): bool
    {
        if (isset($this->appliedFlags[$flag])) {
            return true;
        }

        return false;
    }
}
