<?php

declare(strict_types=1);

namespace JACQ\Tests\Entity\Jacq\Herbarinput;

use JACQ\Entity\Jacq\Herbarinput\Authors;
use JACQ\Entity\Jacq\Herbarinput\Epithet;
use JACQ\Entity\Jacq\Herbarinput\Genus;
use JACQ\Entity\Jacq\Herbarinput\Species;
use PHPUnit\Framework\TestCase;

class SpeciesTest extends TestCase
{
    private Species $species;

    protected function setUp(): void
    {
        $this->species = new Species();
        // Initialize all nullable properties to avoid "must not be accessed before initialization" errors
        $this->setPropertyValue($this->species, 'epithetSpecies', null);
        $this->setPropertyValue($this->species, 'epithetSubspecies', null);
        $this->setPropertyValue($this->species, 'epithetVariety', null);
        $this->setPropertyValue($this->species, 'epithetSubvariety', null);
        $this->setPropertyValue($this->species, 'epithetForma', null);
        $this->setPropertyValue($this->species, 'epithetSubforma', null);
        $this->setPropertyValue($this->species, 'authorSpecies', null);
        $this->setPropertyValue($this->species, 'authorSubspecies', null);
        $this->setPropertyValue($this->species, 'authorVariety', null);
        $this->setPropertyValue($this->species, 'authorSubvariety', null);
        $this->setPropertyValue($this->species, 'authorForma', null);
        $this->setPropertyValue($this->species, 'authorSubforma', null);
        $this->setPropertyValue($this->species, 'validName', null);
    }

    public function testIsSynonymReturnsFalseWhenNoValidName(): void
    {
        $this->assertFalse($this->species->isSynonym());
    }

    public function testIsSynonymReturnsTrueWhenValidNameIsSet(): void
    {
        $validName = new Species();
        $this->setPropertyValue($validName, 'id', 1);
        $this->setPropertyValue($this->species, 'validName', $validName);

        $this->assertTrue($this->species->isSynonym());
    }

    public function testGetFullNameReturnsGenusOnly(): void
    {
        $genus = new Genus();
        $this->setPropertyValue($genus, 'name', 'Rosa');
        $this->setPropertyValue($this->species, 'genus', $genus);

        $this->assertSame('<i>Rosa</i>', $this->species->getFullName(true));
    }

    public function testGetFullNameReturnsGenusAndSpecies(): void
    {
        $genus = new Genus();
        $this->setPropertyValue($genus, 'name', 'Rosa');
        $this->setPropertyValue($this->species, 'genus', $genus);

        $epithet = new Epithet();
        $this->setPropertyValue($epithet, 'name', 'canina');
        $this->setPropertyValue($this->species, 'epithetSpecies', $epithet);

        $author = new Authors();
        $this->setPropertyValue($author, 'name', 'L.');
        $this->setPropertyValue($this->species, 'authorSpecies', $author);

        $this->assertSame('<i>Rosa</i> <i>canina</i> L.', $this->species->getFullName(true));
    }

    public function testGetFullNameReturnsFullNameWithSubspecies(): void
    {
        $genus = new Genus();
        $this->setPropertyValue($genus, 'name', 'Rosa');
        $this->setPropertyValue($this->species, 'genus', $genus);

        $epithetSpecies = new Epithet();
        $this->setPropertyValue($epithetSpecies, 'name', 'canina');
        $this->setPropertyValue($this->species, 'epithetSpecies', $epithetSpecies);

        $authorSpecies = new Authors();
        $this->setPropertyValue($authorSpecies, 'name', 'L.');
        $this->setPropertyValue($this->species, 'authorSpecies', $authorSpecies);

        $epithetSubspecies = new Epithet();
        $this->setPropertyValue($epithetSubspecies, 'name', 'lutetiana');
        $this->setPropertyValue($this->species, 'epithetSubspecies', $epithetSubspecies);

        $authorSubspecies = new Authors();
        $this->setPropertyValue($authorSubspecies, 'name', 'Dupont');
        $this->setPropertyValue($this->species, 'authorSubspecies', $authorSubspecies);

        $this->assertSame(
            '<i>Rosa</i> <i>canina</i> L. subsp. <i>lutetiana</i> Dupont',
            $this->species->getFullName(true)
        );
    }

