<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        'D:\xampp\htdocs\public\rector-main\lab\lab\public',
        // ...
    ]);

    $rectorConfig->sets([LevelSetList::UP_TO_PHP_82]);
};