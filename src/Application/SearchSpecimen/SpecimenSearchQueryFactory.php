<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen;

use Doctrine\ORM\EntityManagerInterface;
use JACQ\Application\SearchSpecimen\Filter\AnnotationFilter;
use JACQ\Application\SearchSpecimen\Filter\CollectionDateFilter;
use JACQ\Application\SearchSpecimen\Filter\CollectionFilter;
use JACQ\Application\SearchSpecimen\Filter\CollectionNrFilter;
use JACQ\Application\SearchSpecimen\Filter\CollectorFilter;
use JACQ\Application\SearchSpecimen\Filter\CollectorNrFilter;
use JACQ\Application\SearchSpecimen\Filter\CountryFilter;
use JACQ\Application\SearchSpecimen\Filter\FamilyFilter;
use JACQ\Application\SearchSpecimen\Filter\HabitatFilter;
use JACQ\Application\SearchSpecimen\Filter\HabitusFilter;
use JACQ\Application\SearchSpecimen\Filter\HasCoordsFilter;
use JACQ\Application\SearchSpecimen\Filter\HasImageFilter;
use JACQ\Application\SearchSpecimen\Filter\HerbNrFilter;
use JACQ\Application\SearchSpecimen\Filter\InstitutionFilter;
use JACQ\Application\SearchSpecimen\Filter\IsTypusFilter;
use JACQ\Application\SearchSpecimen\Filter\LocalityFilter;
use JACQ\Application\SearchSpecimen\Filter\OnlyPublicAvailableFilter;
use JACQ\Application\SearchSpecimen\Filter\ProvinceFilter;
use JACQ\Application\SearchSpecimen\Filter\SeriesFilter;
use JACQ\Application\SearchSpecimen\Filter\TaxonAlternativeFilter;
use JACQ\Application\SearchSpecimen\Filter\TaxonFilter;

final class SpecimenSearchQueryFactory
{
    public function __construct(
        private EntityManagerInterface $em
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
                new IsTypusFilter(),
                new LocalityFilter(),
                new OnlyPublicAvailableFilter(), //!
                new ProvinceFilter(),
                new SeriesFilter(),
                new TaxonAlternativeFilter(),
                new TaxonFilter($this->em)
            ]
        );
    }

}