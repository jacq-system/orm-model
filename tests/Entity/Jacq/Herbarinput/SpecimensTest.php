<?php declare(strict_types=1);

namespace JACQ\Tests\Entity\Jacq\Herbarinput;

use Doctrine\Common\Collections\ArrayCollection;
use JACQ\Entity\Jacq\Herbarinput\Collector;
use JACQ\Entity\Jacq\Herbarinput\Collector2;
use JACQ\Entity\Jacq\Herbarinput\Country;
use JACQ\Entity\Jacq\Herbarinput\HerbCollection;
use JACQ\Entity\Jacq\Herbarinput\Province;
use JACQ\Entity\Jacq\Herbarinput\Series;
use JACQ\Entity\Jacq\Herbarinput\Species;
use JACQ\Entity\Jacq\Herbarinput\SpecimenLink;
use JACQ\Entity\Jacq\Herbarinput\SpecimenLinkQualifier;
use JACQ\Entity\Jacq\Herbarinput\SpecimenVoucherType;
use JACQ\Entity\Jacq\Herbarinput\Specimens;
use JACQ\Entity\Jacq\Herbarinput\StableIdentifier;
use JACQ\Entity\Jacq\Herbarinput\Typus;
use PHPUnit\Framework\TestCase;

class SpecimensTest extends TestCase
{
    private Specimens $specimen;

    protected function setUp(): void
    {
        $this->specimen = new Specimens();
        // Initialize all nullable properties to avoid "must not be accessed before initialization" errors
        $this->setPropertyValue($this->specimen, 'degreeS', 0);
        $this->setPropertyValue($this->specimen, 'minuteS', 0);
        $this->setPropertyValue($this->specimen, 'secondS', 0.0);
        $this->setPropertyValue($this->specimen, 'degreeN', 0);
        $this->setPropertyValue($this->specimen, 'minuteN', 0);
        $this->setPropertyValue($this->specimen, 'secondN', 0.0);
        $this->setPropertyValue($this->specimen, 'degreeW', 0);
        $this->setPropertyValue($this->specimen, 'minuteW', 0);
        $this->setPropertyValue($this->specimen, 'secondW', 0.0);
        $this->setPropertyValue($this->specimen, 'degreeE', 0);
        $this->setPropertyValue($this->specimen, 'minuteE', 0);
        $this->setPropertyValue($this->specimen, 'secondE', 0.0);
        $this->setPropertyValue($this->specimen, 'annotation', null);
        $this->setPropertyValue($this->specimen, 'observation', false);
        $this->setPropertyValue($this->specimen, 'image', false);
        $this->setPropertyValue($this->specimen, 'imageObservation', false);
        $this->setPropertyValue($this->specimen, 'stableIdentifiers', new ArrayCollection());
        $this->setPropertyValue($this->specimen, 'outgoingRelations', new ArrayCollection());
        $this->setPropertyValue($this->specimen, 'incomingRelations', new ArrayCollection());
    }

    public function testGetImageIconFilenameReturnsObsPngForObservationWithImageObservation(): void
    {
        $this->setPropertyValue($this->specimen, 'observation', true);
        $this->setPropertyValue($this->specimen, 'imageObservation', true);

        $this->assertSame('obs.png', $this->specimen->getImageIconFilename());
    }

    public function testGetImageIconFilenameReturnsObsBwPngForObservationWithoutImageObservation(): void
    {
        $this->setPropertyValue($this->specimen, 'observation', true);
        $this->setPropertyValue($this->specimen, 'image', false);
        $this->setPropertyValue($this->specimen, 'imageObservation', false);

        $this->assertSame('obs_bw.png', $this->specimen->getImageIconFilename());
    }

    public function testGetImageIconFilenameReturnsSpecObsPngForSpecimenObservationWithBothImages(): void
    {
        $this->setPropertyValue($this->specimen, 'observation', false);
        $this->setPropertyValue($this->specimen, 'image', true);
        $this->setPropertyValue($this->specimen, 'imageObservation', true);

        $this->assertSame('spec_obs.png', $this->specimen->getImageIconFilename());
    }

