<?php declare(strict_types=1);

namespace JACQ\Service;

use Location\Coordinate;
use Location\Factory\CoordinateFactory;

readonly class GeoService extends BaseService
{

    public function decimalToDMS(float $decimal): string
    {
        $degrees = (int)$decimal;
        $minutesDecimal = abs($decimal - $degrees) * 60;
        $minutes = (int)$minutesDecimal;
        $seconds = ($minutesDecimal - $minutes) * 60;

        return $degrees . '° ' . $minutes . "'" . $seconds . '"';
    }


    public function DMSToDecimal(string $decimal): Coordinate
    {
        return CoordinateFactory::fromString($decimal);
    }
}
