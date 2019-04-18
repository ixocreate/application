<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Uri;

use Ixocreate\Application\ConfiguratorInterface;
use Ixocreate\Application\ServiceRegistryInterface;
use Psr\Http\Message\UriInterface;
use Zend\Diactoros\Uri;

final class ApplicationUriConfigurator implements ConfiguratorInterface
{
    /**
     * @var ApplicationUri
     */
    private $mainUri;

    /**
     * @var array
     */
    private $alternativeUris = [];

    /**
     * ApplicationUriConfigurator constructor.
     */
    public function __construct()
    {
        $this->mainUri = new Uri('/');
    }

    /**
     * @param string $uri
     */
    public function setMainUri(string $uri): void
    {
        $this->mainUri = new Uri(\rtrim($uri, '/'));
    }

    /**
     * @return UriInterface
     */
    public function getMainUri(): UriInterface
    {
        return $this->mainUri;
    }

    /**
     * @param string $name
     * @param string $uri
     */
    public function addAlternativeUri(string $name, string $uri): void
    {
        $this->alternativeUris[$name] = new Uri(\rtrim($uri, '/'));
    }

    /**
     * @param string $name
     */
    public function removeAlternativeUri(string $name): void
    {
        if (\array_key_exists($name, $this->alternativeUris)) {
            unset($this->alternativeUris[$name]);
        }
    }

    /**
     * @return UriInterface[]
     */
    public function getAlternativeUris(): array
    {
        return $this->alternativeUris;
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $serviceRegistry->add(ApplicationUri::class, new ApplicationUri($this));
    }
}
