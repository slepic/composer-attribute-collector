<?php

/*
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace olvlvl\ComposerAttributeCollector\Filter;

use Composer\IO\IOInterface;
use olvlvl\ComposerAttributeCollector\Filter;

use function str_starts_with;

final class IncludePathFilter implements Filter
{
    /**
     * @param string[] $matches
     */
    public function __construct(
        private string $basePath,
        private array $matches,
    ) {
    }

    public function filter(string $filepath, string $class, IOInterface $io): bool
    {
        if (\count($this->matches) === 0) {
            return true;
        }

        foreach ($this->matches as $match) {
            $basePath = str_starts_with($match, '/') ? $match : "{$this->basePath}/$match";
            if (str_starts_with($filepath, $basePath)) {
                return true;
            }
        }

        $io->debug("Discarding '$class' because its path is not on include list");
        return false;
    }
}