    public function testGetImageIconFilenameReturnsObsPngForSpecimenObservationOnly(): void
    {
        $this->setPropertyValue($this->specimen, 'observation', false);
        $this->setPropertyValue($this->specimen, 'image', false);
        $this->setPropertyValue($this->specimen, 'imageObservation', true);

        $this->assertSame('obs.png', $this->specimen->getImageIconFilename());
    }

    public function testGetImageIconFilenameReturnsCameraPngForSpecimenWithImage(): void
    {
        $this->setPropertyValue($this->specimen, 'observation', false);
        $this->setPropertyValue($this->specimen, 'image', true);
        $this->setPropertyValue($this->specimen, 'imageObservation', false);

        $this->assertSame('camera.png', $this->specimen->getImageIconFilename());
    }

    public function testGetImageIconFilenameReturnsNullForNoImage(): void
    {
        $this->setPropertyValue($this->specimen, 'observation', false);
        $this->setPropertyValue($this->specimen, 'image', false);
        $this->setPropertyValue($this->specimen, 'imageObservation', false);

        $this->assertNull($this->specimen->getImageIconFilename());
    }

    public function testHasCoordsReturnsFalseWhenNoCoordinates(): void
    {
        $this->assertFalse($this->specimen->hasCoords());
    }

    public function testHasCoordsReturnsTrueWhenNorthCoordinates(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeN', 48);
        $this->setPropertyValue($this->specimen, 'minuteN', 10);
        $this->setPropertyValue($this->specimen, 'secondN', 30.0);
        $this->setPropertyValue($this->specimen, 'degreeE', 16);
        $this->setPropertyValue($this->specimen, 'minuteE', 20);
        $this->setPropertyValue($this->specimen, 'secondE', 45.0);

        $this->assertTrue($this->specimen->hasCoords());
    }

    public function testHasCoordsReturnsTrueWhenSouthCoordinates(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeS', 34);
        $this->setPropertyValue($this->specimen, 'minuteS', 5);
        $this->setPropertyValue($this->specimen, 'secondS', 15.0);
        $this->setPropertyValue($this->specimen, 'degreeW', 18);
        $this->setPropertyValue($this->specimen, 'minuteW', 30);
        $this->setPropertyValue($this->specimen, 'secondW', 0.0);

        $this->assertTrue($this->specimen->hasCoords());
    }

    public function testGetCoordsReturnsNullWhenNoCoordinates(): void
    {
        $this->assertNull($this->specimen->getCoords());
    }

    public function testGetCoordsReturnsRoundedCoordinatesByDefault(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeN', 48);
        $this->setPropertyValue($this->specimen, 'minuteN', 12);
        $this->setPropertyValue($this->specimen, 'secondN', 36.0);
        $this->setPropertyValue($this->specimen, 'degreeE', 16);
        $this->setPropertyValue($this->specimen, 'minuteE', 24);
        $this->setPropertyValue($this->specimen, 'secondE', 48.0);

        $this->assertSame('48.21,16.41333', $this->specimen->getCoords());
    }

    public function testGetCoordsReturnsUnroundedCoordinatesWhenRoundIsFalse(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeN', 48);
        $this->setPropertyValue($this->specimen, 'minuteN', 12);
        $this->setPropertyValue($this->specimen, 'secondN', 36.0);
        $this->setPropertyValue($this->specimen, 'degreeE', 16);
        $this->setPropertyValue($this->specimen, 'minuteE', 24);
        $this->setPropertyValue($this->specimen, 'secondE', 48.0);

        $this->assertSame('48.21,16.413333333333', $this->specimen->getCoords(false));
    }

    public function testGetLatitudeReturnsNullWhenNoCoordinates(): void
    {
        $this->assertNull($this->specimen->getLatitude());
    }

    public function testGetLatitudeReturnsPositiveValueForNorth(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeN', 48);
        $this->setPropertyValue($this->specimen, 'minuteN', 30);
        $this->setPropertyValue($this->specimen, 'secondN', 0.0);

        $this->assertEquals(48.5, $this->specimen->getLatitude());
    }

    public function testGetLatitudeReturnsNegativeValueForSouth(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeS', 35);
        $this->setPropertyValue($this->specimen, 'minuteS', 30);
        $this->setPropertyValue($this->specimen, 'secondS', 0.0);

        $this->assertEquals(-35.5, $this->specimen->getLatitude());
    }