    public function testGetFullNameReturnsFullNameWithVariety(): void
    {
        $genus = new Genus();
        $this->setPropertyValue($genus, 'name', 'Rosa');
        $this->setPropertyValue($this->species, 'genus', $genus);

        $epithetSpecies = new Epithet();
        $this->setPropertyValue($epithetSpecies, 'name', 'canina');
        $this->setPropertyValue($this->species, 'epithetSpecies', $epithetSpecies);

        $authorSpecies = new Authors();
        $this->setPropertyValue($authorSpecies, 'name', 'L.');
        $this->setPropertyValue($this->species, 'authorSpecies', $authorSpecies);

        $epithetVariety = new Epithet();
        $this->setPropertyValue($epithetVariety, 'name', 'alba');
        $this->setPropertyValue($this->species, 'epithetVariety', $epithetVariety);

        $authorVariety = new Authors();
        $this->setPropertyValue($authorVariety, 'name', 'Mill.');
        $this->setPropertyValue($this->species, 'authorVariety', $authorVariety);

        $this->assertSame(
            '<i>Rosa</i> <i>canina</i> L. var. <i>alba</i> Mill.',
            $this->species->getFullName(true)
        );
    }

    public function testGetFullNameReturnsFullNameWithSubvariety(): void
    {
        $genus = new Genus();
        $this->setPropertyValue($genus, 'name', 'Rosa');
        $this->setPropertyValue($this->species, 'genus', $genus);

        $epithetSpecies = new Epithet();
        $this->setPropertyValue($epithetSpecies, 'name', 'canina');
        $this->setPropertyValue($this->species, 'epithetSpecies', $epithetSpecies);

        $authorSpecies = new Authors();
        $this->setPropertyValue($authorSpecies, 'name', 'L.');
        $this->setPropertyValue($this->species, 'authorSpecies', $authorSpecies);

        $epithetSubvariety = new Epithet();
        $this->setPropertyValue($epithetSubvariety, 'name', 'major');
        $this->setPropertyValue($this->species, 'epithetSubvariety', $epithetSubvariety);

        $authorSubvariety = new Authors();
        $this->setPropertyValue($authorSubvariety, 'name', 'Christ');
        $this->setPropertyValue($this->species, 'authorSubvariety', $authorSubvariety);

        $this->assertSame(
            '<i>Rosa</i> <i>canina</i> L. subvar. <i>major</i> Christ',
            $this->species->getFullName(true)
        );
    }

    public function testGetFullNameReturnsFullNameWithForma(): void
    {
        $genus = new Genus();
        $this->setPropertyValue($genus, 'name', 'Rosa');
        $this->setPropertyValue($this->species, 'genus', $genus);

        $epithetSpecies = new Epithet();
        $this->setPropertyValue($epithetSpecies, 'name', 'canina');
        $this->setPropertyValue($this->species, 'epithetSpecies', $epithetSpecies);

        $authorSpecies = new Authors();
        $this->setPropertyValue($authorSpecies, 'name', 'L.');
        $this->setPropertyValue($this->species, 'authorSpecies', $authorSpecies);

        $epithetForma = new Epithet();
        $this->setPropertyValue($epithetForma, 'name', ' flore-pleno');
        $this->setPropertyValue($this->species, 'epithetForma', $epithetForma);

        $authorForma = new Authors();
        $this->setPropertyValue($authorForma, 'name', 'Voss');
        $this->setPropertyValue($this->species, 'authorForma', $authorForma);

        $this->assertSame(
            '<i>Rosa</i> <i>canina</i> L. forma <i> flore-pleno</i> Voss',
            $this->species->getFullName(true)
        );
    }

