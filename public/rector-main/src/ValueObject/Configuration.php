<?php

declare (strict_types=1);
namespace Rector\Core\ValueObject;

use PHPStan\Collectors\CollectedData;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use RectorPrefix202309\Webmozart\Assert\Assert;
final class Configuration
{
    private bool $isSecondRun = \false;
    /**
     * @var CollectedData[]
     */
    private array $collectedData = [];
    /**
     * @param string[] $fileExtensions
     * @param string[] $paths
     */
    public function __construct(
        /**
         * @readonly
         */
        private readonly bool $isDryRun = \false,
        /**
         * @readonly
         */
        private readonly bool $showProgressBar = \true,
        /**
         * @readonly
         */
        private readonly bool $shouldClearCache = \false,
        /**
         * @readonly
         */
        private readonly string $outputFormat = ConsoleOutputFormatter::NAME,
        /**
         * @readonly
         */
        private readonly array $fileExtensions = ['php'],
        /**
         * @readonly
         */
        private readonly array $paths = [],
        /**
         * @readonly
         */
        private readonly bool $showDiffs = \true,
        /**
         * @readonly
         */
        private readonly ?string $parallelPort = null,
        /**
         * @readonly
         */
        private readonly ?string $parallelIdentifier = null,
        /**
         * @readonly
         */
        private readonly bool $isParallel = \false,
        /**
         * @readonly
         */
        private readonly ?string $memoryLimit = null,
        /**
         * @readonly
         */
        private readonly bool $isDebug = \false,
        /**
         * @readonly
         */
        private readonly bool $isCollectors = \false
    )
    {
    }
    public function isDryRun() : bool
    {
        return $this->isDryRun;
    }
    public function shouldShowProgressBar() : bool
    {
        return $this->showProgressBar;
    }
    public function shouldClearCache() : bool
    {
        return $this->shouldClearCache;
    }
    /**
     * @return string[]
     */
    public function getFileExtensions() : array
    {
        Assert::notEmpty($this->fileExtensions);
        return $this->fileExtensions;
    }
    /**
     * @return string[]
     */
    public function getPaths() : array
    {
        return $this->paths;
    }
    public function getOutputFormat() : string
    {
        return $this->outputFormat;
    }
    public function shouldShowDiffs() : bool
    {
        return $this->showDiffs;
    }
    public function getParallelPort() : ?string
    {
        return $this->parallelPort;
    }
    public function getParallelIdentifier() : ?string
    {
        return $this->parallelIdentifier;
    }
    public function isParallel() : bool
    {
        return $this->isParallel;
    }
    public function getMemoryLimit() : ?string
    {
        return $this->memoryLimit;
    }
    public function isDebug() : bool
    {
        return $this->isDebug;
    }
    /**
     * @param CollectedData[] $collectedData
     */
    public function setCollectedData(array $collectedData) : void
    {
        $this->collectedData = $collectedData;
    }
    /**
     * @return CollectedData[]
     */
    public function getCollectedData() : array
    {
        return $this->collectedData;
    }
    /**
     * @api
     */
    public function enableSecondRun() : void
    {
        $this->isSecondRun = \true;
    }
    /**
     * @api
     */
    public function isSecondRun() : bool
    {
        return $this->isSecondRun;
    }
    /**
     * @api used in tests
     */
    public function reset() : void
    {
        $this->isSecondRun = \false;
    }
    /**
     * @api
     */
    public function isCollectors() : bool
    {
        return $this->isCollectors;
    }
}
