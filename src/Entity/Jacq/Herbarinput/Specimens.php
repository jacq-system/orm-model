<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use JACQ\Entity\Jacq\GbifPilot\EuropeanaImages;
use JACQ\Entity\Jacq\HerbarPictures\PhaidraCache;
use JACQ\Repository\Herbarinput\SpecimensRepository;

#[ORM\Entity(repositoryClass: SpecimensRepository::class)]
#[ORM\Table(name: 'tbl_specimens', schema: 'herbarinput')]
class Specimens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'specimen_ID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'Nummer')]
    protected(set) ?int $number = null;

    #[ORM\Column(name: 'altitude_min')]
    protected(set) ?int $altitudeMin = null;
    #[ORM\Column(name: 'altitude_max')]
    protected(set) ?int $altitudeMax = null;

    #[ORM\Column(name: 'HerbNummer')]
    protected(set) ?string $herbNumber = null;

    #[ORM\Column(name: 'alt_number')]
    protected(set) ?string $altNumber = null;

    #[ORM\Column(name: 'series_number')]
    protected(set) ?string $seriesNumber;

    #[ORM\Column(name: 'CollNummer')]
    protected(set) ?string $collectionNumber = null;

    #[ORM\Column(name: 'observation')]
    protected(set) ?bool $observation;

    #[ORM\Column(name: 'accessible')]
    protected(set) bool $accessibleForPublic;

    #[ORM\Column(name: 'Datum')]
    protected(set) ?string $date;

    #[ORM\Column(name: 'Datum2')]
    protected(set) ?string $date2;

    #[ORM\Column(name: 'Fundort')]
    protected(set) ?string $locality = null;

    #[ORM\Column(name: 'Fundort_engl')]
    protected(set) ?string $localityEng;

    #[ORM\Column(name: 'habitus')]
    protected(set) ?string $habitus = null;

    #[ORM\Column(name: 'det')]
    protected(set) ?string $determination = null;

    #[ORM\Column(name: 'habitat')]
    protected(set) ?string $habitat;

    #[ORM\Column(name: 'Bemerkungen')]
    protected(set) ?string $annotation;

    #[ORM\Column(name: 'digital_image')]
    protected(set) ?bool $image;

    #[ORM\Column(name: 'digital_image_obs')]
    protected(set) ?bool $imageObservation;

    #[ORM\Column(name: 'taxon_alt')]
    protected(set) ?string $taxonAlternative;

    #[ORM\Column(name: 'Coord_S')]
    protected(set) ?int $degreeS;

    #[ORM\Column(name: 'S_Min')]
    protected(set) ?int $minuteS;

    #[ORM\Column(name: 'S_Sec')]
    protected(set) ?float $secondS;

    #[ORM\Column(name: 'Coord_N')]
    protected(set) ?int $degreeN;

    #[ORM\Column(name: 'N_Min')]
    protected(set) ?int $minuteN;

    #[ORM\Column(name: 'N_Sec')]
    protected(set) ?float $secondN;

    #[ORM\Column(name: 'Coord_W')]
    protected(set) ?int $degreeW;

    #[ORM\Column(name: 'W_Min')]
    protected(set) ?int $minuteW;

    #[ORM\Column(name: 'W_Sec')]
    protected(set) ?float $secondW;

    #[ORM\Column(name: 'Coord_E')]
    protected(set) ?int $degreeE;

    #[ORM\Column(name: 'E_Min')]
    protected(set) ?int $minuteE;

    #[ORM\Column(name: 'E_Sec')]
    protected(set) ?float $secondE;

    #[ORM\Column(name: 'ncbi_accession')]
    protected(set) ?string $ncbiAccession;

    #[ORM\Column(name: 'typified')]
    protected(set) ?string $typified;

    #[ORM\Column(name: 'garten')]
    protected(set) ?string $garden;

    #[ORM\Column(name: 'Bezirk')]
    protected(set) ?string $region;

    #[ORM\Column(name: 'quadrant')]
    protected(set) ?int $quadrant;

    #[ORM\Column(name: 'quadrant_sub')]
    protected(set) ?int $quadrantSub;

    #[ORM\Column(name: 'exactness')]
    protected(set) ?float $exactness;

    #[ORM\Column(name: 'DiSSCo_ID', type: 'string')]
    protected(set) ?string $pidDissco;
    #[ORM\Column(name: 'GBIF_ID', type: 'string')]
    protected(set) ?string $pidGbif;

    #[ORM\ManyToOne(targetEntity: HerbCollection::class)]
    #[ORM\JoinColumn(name: 'collectionID', referencedColumnName: 'collectionID')]
    protected(set) HerbCollection $herbCollection;

    #[ORM\ManyToOne(targetEntity: Series::class)]
    #[ORM\JoinColumn(name: 'seriesID', referencedColumnName: 'seriesID')]
    protected(set) ?Series $series = null;

    #[ORM\ManyToOne(targetEntity: Collector::class)]
    #[ORM\JoinColumn(name: 'SammlerID', referencedColumnName: 'SammlerID')]
    protected(set) ?Collector $collector = null;
    #[ORM\ManyToOne(targetEntity: Collector2::class)]
    #[ORM\JoinColumn(name: 'Sammler_2ID', referencedColumnName: 'Sammler_2ID')]
    protected(set) ?Collector2 $collector2 = null;

    #[ORM\OneToMany(targetEntity: Typus::class, mappedBy: 'specimen')]
    #[ORM\OrderBy(["date" => "DESC"])]
    protected(set) Collection $typus;

    /**
     * @note https://github.com/jacq-system/jacq-legacy/issues/4, this col should be removed in favor of 1:M relation
     */
    #[ORM\Column(name: 'typusID')]
    protected(set) ?bool $isTypus;

    #[ORM\OneToMany(targetEntity: StableIdentifier::class, mappedBy: 'specimen')]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    protected(set) Collection $stableIdentifiers;

    #[ORM\OneToOne(targetEntity: PhaidraCache::class, mappedBy: 'specimen')]
    protected(set) ?PhaidraCache $phaidraImages = null;

    #[ORM\OneToOne(targetEntity: EuropeanaImages::class, mappedBy: 'specimen')]
    protected(set) ?EuropeanaImages $europeanaImages = null;

    #[ORM\ManyToOne(targetEntity: Species::class, inversedBy: 'specimens')]
    #[ORM\JoinColumn(name: 'taxonID', referencedColumnName: 'taxonID')]
    protected(set) Species $species;

    #[ORM\ManyToOne(targetEntity: Province::class)]
    #[ORM\JoinColumn(name: 'provinceID', referencedColumnName: 'provinceID')]
    protected(set) ?Province $province = null;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    #[ORM\JoinColumn(name: 'NationID', referencedColumnName: 'NationID')]
    protected(set) ?Country $country = null;

    #[ORM\ManyToOne(targetEntity: IdentificationStatus::class)]
    #[ORM\JoinColumn(name: 'identstatusID', referencedColumnName: 'identstatusID')]
    protected(set) ?IdentificationStatus $identificationStatus;

    #[ORM\ManyToOne(targetEntity: SpecimenVoucherType::class)]
    #[ORM\JoinColumn(name: 'voucherID', referencedColumnName: 'voucherID')]
    protected(set) ?SpecimenVoucherType $voucher;

    #[ORM\OneToMany(targetEntity: SpecimenLink::class, mappedBy: 'specimen1')]
    protected(set) Collection $outgoingRelations;

    #[ORM\OneToMany(targetEntity: SpecimenLink::class, mappedBy: 'specimen2')]
    protected(set) Collection $incomingRelations;


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
        } else if ($this->degreeN > 0 || $this->minuteN > 0 || $this->secondN > 0) {
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
        } else if ($this->degreeE > 0 || $this->minuteE > 0 || $this->secondE > 0) {
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
        } else if ($this->degreeN > 0 || $this->minuteN > 0 || $this->secondN > 0) {
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
        } else if ($this->degreeE > 0 || $this->minuteE > 0 || $this->secondE > 0) {
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
        } else if ($this->degreeN > 0 || $this->minuteN > 0 || $this->secondN > 0) {
            return $this->degreeN . "d " . (($this->minuteN) ?: '?') . "m " . (($this->secondN) ?: '?') . 's N';
        } else {
            return '';
        }
    }

    public function getVerbatimLongitude(): string
    {
        if ($this->degreeW > 0 || $this->minuteW > 0 || $this->secondW > 0) {
            return $this->degreeW . "d " . (($this->minuteW) ?: '?') . "m " . (($this->secondW) ?: '?') . 's W';
        } else if ($this->degreeE > 0 || $this->minuteE > 0 || $this->secondE > 0) {
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

    public function getVisibleStableIdentifiers(): Collection
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('visible', true));

        return $this->stableIdentifiers->matching($criteria);
    }

    /**
     * @todo
     * problematic, as the PID could be assigned later - do not use this function untile necessary
     */
    public function getMainStableIdentifier(): ?StableIdentifier
    {
        if (count($this->stableIdentifiers) > 0) {
            return $this->stableIdentifiers[0];
        }
        return null;
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
     * @return Collection|SpecimenLink[]
     */
    public function getAllDirectRelations(): Collection
    {
        $merged = array_merge($this->outgoingRelations->toArray(), $this->incomingRelations->toArray());

        usort($merged, fn(SpecimenLink $a, SpecimenLink $b) => $a->linkQualifier?->name ?? 1 <=> $b->linkQualifier?->name ?? 1);

        return new ArrayCollection($merged);
    }

}
