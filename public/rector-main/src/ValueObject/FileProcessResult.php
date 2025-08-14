<?php

declare (strict_types=1);
namespace Rector\Core\ValueObject;

use PHPStan\Collectors\CollectedData;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use RectorPrefix202309\Webmozart\Assert\Assert;
final readonly class FileProcessResult
{
    /**
     * @param SystemError[] $systemErrors
     * @param CollectedData[] $collectedDatas
     */
    public function __construct(/**
     * @readonly
     */
    private array $systemErrors, /**
     * @readonly
     */
    private ?FileDiff $fileDiff, /**
     * @readonly
     */
    private array $collectedDatas)
    {
        Assert::allIsInstanceOf($systemErrors, SystemError::class);
        Assert::allIsInstanceOf($collectedDatas, CollectedData::class);
    }
    /**
     * @return SystemError[]
     */
    public function getSystemErrors() : array
    {
        return $this->systemErrors;
    }
    public function getFileDiff() : ?FileDiff
    {
        return $this->fileDiff;
    }
    /**
     * @return CollectedData[]
     */
    public function getCollectedData() : array
    {
        return $this->collectedDatas;
    }
}
