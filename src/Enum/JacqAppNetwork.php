<?php declare(strict_types=1);

namespace JACQ\Enum;

enum JacqAppNetwork: string
{
    case Output = 'https://jacq.org';
    case Services = 'https://api.jacq.org/v1';

}
