<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Contracts;

use Ihorrud\Counter\Entities\Log;

interface Writer
{
    public function write(Log $log): void;
}
