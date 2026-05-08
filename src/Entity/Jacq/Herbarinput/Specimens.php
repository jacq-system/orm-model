<?php

declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\Mapping as ORM;
use JACQ\Repository\Herbarinput\SpecimensRepository;

#[ORM\Entity(repositoryClass: SpecimensRepository::class)]
#[ORM\Table(name: 'tbl_specimens', schema: 'herbarinput')]
class Specimens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'specimen_ID')]
    public protected(set) ?int $id = null;

    #[ORM\Column(name: 'Nummer')]
    public protected(set) ?int $number = null;

    #[ORM\Column(name: 'altitude_min')]
    public protected(set) ?int $altitudeMin = null;
    #[ORM\Column(name: 'altitude_max')]
    public protected(set) ?int $altitudeMax = null;

    #[ORM\Column(name: 'HerbNummer')]
    public protected(set) ?string $herbNumber = null;

    #[ORM\Column(name: 'alt_number')]
    public protected(set) ?string $altNumber = null;

    #[ORM\Column(name: 'series_number')]
    public protected(set) ?string $seriesNumber;

    #[ORM\Column(name: 'CollNummer')]
    public protected(set) ?string $collectionNumber = null;

    #[ORM\Column(name: 'observation')]
    public protected(set) ?bool $observation;

    #[ORM\Column(name: 'accessible')]
    public protected(set) bool $accessibleForPublic;

    #[ORM\Column(name: 'Datum')]
    public protected(set) ?string $date;

    #[ORM\Column(name: 'Datum2')]
    public protected(set) ?string $date2;

    #[ORM\Column(name: 'Fundort')]
    public protected(set) ?string $locality = null;

    #[ORM\Column(name: 'Fundort_engl')]
    public protected(set) ?string $localityEng;

    #[ORM\Column(name: 'habitus')]
    public protected(set) ?string $habitus = null;

    #[ORM\Column(name: 'det')]
    public protected(set) ?string $determination = null;

    #[ORM\Column(name: 'habitat')]
    public protected(set) ?string $habitat;

    #[ORM\Column(name: 'Bemerkungen')]
    public protected(set) ?string $annotation;

    #[ORM\Column(name: 'digital_image')]
    public protected(set) ?bool $image;

    #[ORM\Column(name: 'digital_image_obs')]
    public protected(set) ?bool $imageObservation;

    #[ORM\Column(name: 'taxon_alt')]
    public protected(set) ?string $taxonAlternative;

    #[ORM\Column(name: 'Coord_S')]
    public protected(set) ?int $degreeS;

    #[ORM\Column(name: 'S_Min')]
    public protected(set) ?int $minuteS;

    #[ORM\Column(name: 'S_Sec')]
    public protected(set) ?float $secondS;

    #[ORM\Column(name: 'Coord_N')]
    public protected(set) ?int $degreeN;

    #[ORM\Column(name: 'N_Min')]
    public protected(set) ?int $minuteN;

    #[ORM\Column(name: 'N_Sec')]
    public protected(set) ?float $secondN;

    #[ORM\Column(name: 'Coord_W')]
    public protected(set) ?int $degreeW;

    #[ORM\Column(name: 'W_Min')]
    public protected(set) ?int $minuteW;

    #[ORM\Column(name: 'W_Sec')]
    public protected(set) ?float $secondW;

    #[ORM\Column(name: 'Coord_E')]
    public protected(set) ?int $degreeE;

    #[ORM\Column(name: 'E_Min')]
    public protected(set) ?int $minuteE;

    #[ORM\Column(name: 'E_Sec')]
    public protected(set) ?float $secondE;

    #[ORM\Column(name: 'ncbi_accession')]
    public protected(set) ?string $ncbiAccession;

    #[ORM\Column(name: 'typified')]
    public protected(set) ?string $typified;

    #[ORM\Column(name: 'garten')]
    public protected(set) ?string $garden;

    #[ORM\Column(name: 'Bezirk')]
    public protected(set) ?string $region;

    #[ORM\Column(name: 'quadrant')]
    public protected(set) ?int $quadrant;

    #[ORM\Column(name: 'quadrant_sub')]
    public protected(set) ?int $quadrantSub;

    #[ORM\Column(name: 'exactness')]
    public protected(set) ?float $exactness;

    #[ORM\Column(name: 'DiSSCo_ID', type: 'string')]
    public protected(set) ?string $pidDissco;
    #[ORM\Column(name: 'GBIF_ID', type: 'string')]
    public protected(set) ?string $pidGbif;

    #[ORM\ManyToOne(targetEntity: HerbCollection::class, inversedBy: 'specimens')]
    #[ORM\JoinColumn(name: 'collectionID', referencedColumnName: 'collectionID')]
    public protected(set) HerbCollection $herbCollection;

    #[ORM\ManyToOne(targetEntity: Series::class)]
    #[ORM\JoinColumn(name: 'seriesID', referencedColumnName: 'seriesID')]
    public protected(set) ?Series $series = null;

    #[ORM\ManyToOne(targetEntity: Collector::class)]
    #[ORM\JoinColumn(name: 'SammlerID', referencedColumnName: 'SammlerID')]
    public protected(set) ?Collector $collector = null;
    #[ORM\ManyToOne(targetEntity: Collector2::class)]
    #[ORM\JoinColumn(name: 'Sammler_2ID', referencedColumnName: 'Sammler_2ID')]
    public protected(set) ?Collector2 $collector2 = null;

    /**
     * @note nonempty $typus means "this is a type specimen"
     * @var Collection<int, Typus>
     */
    #[ORM\OneToMany(targetEntity: Typus::class, mappedBy: 'specimen')]
    #[ORM\OrderBy(["date" => "DESC"])]
    public protected(set) Collection $typus;

    /**
     * @note https://github.com/jacq-system/jacq-legacy/issues/4,
     * Only informative label that some typus info is present on the physical specimen (e.g. stamp, handwritten etc.)
     */
    #[ORM\Column(name: 'typusID')]
    public protected(set) ?bool $isTypus;

    /**
     * @var Collection<int, StableIdentifier>
     */
    #[ORM\OneToMany(targetEntity: StableIdentifier::class, mappedBy: 'specimen')]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    public protected(set) Collection $stableIdentifiers;

    //TODO performance killer
    //    #[ORM\OneToOne(targetEntity: PhaidraCache::class, mappedBy: 'specimen')]
    //    protected(set) ?PhaidraCache $phaidraImages = null;
    //
    //    #[ORM\OneToOne(targetEntity: EuropeanaImages::class, mappedBy: 'specimen')]
    //    protected(set) ?EuropeanaImages $europeanaImages = null;

    #[ORM\ManyToOne(targetEntity: Species::class, inversedBy: 'specimens')]
    #[ORM\JoinColumn(name: 'taxonID', referencedColumnName: 'taxonID')]
    public protected(set) Species $species;

    #[ORM\ManyToOne(targetEntity: Province::class)]
    #[ORM\JoinColumn(name: 'provinceID', referencedColumnName: 'provinceID')]
    public protected(set) ?Province $province = null;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    #[ORM\JoinColumn(name: 'NationID', referencedColumnName: 'NationID')]
    public protected(set) ?Country $country = null;

    #[ORM\ManyToOne(targetEntity: IdentificationStatus::class)]
    #[ORM\JoinColumn(name: 'identstatusID', referencedColumnName: 'identstatusID')]
    public protected(set) ?IdentificationStatus $identificationStatus;

    #[ORM\ManyToOne(targetEntity: SpecimenVoucherType::class)]
    #[ORM\JoinColumn(name: 'voucherID', referencedColumnName: 'voucherID')]
    public protected(set) ?SpecimenVoucherType $voucher;

    /**
    * @var Collection<int, SpecimenLink>
    */
    #[ORM\OneToMany(targetEntity: SpecimenLink::class, mappedBy: 'specimen1')]
    public protected(set) Collection $outgoingRelations;

    /**
    * @var Collection<int, SpecimenLink>
    */
    #[ORM\OneToMany(targetEntity: SpecimenLink::class, mappedBy: 'specimen2')]
    public protected(set) Collection $incomingRelations;


    public function __construct()
    {
        $this->typus = new ArrayCollection();
        $this->stableIdentifiers = new ArrayCollection();
        $this->outgoingRelations = new ArrayCollection();
        $this->incomingRelations = new ArrayCollection();
    }


    public function getImageIconFilename(): ?string
    {
        if ($this->observation) {
            if ($this->imageObservation) {
                return "obs.png";
            } else {
                return "obs_bw.png";
            }
        } else {
            if ($this->image || $this->imageObservation) {
                if ($this->imageObservation && $this->image) {
                    return "spec_obs.png";
                } elseif ($this->imageObservation && !$this->image) {
                    return "obs.png";
                } else {
                    return "camera.png";
                }
            }
        }
        return null;
    }


    public function getCoords(bool $round = true): ?string
    {
        if (!$this->hasCoords()) {
            return null;
        }
        if ($round) {
            return round($this->getLatitude(), 5) . "," . round($this->getLongitude(), 5);
        }
        return $this->getLatitude() . "," . $this->getLongitude();
    }

    public function hasCoords(): bool
    {
        if ($this->getLatitude() !== null && $this->getLongitude() !== null) {
            return true;
        }
        return false;
    }

    /**
     * @deprecated
     */
    public function getLatitude(): ?float
    {
        if ($this->degreeS > 0 || $this->minuteS > 0 || $this->secondS > 0) {
            return -($this->degreeS + $this->minuteS / 60 + $this->secondS / 3600);
        } elseif ($this->degreeN > 0 || $this->minuteN > 0 || $this->secondN > 0) {
            return $this->degreeN + $this->minuteN / 60 + $this->secondN / 3600;
        }
        return null;
    }

    /**
     * @deprecated
     */
    public function getLongitude(): ?float
    {
        if ($this->degreeW > 0 || $this->minuteW > 0 || $this->secondW > 0) {
            return -($this->degreeW + $this->minuteW / 60 + $this->secondW / 3600);
        } elseif ($this->degreeE > 0 || $this->minuteE > 0 || $this->secondE > 0) {
            return $this->degreeE + $this->minuteE / 60 + $this->secondE / 3600;
        }
        return null;
    }

    public function getDMSCoords(): ?string
    {
        if (!$this->hasCoords()) {
            return null;
        }
        return $this->getLatitudeDMS() . "," . $this->getLongitudeDMS();
    }

    public function getLatitudeDMS(): ?string
    {
        if ($this->degreeS > 0 || $this->minuteS > 0 || $this->secondS > 0) {
            $deg = $this->degreeS;
            $min = $this->minuteS;
            $sec = $this->secondS;
            $hemisphere = 'S';
        } elseif ($this->degreeN > 0 || $this->minuteN > 0 || $this->secondN > 0) {
            $deg = $this->degreeN;
            $min = $this->minuteN;
            $sec = $this->secondN;
            $hemisphere = 'N';
        } else {
            return null;
        }

        return sprintf('%d°%d′%.2f″%s', $deg, $min, $sec, $hemisphere);
    }

    public function getLongitudeDMS(): ?string
    {
        if ($this->degreeW > 0 || $this->minuteW > 0 || $this->secondW > 0) {
            $deg = $this->degreeW;
            $min = $this->minuteW;
            $sec = $this->secondW;
            $hemisphere = 'W';
        } elseif ($this->degreeE > 0 || $this->minuteE > 0 || $this->secondE > 0) {
            $deg = $this->degreeE;
            $min = $this->minuteE;
            $sec = $this->secondE;
            $hemisphere = 'E';
        } else {
            return null;
        }

        return sprintf('%d°%d′%.2f″%s', $deg, $min, $sec, $hemisphere);
    }

    public function getVerbatimLatitude(): string
    {
        if ($this->degreeS > 0 || $this->minuteS > 0 || $this->secondS > 0) {
            return $this->degreeS . "d " . (($this->minuteS) ?: '?') . "m " . (($this->secondS) ?: '?') . 's S';
        } elseif ($this->degreeN > 0 || $this->minuteN > 0 || $this->secondN > 0) {
            return $this->degreeN . "d " . (($this->minuteN) ?: '?') . "m " . (($this->secondN) ?: '?') . 's N';
        } else {
            return '';
        }
    }

    public function getVerbatimLongitude(): string
    {
        if ($this->degreeW > 0 || $this->minuteW > 0 || $this->secondW > 0) {
            return $this->degreeW . "d " . (($this->minuteW) ?: '?') . "m " . (($this->secondW) ?: '?') . 's W';
        } elseif ($this->degreeE > 0 || $this->minuteE > 0 || $this->secondE > 0) {
            return $this->degreeE . "d " . (($this->minuteE) ?: '?') . "m " . (($this->secondE) ?: '?') . 's E';
        } else {
            return '';
        }
    }

    public function getHemisphereLatitude(): ?string
    {
        if (!empty($this->degreeS) || !empty($this->minuteS) || !empty($this->secondS)) {
            return 'S';
        } elseif (!empty($this->degreeN) || !empty($this->minuteN) || !empty($this->secondN)) {
            return 'N';
        } else {
            return null;
        }
    }

    public function getHemisphereLongitude(): ?string
    {
        if (!empty($this->degreeW) || !empty($this->minuteW) || !empty($this->secondW)) {
            return 'W';
        } elseif (!empty($this->degreeE) || !empty($this->minuteE) || !empty($this->secondE)) {
            return 'E';
        } else {
            return null;
        }
    }

    public function getAnnotation(bool $replaceNL2BR = false): ?string
    {
        if ($replaceNL2BR && $this->annotation !== null) {
            return nl2br($this->annotation);
        }
        return $this->annotation;
    }

    /**
     * @return Collection<int, StableIdentifier>
     */
    public function getVisibleStableIdentifiers(): Collection
    {
        $criteria = Criteria::create(true)
            ->where(Criteria::expr()->eq('visible', true))
            ->orderBy(['createdAt' => Order::Ascending]);

        return $this->stableIdentifiers->matching($criteria);
    }

    /**
     * @todo
     * problematic, as the PID could be assigned later - do not use this function untile necessary
     */
    public function getMainStableIdentifier(): ?StableIdentifier
    {
        $identifiers = $this->getVisibleStableIdentifiers();
        return $identifiers->first() ?: null;
    }

    public function getCollectorsTeam(): string
    {
        $collectorTeam = $this->collector->name;
        $secondCollector = $this->collector2;
        if ($secondCollector !== null && (strstr($secondCollector->name, "et al.") || strstr($secondCollector->name, "alii"))) {
            $collectorTeam .= " et al.";
        } elseif ($secondCollector !== null) {
            $parts = explode(',', $secondCollector->name);           // some people forget the final "&"
            if (count($parts) > 2) {                            // so we have to use an alternative way
                $collectorTeam .= ", " . $secondCollector->name;
            } else {
                $collectorTeam .= " & " . $secondCollector->name;
            }
        }
        return $collectorTeam;
    }

    public function getDatesAsString(): string
    {

        if ($this->getDate() === "s.d.") {
            return '';
        }
        if ($this->getDate() === null) {
            return (string)$this->getDate2();
        }

        $created = $this->getDate();
        if (!empty($this->getDate2())) {
            $created .= " - " . $this->getDate2();
        }
        return $created;
    }

    public function getDate(): ?string
    {
        return $this->date !== null ? trim($this->date) : null;
    }

    public function getDate2(): ?string
    {
        return $this->date2 !== null ? trim($this->date2) : null;
    }

    public function getBasisOfRecordField(): string
    {
        return $this->observation ? "HumanObservation" : "PreservedSpecimen";
    }

    public function hasRelatedSpecimens(): bool
    {
        if ($this->getAllDirectRelations()->isEmpty()) {
            return false;
        }
        return true;
    }

    /**
     * @return Collection<int, SpecimenLink>
     */
    public function getAllDirectRelations(): Collection
    {
        $merged = array_merge(
            $this->outgoingRelations->toArray(),
            $this->incomingRelations->toArray()
        );

        usort(
            $merged,
            fn (SpecimenLink $a, SpecimenLink $b)
             => ($a->linkQualifier->name ?? '') <=> ($b->linkQualifier->name ?? '')
        );

        return new ArrayCollection($merged);
    }

}
