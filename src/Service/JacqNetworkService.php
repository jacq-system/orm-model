<?php declare(strict_types=1);

namespace JACQ\Service;

use JACQ\Enum\JacqRoutesNetwork;

readonly class JacqNetworkService
{
    public function generateUrl(JacqRoutesNetwork $app, string $path = '', array $query = []): string
    {
        $url = rtrim($app->value, '/');
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