    public function testGetLongitudeReturnsNullWhenNoCoordinates(): void
    {
        $this->assertNull($this->specimen->getLongitude());
    }

    public function testGetLongitudeReturnsPositiveValueForEast(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeE', 16);
        $this->setPropertyValue($this->specimen, 'minuteE', 30);
        $this->setPropertyValue($this->specimen, 'secondE', 0.0);

        $this->assertEquals(16.5, $this->specimen->getLongitude());
    }

    public function testGetLongitudeReturnsNegativeValueForWest(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeW', 74);
        $this->setPropertyValue($this->specimen, 'minuteW', 0);
        $this->setPropertyValue($this->specimen, 'secondW', 0.0);

        $this->assertEquals(-74.0, $this->specimen->getLongitude());
    }

    public function testGetDMSCoordsReturnsNullWhenNoCoordinates(): void
    {
        $this->assertNull($this->specimen->getDMSCoords());
    }

    public function testGetDMSCoordsReturnsFormattedCoordinates(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeN', 48);
        $this->setPropertyValue($this->specimen, 'minuteN', 12);
        $this->setPropertyValue($this->specimen, 'secondN', 36.0);
        $this->setPropertyValue($this->specimen, 'degreeE', 16);
        $this->setPropertyValue($this->specimen, 'minuteE', 24);
        $this->setPropertyValue($this->specimen, 'secondE', 48.0);

        $this->assertSame("48°12′36.00″N,16°24′48.00″E", $this->specimen->getDMSCoords());
    }

    public function testGetLatitudeDMSReturnsNullWhenNoCoordinates(): void
    {
        $this->assertNull($this->specimen->getLatitudeDMS());
    }

    public function testGetLatitudeDMSReturnsFormattedNorthLatitude(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeN', 48);
        $this->setPropertyValue($this->specimen, 'minuteN', 12);
        $this->setPropertyValue($this->specimen, 'secondN', 36.5);

        $this->assertSame("48°12′36.50″N", $this->specimen->getLatitudeDMS());
    }

    public function testGetLatitudeDMSReturnsFormattedSouthLatitude(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeS', 35);
        $this->setPropertyValue($this->specimen, 'minuteS', 45);
        $this->setPropertyValue($this->specimen, 'secondS', 15.25);

        $this->assertSame("35°45′15.25″S", $this->specimen->getLatitudeDMS());
    }

    public function testGetLongitudeDMSReturnsNullWhenNoCoordinates(): void
    {
        $this->assertNull($this->specimen->getLongitudeDMS());
    }

    public function testGetLongitudeDMSReturnsFormattedEastLongitude(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeE', 16);
        $this->setPropertyValue($this->specimen, 'minuteE', 30);
        $this->setPropertyValue($this->specimen, 'secondE', 0.0);

        $this->assertSame("16°30′0.00″E", $this->specimen->getLongitudeDMS());
    }

    public function testGetLongitudeDMSReturnsFormattedWestLongitude(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeW', 74);
        $this->setPropertyValue($this->specimen, 'minuteW', 30);
        $this->setPropertyValue($this->specimen, 'secondW', 45.0);

        $this->assertSame("74°30′45.00″W", $this->specimen->getLongitudeDMS());
    }

    public function testGetVerbatimLatitudeReturnsEmptyStringWhenNoCoordinates(): void
    {
        $this->assertSame('', $this->specimen->getVerbatimLatitude());
    }

    public function testGetVerbatimLatitudeReturnsFormattedNorthLatitude(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeN', 48);
        $this->setPropertyValue($this->specimen, 'minuteN', 12);
        $this->setPropertyValue($this->specimen, 'secondN', 36.0);

        $this->assertSame('48d 12m 36s N', $this->specimen->getVerbatimLatitude());
    }

    public function testGetVerbatimLatitudeReturnsFormattedSouthLatitude(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeS', 35);
        $this->setPropertyValue($this->specimen, 'minuteS', 45);
        $this->setPropertyValue($this->specimen, 'secondS', 15.0);

        $this->assertSame('35d 45m 15s S', $this->specimen->getVerbatimLatitude());
    }

