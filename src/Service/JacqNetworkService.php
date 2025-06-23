<?php declare(strict_types=1);

namespace JACQ\Service;

use JACQ\Enum\JacqRoutesNetwork;

readonly class JacqNetworkService
{
    public function translateSymfonyToRealServicePath(string $generatedServiceAbsUrl): string
    {
        $parts = parse_url($generatedServiceAbsUrl);
        $parts['host'] = 'services.jacq.org';

        $segments = array_values(array_filter(explode('/', $parts['path'] ?? '')));

        if (isset($segments[0])) {
            $segments[0] = 'jacq-services';
        }

        $parts['path'] = '/' . implode('/', $segments);

        return 'https://' . $parts['host'] . $parts['path'];
    }

    public function generateUrl(JacqRoutesNetwork $app, string $path = '', array $query = []): string
    {
        $url = rtrim($app->value,'/');
        $path = ltrim($path, '/');
        if ($path !== '') {
            $url .= '/' . $path;
        }
        if ($query) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }
}
