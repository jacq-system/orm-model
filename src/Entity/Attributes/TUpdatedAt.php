<?php declare(strict_types=1);

namespace JACQ\Entity\Attributes;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;

trait TUpdatedAt
{

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    protected(set) DateTimeImmutable $updatedAt;

    public function setUpdatedAt(): mixed
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

}
