<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search;

use Doctrine\ORM\EntityManagerInterface;
use JACQ\Application\Specimen\Search\Filter\AnnotationFilter;
use JACQ\Application\Specimen\Search\Filter\CollectionDateFilter;
use JACQ\Application\Specimen\Search\Filter\CollectionFilter;
use JACQ\Application\Specimen\Search\Filter\CollectionNrFilter;
use JACQ\Application\Specimen\Search\Filter\CollectorFilter;
use JACQ\Application\Specimen\Search\Filter\CollectorNrFilter;
use JACQ\Application\Specimen\Search\Filter\CountryFilter;
use JACQ\Application\Specimen\Search\Filter\FamilyFilter;
use JACQ\Application\Specimen\Search\Filter\HabitatFilter;
use JACQ\Application\Specimen\Search\Filter\HabitusFilter;
use JACQ\Application\Specimen\Search\Filter\HasCoordsFilter;
use JACQ\Application\Specimen\Search\Filter\HasImageFilter;
use JACQ\Application\Specimen\Search\Filter\HerbNrFilter;
use JACQ\Application\Specimen\Search\Filter\InstitutionFilter;
use JACQ\Application\Specimen\Search\Filter\IsTypusFilter;
use JACQ\Application\Specimen\Search\Filter\LocalityFilter;
use JACQ\Application\Specimen\Search\Filter\OnlyPublicAvailableFilter;
use JACQ\Application\Specimen\Search\Filter\ProvinceFilter;
use JACQ\Application\Specimen\Search\Filter\SeriesFilter;
use JACQ\Application\Specimen\Search\Filter\TaxonAlternativeFilter;
use JACQ\Application\Specimen\Search\Filter\TaxonFilter;
use JACQ\Service\SpeciesService;

final class SpecimenSearchQueryFactory
{
    public function __construct(
        private EntityManagerInterface $em,
        private SpeciesService $speciesService,
    )
    {
    }

    public function createForPublic(

    ): SpecimenSearchQuery
    {
        return new SpecimenSearchQuery(
            $this->em,
            [
                new AnnotationFilter(),
                new CollectionDateFilter(),
                new CollectionFilter(),
                new CollectionNrFilter(),
                new CollectorFilter($this->em),
                new CollectorNrFilter(),
                new CountryFilter(),
                new FamilyFilter(),
                new HabitatFilter(),
                new HabitusFilter(),
                new HasCoordsFilter(),
                new HasImageFilter(),
                new HerbNrFilter($this->em),
                new InstitutionFilter(),
                new IsTypusFilter($this->em),
                new LocalityFilter(),
                new OnlyPublicAvailableFilter(), //!
                new ProvinceFilter(),
                new SeriesFilter(),
                new TaxonAlternativeFilter(),
                new TaxonFilter($this->speciesService)
            ]
        );
    }

}