    public function testGetFullNameReturnsFullNameWithSubforma(): void
    {
        $genus = new Genus();
        $this->setPropertyValue($genus, 'name', 'Rosa');
        $this->setPropertyValue($this->species, 'genus', $genus);

        $epithetSpecies = new Epithet();
        $this->setPropertyValue($epithetSpecies, 'name', 'canina');
        $this->setPropertyValue($this->species, 'epithetSpecies', $epithetSpecies);

        $authorSpecies = new Authors();
        $this->setPropertyValue($authorSpecies, 'name', 'L.');
        $this->setPropertyValue($this->species, 'authorSpecies', $authorSpecies);

        $epithetSubforma = new Epithet();
        $this->setPropertyValue($epithetSubforma, 'name', 'minor');
        $this->setPropertyValue($this->species, 'epithetSubforma', $epithetSubforma);

        $authorSubforma = new Authors();
        $this->setPropertyValue($authorSubforma, 'name', 'Gremli');
        $this->setPropertyValue($this->species, 'authorSubforma', $authorSubforma);

        $this->assertSame(
            '<i>Rosa</i> <i>canina</i> L. subforma <i>minor</i> Gremli',
            $this->species->getFullName(true)
        );
    }

    public function testGetFullNameReturnsHtmlWhenRequested(): void
    {
        $genus = new Genus();
        $this->setPropertyValue($genus, 'name', 'Rosa');
        $this->setPropertyValue($this->species, 'genus', $genus);

        $epithet = new Epithet();
        $this->setPropertyValue($epithet, 'name', 'canina');
        $this->setPropertyValue($this->species, 'epithetSpecies', $epithet);

        $author = new Authors();
        $this->setPropertyValue($author, 'name', 'L.');
        $this->setPropertyValue($this->species, 'authorSpecies', $author);

        $this->assertSame('<i>Rosa</i> <i>canina</i> L.', $this->species->getFullName(true));
    }

    public function testGetFullNameReturnsPlainTextWhenHtmlIsFalse(): void
    {
        $genus = new Genus();
        $this->setPropertyValue($genus, 'name', 'Rosa');
        $this->setPropertyValue($this->species, 'genus', $genus);

        $epithet = new Epithet();
        $this->setPropertyValue($epithet, 'name', 'canina');
        $this->setPropertyValue($this->species, 'epithetSpecies', $epithet);

        $author = new Authors();
        $this->setPropertyValue($author, 'name', 'L.');
        $this->setPropertyValue($this->species, 'authorSpecies', $author);

        $this->assertSame('Rosa canina L.', $this->species->getFullName(false));
    }

    public function testGetInfraEpithetReturnsEmptyArrayWhenNoInfraEpithet(): void
    {
        $result = $this->species->getInfraEpithet();

        $this->assertSame(['author' => '', 'epithet' => ''], $result);
    }

    public function testGetInfraEpithetReturnsSubforma(): void
    {
        $epithetSubforma = new Epithet();
        $this->setPropertyValue($epithetSubforma, 'name', 'minor');
        $this->setPropertyValue($this->species, 'epithetSubforma', $epithetSubforma);

        $authorSubforma = new Authors();
        $this->setPropertyValue($authorSubforma, 'name', 'Gremli');
        $this->setPropertyValue($this->species, 'authorSubforma', $authorSubforma);

        $result = $this->species->getInfraEpithet();

        $this->assertSame(['author' => 'Gremli', 'epithet' => 'minor'], $result);
    }

    public function testGetInfraEpithetReturnsForma(): void
    {
        $epithetForma = new Epithet();
        $this->setPropertyValue($epithetForma, 'name', 'flore-pleno');
        $this->setPropertyValue($this->species, 'epithetForma', $epithetForma);

        $authorForma = new Authors();
        $this->setPropertyValue($authorForma, 'name', 'Voss');
        $this->setPropertyValue($this->species, 'authorForma', $authorForma);

        $result = $this->species->getInfraEpithet();

        $this->assertSame(['author' => 'Voss', 'epithet' => 'flore-pleno'], $result);
    }

