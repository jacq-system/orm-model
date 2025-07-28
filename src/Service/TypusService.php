<?php declare(strict_types=1);

namespace JACQ\Service;


use JACQ\Entity\Jacq\Herbarinput\Species;
use JACQ\Entity\Jacq\Herbarinput\Specimens;
use Doctrine\ORM\EntityManagerInterface;

readonly class TypusService
{
    public function __construct(protected EntityManagerInterface $entityManager, protected SpeciesService $taxonService)
    {
    }

    public function getTypusText(Specimens $specimen): string
    {
        $text = '';
        foreach ($specimen->getTypus() as $typus) {
            $text .= $typus->getRank()->getLatinName() . ' for ' . $this->taxonService->taxonNameWithHybrids($specimen->getSpecies());
            $text .= '';
            foreach ($this->getProtologs($typus->getSpecies()) as $protolog) {
                $text .= $protolog . ' ';
            }
        }
        if ($specimen->getSpecies()->isSynonym()) {
            $text .= "Current Name: " . $this->taxonService->taxonNameWithHybrids($specimen->getSpecies()->getValidName());
        }
        return $text;

    }

    public function getTypusArray(Specimens $specimen, bool $asText = true): array
    {
      $result = [];
        foreach ($specimen->getTypus() as $typus) {
            if($asText) {
                $text = $typus->getRank()->getLatinName() . ' for ' . $this->taxonService->taxonNameWithHybrids($specimen->getSpecies());
                $text .= '';
                foreach ($this->getProtologs($typus->getSpecies()) as $protolog) {
                    $text .= ', '.$protolog . ' ';
                }
                if ($specimen->getSpecies()->isSynonym()) {
                    $text .= "Current Name: " . $this->taxonService->taxonNameWithHybrids($specimen->getSpecies()->getValidName());
                }
                $result[] = $text;
            }
            else{
                $subresult = [];
                $subresult['jacq:typeStatus'] = $typus->getRank()->getLatinName();
                $subresult['jacq:typifiedName'] = $this->taxonService->taxonNameWithHybrids($specimen->getSpecies());
                $subresult['jacq:typeReference'] =$this->getProtologs($typus->getSpecies());
                $subresult['jacq:typeCurrent'] = $this->taxonService->taxonNameWithHybrids($specimen->getSpecies()->getValidName());
                $result[] = $subresult;
            }
        }

        return $result;

    }

    public function getProtologs(Species $species): array
    {
        $text = [];
        $sql = "SELECT l.suptitel, la.autor, l.periodicalID, lp.periodical, l.vol, l.part, ti.paginae, ti.figures, l.jahr
                 FROM tbl_tax_index ti
                  INNER JOIN tbl_lit l ON l.citationID=ti.citationID
                  LEFT JOIN tbl_lit_periodicals lp ON lp.periodicalID=l.periodicalID
                  LEFT JOIN tbl_lit_authors la ON la.autorID=l.editorsID
                 WHERE ti.taxonID=:taxon";
        $result = $this->entityManager->getConnection()->executeQuery($sql, ['taxon' => $species->getId()]);
        while ($row = $result->fetchAssociative()) {
            $text[] = $this->protolog($row);
        }
        return $text;
    }

    protected function protolog($row): string
    {
        $text = "";
        if ($row['suptitel']) {
            $text .= "in " . $row['autor'] . ": " . $row['suptitel'] . " ";
        }
        if ($row['periodicalID']) {
            $text .= $row['periodical'];
        }
        $text .= " " . $row['vol'];
        if ($row['part']) {
            $text .= " (" . $row['part'] . ")";
        }
        $text .= ": " . $row['paginae'];
        if ($row['figures']) {
            $text .= "; " . $row['figures'];
        }
        $text .= " (" . $row['jahr'] . ")";

        return $text;
    }

}