    public function testGetVerbatimLongitudeReturnsEmptyStringWhenNoCoordinates(): void
    {
        $this->assertSame('', $this->specimen->getVerbatimLongitude());
    }

    public function testGetVerbatimLongitudeReturnsFormattedEastLongitude(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeE', 16);
        $this->setPropertyValue($this->specimen, 'minuteE', 30);
        $this->setPropertyValue($this->specimen, 'secondE', 45.0);

        $this->assertSame('16d 30m 45s E', $this->specimen->getVerbatimLongitude());
    }

    public function testGetVerbatimLongitudeReturnsFormattedWestLongitude(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeW', 74);
        $this->setPropertyValue($this->specimen, 'minuteW', 30);
        $this->setPropertyValue($this->specimen, 'secondW', 45.0);

        $this->assertSame('74d 30m 45s W', $this->specimen->getVerbatimLongitude());
    }

    public function testGetHemisphereLatitudeReturnsNullWhenNoCoordinates(): void
    {
        $this->assertNull($this->specimen->getHemisphereLatitude());
    }

    public function testGetHemisphereLatitudeReturnsNForNorth(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeN', 48);

        $this->assertSame('N', $this->specimen->getHemisphereLatitude());
    }

    public function testGetHemisphereLatitudeReturnsSForSouth(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeS', 35);

        $this->assertSame('S', $this->specimen->getHemisphereLatitude());
    }

    public function testGetHemisphereLongitudeReturnsNullWhenNoCoordinates(): void
    {
        $this->assertNull($this->specimen->getHemisphereLongitude());
    }

    public function testGetHemisphereLongitudeReturnsEForEast(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeE', 16);

        $this->assertSame('E', $this->specimen->getHemisphereLongitude());
    }

    public function testGetHemisphereLongitudeReturnsWForWest(): void
    {
        $this->setPropertyValue($this->specimen, 'degreeW', 74);

        $this->assertSame('W', $this->specimen->getHemisphereLongitude());
    }

    public function testGetAnnotationReturnsNullWhenNoAnnotation(): void
    {
        $this->assertNull($this->specimen->getAnnotation());
    }

    public function testGetAnnotationReturnsAnnotationAsIs(): void
    {
        $this->setPropertyValue($this->specimen, 'annotation', 'Test annotation');

        $this->assertSame('Test annotation', $this->specimen->getAnnotation());
    }

    public function testGetAnnotationReplacesNewlinesWithBrWhenRequested(): void
    {
        $this->setPropertyValue($this->specimen, 'annotation', "Line 1\nLine 2");

        $this->assertSame("Line 1<br />\nLine 2", $this->specimen->getAnnotation(true));
    }

    public function testGetVisibleStableIdentifiersReturnsOnlyVisible(): void
    {
        $visibleIdentifier = new StableIdentifier();
        $this->setPropertyValue($visibleIdentifier, 'visible', true);
        $this->setPropertyValue($visibleIdentifier, 'specimen', $this->specimen);
        $this->setPropertyValue($visibleIdentifier, 'createdAt', new \DateTimeImmutable());

        $invisibleIdentifier = new StableIdentifier();
        $this->setPropertyValue($invisibleIdentifier, 'visible', false);
        $this->setPropertyValue($invisibleIdentifier, 'specimen', $this->specimen);
        $this->setPropertyValue($invisibleIdentifier, 'createdAt', new \DateTimeImmutable());

        $this->setPropertyValue($this->specimen, 'stableIdentifiers', new ArrayCollection([
            $visibleIdentifier,
            $invisibleIdentifier,
        ]));

        $result = $this->specimen->getVisibleStableIdentifiers();

        $this->assertCount(1, $result);
        $this->assertTrue($result->contains($visibleIdentifier));
        $this->assertFalse($result->contains($invisibleIdentifier));
    }

