<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Uri;

use Ixocreate\Application\Service\SerializableServiceInterface;
use Psr\Http\Message\UriInterface;

final class ApplicationUri implements SerializableServiceInterface
{
    /**
     * @var UriInterface
     */
    private $mainUri;

    /**
     * @var UriInterface[]
     */
    private $alternativeUris;

    /**
     * @var UriInterface[]
     */
    private $possibleUrls;

    public function __construct(ApplicationUriConfigurator $configurator)
    {
        $this->mainUri = $configurator->getMainUri();
        $this->alternativeUris = $configurator->getAlternativeUris();

        $this->possibleUrls = $this->alternativeUris;
        $this->possibleUrls['mainUri'] = $this->mainUri;
    }

    /**
     * @return UriInterface
     */
    public function getMainUri(): UriInterface
    {
        return $this->mainUri;
    }

    /**
     * @return UriInterface
     * @deprecated
     */
    public function getMainUrl(): UriInterface
    {
        return $this->mainUri;
    }

    /**
     * @return UriInterface[]
     */
    public function getAlternativeUris(): array
    {
        return $this->alternativeUris;
    }

    /**
     * @param string $name
     * @return UriInterface
     */
    public function getAlternativeUri(string $name): ?UriInterface
    {
        if (!empty($this->alternativeUris[$name])) {
            return $this->alternativeUris[$name];
        }
        return null;
    }

    /**
     * @return UriInterface[]
     */
    public function getPossibleUrls(): array
    {
        return $this->possibleUrls;
    }

    /**
     * @param string $name
     * @return UriInterface|null
     */
    public function getPossibleUri(string $name): ?UriInterface
    {
        if (!empty($this->possibleUrls[$name])) {
            return $this->possibleUrls[$name];
        }
        return null;
    }

    /**
     * @param UriInterface $uri
     * @return bool
     */
    public function isValidUrl(UriInterface $uri): bool
    {
        foreach ($this->possibleUrls as $possibleUrl) {
            if ($this->isSubUri($possibleUrl, $uri)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param UriInterface $uri
     * @return string
     */
    public function getPathWithoutBase(UriInterface $uri): string
    {
        foreach ($this->possibleUrls as $possibleUrl) {
            if ($this->isSubUri($possibleUrl, $uri)) {
                $pathLength = \mb_strlen($possibleUrl->getPath());
                if ($pathLength > 0) {
                    return \mb_substr($uri->getPath(), $pathLength);
                }

                return $uri->getPath();
            }
        }

        return '';
    }

    private function isSubUri(UriInterface $base, UriInterface $compare)
    {
        if ($compare->getHost() !== $base->getHost()) {
            return false;
        }

        if ($compare->getScheme() !== $base->getScheme()) {
            return false;
        }

        if ($compare->getPort() !== $base->getPort()) {
            return false;
        }

        $pathLength = \mb_strlen($base->getPath());
        if ($pathLength > 0) {
            if (\mb_strlen($compare->getPath()) < $pathLength) {
                return false;
            }

            if (\mb_substr($compare->getPath(), 0, $pathLength) !== $base->getPath()) {
                return false;
            }
        }

        return true;
    }

    public function serialize()
    {
        return \serialize([
            'mainUri' => $this->mainUri,
            'alternativeUris' => $this->alternativeUris,
        ]);
    }

    public function unserialize($serialized)
    {
        $data = \unserialize($serialized);
        $this->mainUri = $data['mainUri'];
        $this->alternativeUris = $data['alternativeUris'];

        $this->possibleUrls = $this->alternativeUris;
        $this->possibleUrls['mainUri'] = $this->mainUri;
    }
}
