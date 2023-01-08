<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Answers;

use App\Domain\Answers\Answer\AnswerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * DoctrineAnswerId
 *
 * @package App\Infrastructure\Persistence\Doctrine\Answers
 */
final class DoctrineAnswerId extends StringType
{
    public const NAME = 'AnswerId';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @inheritDoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value) {
            return (string) $value;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?AnswerId
    {
        if  ($value) {
            return new AnswerId($value);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return [self::NAME];
    }
}