    public function testGetMainStableIdentifierReturnsFirstVisible(): void
    {
        $firstIdentifier = new StableIdentifier();
        $this->setPropertyValue($firstIdentifier, 'visible', true);
        $this->setPropertyValue($firstIdentifier, 'specimen', $this->specimen);
        $this->setPropertyValue($firstIdentifier, 'createdAt', new \DateTimeImmutable('2020-01-01'));

        $secondIdentifier = new StableIdentifier();
        $this->setPropertyValue($secondIdentifier, 'visible', true);
        $this->setPropertyValue($secondIdentifier, 'specimen', $this->specimen);
        $this->setPropertyValue($secondIdentifier, 'createdAt', new \DateTimeImmutable('2020-02-01'));

        $this->setPropertyValue($this->specimen, 'stableIdentifiers', new ArrayCollection([
            $firstIdentifier,
            $secondIdentifier,
        ]));

        $result = $this->specimen->getMainStableIdentifier();

        $this->assertSame($firstIdentifier, $result);
    }

    public function testGetMainStableIdentifierReturnsNullWhenNoVisibleIdentifiers(): void
    {
        $this->setPropertyValue($this->specimen, 'stableIdentifiers', new ArrayCollection());

        $this->assertNull($this->specimen->getMainStableIdentifier());
    }

    public function testGetCollectorsTeamReturnsCollectorNameOnly(): void
    {
        $collector = new Collector();
        $this->setPropertyValue($collector, 'name', 'Smith, J.');
        $this->setPropertyValue($this->specimen, 'collector', $collector);
        $this->setPropertyValue($this->specimen, 'collector2', null);

        $this->assertSame('Smith, J.', $this->specimen->getCollectorsTeam());
    }

    public function testGetCollectorsTeamAppendsEtAlForSecondCollector(): void
    {
        $collector = new Collector();
        $this->setPropertyValue($collector, 'name', 'Smith, J.');
        $this->setPropertyValue($this->specimen, 'collector', $collector);

        $collector2 = new Collector2();
        $this->setPropertyValue($collector2, 'name', 'Johnson et al.');
        $this->setPropertyValue($this->specimen, 'collector2', $collector2);

        $this->assertSame('Smith, J. et al.', $this->specimen->getCollectorsTeam());
    }

    public function testGetCollectorsTeamAppendsAliiForSecondCollector(): void
    {
        $collector = new Collector();
        $this->setPropertyValue($collector, 'name', 'Smith, J.');
        $this->setPropertyValue($this->specimen, 'collector', $collector);

        $collector2 = new Collector2();
        $this->setPropertyValue($collector2, 'name', 'Johnson alii');
        $this->setPropertyValue($this->specimen, 'collector2', $collector2);

        $this->assertSame('Smith, J. et al.', $this->specimen->getCollectorsTeam());
    }

    public function testGetCollectorsTeamAppendsSecondCollectorWithAmpersand(): void
    {
        $collector = new Collector();
        $this->setPropertyValue($collector, 'name', 'Smith, J.');
        $this->setPropertyValue($this->specimen, 'collector', $collector);

        $collector2 = new Collector2();
        $this->setPropertyValue($collector2, 'name', 'Johnson, K.');
        $this->setPropertyValue($this->specimen, 'collector2', $collector2);

        $this->assertSame('Smith, J. & Johnson, K.', $this->specimen->getCollectorsTeam());
    }

    public function testGetCollectorsTeamAppendsSecondCollectorWithCommaForMultipleParts(): void
    {
        $collector = new Collector();
        $this->setPropertyValue($collector, 'name', 'Smith, J.');
        $this->setPropertyValue($this->specimen, 'collector', $collector);

        $collector2 = new Collector2();
        $this->setPropertyValue($collector2, 'name', 'Johnson, K., Williams, L.');
        $this->setPropertyValue($this->specimen, 'collector2', $collector2);

        $this->assertSame('Smith, J., Johnson, K., Williams, L.', $this->specimen->getCollectorsTeam());
    }

    public function testGetDatesAsStringReturnsEmptyStringForSd(): void
    {
        $this->setPropertyValue($this->specimen, 'date', 's.d.');

        $this->assertSame('', $this->specimen->getDatesAsString());
    }

    public function testGetDatesAsStringReturnsDate2WhenDateIsNull(): void
    {
        $this->setPropertyValue($this->specimen, 'date', null);
        $this->setPropertyValue($this->specimen, 'date2', '2020-01-01');

        $this->assertSame('2020-01-01', $this->specimen->getDatesAsString());
    }