    public function testGetInfraEpithetReturnsSubvariety(): void
    {
        $epithetSubvariety = new Epithet();
        $this->setPropertyValue($epithetSubvariety, 'name', 'major');
        $this->setPropertyValue($this->species, 'epithetSubvariety', $epithetSubvariety);

        $authorSubvariety = new Authors();
        $this->setPropertyValue($authorSubvariety, 'name', 'Christ');
        $this->setPropertyValue($this->species, 'authorSubvariety', $authorSubvariety);

        $result = $this->species->getInfraEpithet();

        $this->assertSame(['author' => 'Christ', 'epithet' => 'major'], $result);
    }

    public function testGetInfraEpithetReturnsVariety(): void
    {
        $epithetVariety = new Epithet();
        $this->setPropertyValue($epithetVariety, 'name', 'alba');
        $this->setPropertyValue($this->species, 'epithetVariety', $epithetVariety);

        $authorVariety = new Authors();
        $this->setPropertyValue($authorVariety, 'name', 'Mill.');
        $this->setPropertyValue($this->species, 'authorVariety', $authorVariety);

        $result = $this->species->getInfraEpithet();

        $this->assertSame(['author' => 'Mill.', 'epithet' => 'alba'], $result);
    }

    public function testGetInfraEpithetReturnsSubspecies(): void
    {
        $epithetSubspecies = new Epithet();
        $this->setPropertyValue($epithetSubspecies, 'name', 'lutetiana');
        $this->setPropertyValue($this->species, 'epithetSubspecies', $epithetSubspecies);

        $authorSubspecies = new Authors();
        $this->setPropertyValue($authorSubspecies, 'name', 'Dupont');
        $this->setPropertyValue($this->species, 'authorSubspecies', $authorSubspecies);

        $result = $this->species->getInfraEpithet();

        $this->assertSame(['author' => 'Dupont', 'epithet' => 'lutetiana'], $result);
    }

    public function testIsHybridReturnsFalseWhenStatusIsNotOne(): void
    {
        $this->setPropertyValue($this->species, 'status', 0);
        $this->setPropertyValue($this->species, 'epithetSpecies', null);
        $this->setPropertyValue($this->species, 'authorSpecies', null);

        $this->assertFalse($this->species->isHybrid());
    }

    public function testIsHybridReturnsFalseWhenEpithetSpeciesIsSet(): void
    {
        $this->setPropertyValue($this->species, 'status', 1);
        $epithet = new Epithet();
        $this->setPropertyValue($epithet, 'name', 'hybrida');
        $this->setPropertyValue($this->species, 'epithetSpecies', $epithet);
        $this->setPropertyValue($this->species, 'authorSpecies', null);

        $this->assertFalse($this->species->isHybrid());
    }

    public function testIsHybridReturnsFalseWhenAuthorSpeciesIsSet(): void
    {
        $this->setPropertyValue($this->species, 'status', 1);
        $this->setPropertyValue($this->species, 'epithetSpecies', null);
        $author = new Authors();
        $this->setPropertyValue($author, 'name', 'Hybrid');
        $this->setPropertyValue($this->species, 'authorSpecies', $author);

        $this->assertFalse($this->species->isHybrid());
    }

    public function testIsHybridReturnsTrueWhenStatusIsOneAndNoEpithetOrAuthor(): void
    {
        $this->setPropertyValue($this->species, 'status', 1);
        $this->setPropertyValue($this->species, 'epithetSpecies', null);
        $this->setPropertyValue($this->species, 'authorSpecies', null);

        $this->assertTrue($this->species->isHybrid());
    }

    private function setPropertyValue(object $object, string $propertyName, mixed $value): void
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setValue($object, $value);
    }
}
