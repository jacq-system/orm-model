<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Sort;

enum SpecimenSortEnum: string
{
    case SCIENTIFIC_NAME = 'sciname';
    case COLLECTOR = 'coll';
    case SERIES = 'ser';
    case COLLECTOR_NUMBER = 'num';
    case HERB_NUMBER = 'herbnr';
    case DATE = 'date';
    case LOCATION = 'location';
    case TYPUS = 'typus';
    case COORDS = 'coords';
}
