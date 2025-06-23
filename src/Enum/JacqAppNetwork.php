<?php declare(strict_types=1);

namespace JACQ\Enum;

enum JacqAppNetwork: string
{
    case Output = 'https://jacq.org';
    case Services = 'https://services.jacq.org/jacq-services/rest';
    case Api = 'https://api.jacq.org';

}
