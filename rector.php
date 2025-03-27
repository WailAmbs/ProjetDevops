<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    // Niveau de règles avec compatibilité PHP 8.2
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
    ]);

    // Exclure les chemins spécifiques qui ont des problèmes
    $rectorConfig->skip([
        __DIR__ . '/vendor',
        __DIR__ . '/tests',
        __DIR__ . '/admin-login.php',
        __DIR__ . '/includes/config.php',
        __DIR__ . '/src/Validators/IpValidator.php',
        __DIR__ . '/src/Validators/EmailValidator.php',
    ]);

    // Configurer les règles spécifiques
    $rectorConfig->rule(TypedPropertyFromStrictConstructorRector::class);
}; 