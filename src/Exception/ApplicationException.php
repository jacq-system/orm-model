<?php declare(strict_types=1);

namespace JACQ\Exception;

use RuntimeException;

abstract class ApplicationException extends RuntimeException implements ApplicationExceptionInterface
{
}