    public function testGetDatesAsStringReturnsDateOnly(): void
    {
        $this->setPropertyValue($this->specimen, 'date', '2020-01-01');
        $this->setPropertyValue($this->specimen, 'date2', null);

        $this->assertSame('2020-01-01', $this->specimen->getDatesAsString());
    }

    public function testGetDatesAsStringReturnsDateAndDate2(): void
    {
        $this->setPropertyValue($this->specimen, 'date', '2020-01-01');
        $this->setPropertyValue($this->specimen, 'date2', '2020-02-01');

        $this->assertSame('2020-01-01 - 2020-02-01', $this->specimen->getDatesAsString());
    }

    public function testGetDateReturnsTrimmedValue(): void
    {
        $this->setPropertyValue($this->specimen, 'date', '  2020-01-01  ');

        $this->assertSame('2020-01-01', $this->specimen->getDate());
    }

    public function testGetDateReturnsNullForNull(): void
    {
        $this->setPropertyValue($this->specimen, 'date', null);

        $this->assertNull($this->specimen->getDate());
    }

    public function testGetDate2ReturnsTrimmedValue(): void
    {
        $this->setPropertyValue($this->specimen, 'date2', '  2020-02-01  ');

        $this->assertSame('2020-02-01', $this->specimen->getDate2());
    }

    public function testGetDate2ReturnsNullForNull(): void
    {
        $this->setPropertyValue($this->specimen, 'date2', null);

        $this->assertNull($this->specimen->getDate2());
    }

    public function testGetBasisOfRecordFieldReturnsHumanObservation(): void
    {
        $this->setPropertyValue($this->specimen, 'observation', true);

        $this->assertSame('HumanObservation', $this->specimen->getBasisOfRecordField());
    }

    public function testGetBasisOfRecordFieldReturnsPreservedSpecimen(): void
    {
        $this->setPropertyValue($this->specimen, 'observation', false);

        $this->assertSame('PreservedSpecimen', $this->specimen->getBasisOfRecordField());
    }

    public function testHasRelatedSpecimensReturnsFalseWhenNoRelations(): void
    {
        $this->setPropertyValue($this->specimen, 'outgoingRelations', new ArrayCollection());
        $this->setPropertyValue($this->specimen, 'incomingRelations', new ArrayCollection());

        $this->assertFalse($this->specimen->hasRelatedSpecimens());
    }

    public function testHasRelatedSpecimensReturnsTrueWhenOutgoingRelations(): void
    {
        $link = new SpecimenLink();
        $this->setPropertyValue($this->specimen, 'outgoingRelations', new ArrayCollection([$link]));
        $this->setPropertyValue($this->specimen, 'incomingRelations', new ArrayCollection());

        $this->assertTrue($this->specimen->hasRelatedSpecimens());
    }

    public function testHasRelatedSpecimensReturnsTrueWhenIncomingRelations(): void
    {
        $this->setPropertyValue($this->specimen, 'outgoingRelations', new ArrayCollection());
        $link = new SpecimenLink();
        $this->setPropertyValue($this->specimen, 'incomingRelations', new ArrayCollection([$link]));

        $this->assertTrue($this->specimen->hasRelatedSpecimens());
    }

    public function testGetAllDirectRelationsReturnsMergedAndSortedRelations(): void
    {
        $qualifier1 = new SpecimenLinkQualifier();
        $this->setPropertyValue($qualifier1, 'name', 'B qualifier');

        $qualifier2 = new SpecimenLinkQualifier();
        $this->setPropertyValue($qualifier2, 'name', 'A qualifier');

        $link1 = new SpecimenLink();
        $this->setPropertyValue($link1, 'linkQualifier', $qualifier1);

        $link2 = new SpecimenLink();
        $this->setPropertyValue($link2, 'linkQualifier', $qualifier2);

        $this->setPropertyValue($this->specimen, 'outgoingRelations', new ArrayCollection([$link1]));
        $this->setPropertyValue($this->specimen, 'incomingRelations', new ArrayCollection([$link2]));

        $result = $this->specimen->getAllDirectRelations();

        $this->assertCount(2, $result);
        $this->assertSame($link2, $result[0]);
        $this->assertSame($link1, $result[1]);
    }

    private function setPropertyValue(object $object, string $propertyName, mixed $value): void
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setValue($object, $value);
    }
}