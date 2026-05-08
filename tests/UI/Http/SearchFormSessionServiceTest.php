<?php declare(strict_types=1);

namespace JACQ\Tests\UI\Http;

use JACQ\UI\Http\SearchFormSessionService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SearchFormSessionServiceTest extends TestCase
{
    private RequestStack $requestStack;
    private SessionInterface $session;
    private SearchFormSessionService $service;

    protected function setUp(): void
    {
        $this->session = $this->createMock(SessionInterface::class);
        $this->requestStack = new RequestStack();
        
        $request = new Request();
        $request->setSession($this->session);
        $this->requestStack->push($request);
        
        $this->service = new SearchFormSessionService($this->requestStack);
    }

    public function testHasFiltersReturnsFalseWhenNoFilters(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_FILTERS ? null : null);

        $this->assertFalse($this->service->hasFilters());
    }

    public function testHasFiltersReturnsTrueWhenFiltersExist(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_FILTERS ? ['key' => 'value'] : null);

        $this->assertTrue($this->service->hasFilters());
    }

    public function testGetFilterReturnsValue(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_FILTERS ? ['key' => 'value'] : null);

        $this->assertSame('value', $this->service->getFilter('key'));
    }

    public function testGetFilterReturnsDefaultWhenKeyNotFound(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_FILTERS ? ['other' => 'value'] : null);

        $this->assertSame('default', $this->service->getFilter('key', 'default'));
    }

    public function testGetSettingReturnsValue(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SETTINGS ? ['key' => 'value'] : null);

        $this->assertSame('value', $this->service->getSetting('key'));
    }

    public function testGetSettingReturnsDefaultWhenKeyNotFound(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SETTINGS ? ['other' => 'value'] : null);

        $this->assertSame('default', $this->service->getSetting('key', 'default'));
    }

    public function testSetSettingStoresValue(): void
    {
        $this->session->expects($this->once())
            ->method('set')
            ->with(SearchFormSessionService::SESSION_SETTINGS, ['key' => 'value']);

        $this->service->setSetting('key', 'value');
    }

    public function testSetSettingReturnsFluentInterface(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SETTINGS ? [] : null);

        $result = $this->service->setSetting('key', 'value');

        $this->assertSame($this->service, $result);
    }

    public function testSetSettingsStoresFormData(): void
    {
        $formData = ['field1' => 'value1', 'field2' => 'value2'];
        
        $this->session->expects($this->once())
            ->method('set')
            ->with(SearchFormSessionService::SESSION_SETTINGS, $formData);

        $this->service->setSettings($formData);
    }

    public function testSetSettingsReturnsFluentInterface(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SETTINGS ? [] : null);

        $result = $this->service->setSettings(['key' => 'value']);

        $this->assertSame($this->service, $result);
    }

    public function testSetSortStoresNewSort(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SORT ? null : null);

        $this->session->expects($this->once())
            ->method('set')
            ->with(SearchFormSessionService::SESSION_SORT, ['name' => 'ASC']);

        $this->service->setSort('name');
    }

    public function testSetSortTogglesToDescWhenAlreadyAsc(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SORT ? ['name' => 'ASC'] : null);

        $this->session->expects($this->once())
            ->method('set')
            ->with(SearchFormSessionService::SESSION_SORT, ['name' => 'DESC']);

        $this->service->setSort('name');
    }

    public function testSetSortRemovesWhenAlreadyDesc(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SORT ? ['name' => 'DESC'] : null);

        $this->session->expects($this->once())
            ->method('remove')
            ->with(SearchFormSessionService::SESSION_SORT);

        $this->service->setSort('name');
    }

    public function testSetSortReturnsFluentInterface(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SORT ? null : null);

        $result = $this->service->setSort('name');

        $this->assertSame($this->service, $result);
    }

    public function testGetSortReturnsSort(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SORT ? ['name' => 'ASC'] : null);

        $this->assertSame(['name' => 'ASC'], $this->service->getSort());
    }

    public function testGetSortReturnsNullWhenNoSort(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SORT ? null : null);

        $this->assertNull($this->service->getSort());
    }

    public function testIsSortedByReturnsTrueWhenSortedByField(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SORT ? ['name' => 'ASC'] : null);

        $this->assertTrue($this->service->isSortedBy('name'));
    }

    public function testIsSortedByReturnsFalseWhenNotSortedByField(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SORT ? ['other' => 'ASC'] : null);

        $this->assertFalse($this->service->isSortedBy('name'));
    }

    public function testHasSortReturnsTrueWhenSortExists(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SORT ? ['name' => 'ASC'] : null);

        $this->assertTrue($this->service->hasSort());
    }

    public function testHasSortReturnsFalseWhenNoSort(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_SORT ? null : null);

        $this->assertFalse($this->service->hasSort());
    }

    public function testResetRemovesAllSessionData(): void
    {
        $this->session->expects($this->exactly(3))
            ->method('remove');

        $this->service->reset();
    }

    public function testResetReturnsFluentInterface(): void
    {
        $this->session->expects($this->exactly(3))
            ->method('remove');

        $result = $this->service->reset();

        $this->assertSame($this->service, $result);
    }

    public function testSetFiltersStoresFilters(): void
    {
        $filters = ['field1' => 'value1', 'field2' => 'value2'];

        $this->session->expects($this->once())
            ->method('set')
            ->with(SearchFormSessionService::SESSION_FILTERS, $filters);

        $this->service->setFilters($filters);
    }

    public function testSetFiltersReturnsFluentInterface(): void
    {
        $this->session->expects($this->any())
            ->method('get')
            ->willReturnCallback(fn(string $key) => $key === SearchFormSessionService::SESSION_FILTERS ? [] : null);

        $result = $this->service->setFilters(['key' => 'value']);

        $this->assertSame($this->service, $result);
    }

    public function testAllReturnsAllSessionData(): void
    {
        $allData = ['key1' => 'value1', 'key2' => 'value2'];

        $this->session->expects($this->once())
            ->method('all')
            ->willReturn($allData);

        $this->assertSame($allData, $this->service->all());
    }
}