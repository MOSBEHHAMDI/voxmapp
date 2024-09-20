<?php

declare(strict_types=1);

namespace App\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;

final class Citext extends TextType
{
    const CITEXT = 'citext';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::CITEXT;
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDoctrineTypeMapping(self::CITEXT);
    }
}