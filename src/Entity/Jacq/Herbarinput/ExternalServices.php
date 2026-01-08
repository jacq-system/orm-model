<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;
use JACQ\Repository\Herbarinput\ExternalServicesRepository;

#[ORM\Entity(repositoryClass: ExternalServicesRepository::class)]
#[ORM\Table(name: 'tbl_nom_service', schema: 'herbarinput')]
class ExternalServices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'serviceID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'name')]
    protected(set) string $name;

    #[ORM\Column(name: 'api_code')]
    protected(set) ?string $apiCode;

    #[ORM\Column(name: 'api_url')]
    protected(set) ?string $apiUrl;

}
