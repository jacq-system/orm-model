<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JACQ\Repository\Herbarinput\SpeciesRepository;

#[ORM\Entity(repositoryClass: SpeciesRepository::class)]
#[ORM\Table(name: 'tbl_tax_species', schema: 'herbarinput')]
class Species
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'taxonID')]
    protected(set) ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Genus::class)]
    #[ORM\JoinColumn(name: 'genID', referencedColumnName: 'genID')]
    protected(set) Genus $genus;

    #[ORM\ManyToOne(targetEntity: Authors::class)]
    #[ORM\JoinColumn(name: 'authorID', referencedColumnName: 'authorID')]
    protected(set) ?Authors $authorSpecies;
    #[ORM\ManyToOne(targetEntity: Authors::class)]
    #[ORM\JoinColumn(name: 'subspecies_authorID', referencedColumnName: 'authorID')]
    protected(set) ?Authors $authorSubspecies;
    #[ORM\ManyToOne(targetEntity: Authors::class)]
    #[ORM\JoinColumn(name: 'variety_authorID', referencedColumnName: 'authorID')]
    protected(set) ?Authors $authorVariety;
    #[ORM\ManyToOne(targetEntity: Authors::class)]
    #[ORM\JoinColumn(name: 'subvariety_authorID', referencedColumnName: 'authorID')]
    protected(set) ?Authors $authorSubvariety;
    #[ORM\ManyToOne(targetEntity: Authors::class)]
    #[ORM\JoinColumn(name: 'forma_authorID', referencedColumnName: 'authorID')]
    protected(set) ?Authors $authorForma;
    #[ORM\ManyToOne(targetEntity: Authors::class)]
    #[ORM\JoinColumn(name: 'subforma_authorID', referencedColumnName: 'authorID')]
    protected(set) ?Authors $authorSubforma;

    #[ORM\ManyToOne(targetEntity: Epithet::class)]
    #[ORM\JoinColumn(name: 'speciesID', referencedColumnName: 'epithetID')]
    protected(set) ?Epithet $epithetSpecies;

    #[ORM\ManyToOne(targetEntity: Epithet::class)]
    #[ORM\JoinColumn(name: 'subspeciesID', referencedColumnName: 'epithetID')]
    protected(set) ?Epithet $epithetSubspecies;

    #[ORM\ManyToOne(targetEntity: Epithet::class)]
    #[ORM\JoinColumn(name: 'varietyID', referencedColumnName: 'epithetID')]
    protected(set) ?Epithet $epithetVariety;

    #[ORM\ManyToOne(targetEntity: Epithet::class)]
    #[ORM\JoinColumn(name: 'subvarietyID', referencedColumnName: 'epithetID')]
    protected(set) ?Epithet $epithetSubvariety;

    #[ORM\ManyToOne(targetEntity: Epithet::class)]
    #[ORM\JoinColumn(name: 'formaID', referencedColumnName: 'epithetID')]
    protected(set) ?Epithet $epithetForma;

    #[ORM\ManyToOne(targetEntity: Epithet::class)]
    #[ORM\JoinColumn(name: 'subformaID', referencedColumnName: 'epithetID')]
    protected(set) ?Epithet $epithetSubforma;

    #[ORM\Column(name: 'statusID')]
    protected(set) int $status;

    #[ORM\Column(name: 'external')]
    protected(set) bool $external;

    #[ORM\ManyToOne(targetEntity: Species::class)]
    #[ORM\JoinColumn(name: "synID", referencedColumnName: "taxonID", nullable: true)]
    protected(set) ?Species $validName = null;

    #[ORM\ManyToOne(targetEntity: Species::class)]
    #[ORM\JoinColumn(name: "basID", referencedColumnName: "taxonID", nullable: true)]
    protected(set) ?Species $basionym = null;

    #[ORM\ManyToOne(targetEntity: TaxonRank::class)]
    #[ORM\JoinColumn(name: "tax_rankID", referencedColumnName: "tax_rankID", nullable: true)]
    protected(set) TaxonRank $rank;

    #[ORM\OneToMany(mappedBy: 'species', targetEntity: Specimens::class)]
    protected(set) Collection $specimens;

    public function __construct()
    {
        $this->specimens = new ArrayCollection();
    }

    public function isSynonym(): bool
    {
        return $this->validName !== null;
    }


    public function getFullName(bool $html = false): string
    {
        $text = '<i>' . $this->genus->name . '</i>';
        if ($this->epithetSpecies !== null) {
            $text .= " <i>" . $this->epithetSpecies->name . "</i> " . $this->authorSpecies?->name;
        }

        if ($this->epithetSubspecies !== null) {
            $text .= " subsp. <i>" . $this->epithetSubspecies->name . "</i> " . $this->authorSubspecies?->name;
        }
        if ($this->epithetVariety !== null) {
            $text .= " var. <i>" . $this->epithetVariety->name . "</i> " . $this->authorVariety?->name;
        }
        if ($this->epithetSubvariety !== null) {
            $text .= " subvar. <i>" . $this->epithetSubvariety->name . "</i> " . $this->authorSubvariety?->name;
        }
        if ($this->epithetForma !== null) {
            $text .= " forma <i>" . $this->epithetForma->name . "</i> " . $this->authorForma?->name;
        }
        if ($this->epithetSubforma !== null) {
            $text .= " subforma <i>" . $this->epithetSubforma->name . "</i> " . $this->authorSubforma?->name;
        }

        if (!$html) {
            return strip_tags($text);
        }
        return $text;
    }

    public function getInfraEpithet(): array
    {

        if ($this->epithetSubforma !== null) {
            $author = $this->authorSubforma?->name;
            $epithet = $this->epithetSubforma->name;
        } elseif ($this->epithetForma !== null) {
            $author = $this->authorForma?->name;
            $epithet = $this->epithetForma->name;
        } elseif ($this->epithetSubvariety !== null) {
            $author = $this->authorSubvariety?->name;
            $epithet = $this->epithetSubvariety->name;
        } elseif ($this->epithetVariety !== null) {
            $author = $this->authorVariety?->name;
            $epithet = $this->epithetVariety->name;
        } elseif ($this->epithetSubspecies !== null) {
            $author = $this->authorSubspecies?->name;
            $epithet = $this->epithetSubspecies->name;
        } else {
            $author = '';
            $epithet = '';
        }

        return ['author' => $author, 'epithet' => $epithet];
    }

    public function isHybrid(): bool
    {
        return ($this->status === 1 && $this->epithetSpecies === null && $this->authorSpecies === null);
    }

}
