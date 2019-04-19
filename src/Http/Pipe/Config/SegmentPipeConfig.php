<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Pipe\Config;

use Ixocreate\Application\Http\Pipe\PipeConfig;

final class SegmentPipeConfig implements \Serializable
{
    /**
     * @var string
     */
    private $provider;

    /**
     * @var PipeConfig
     */
    private $pipeConfig;

    /**
     * MiddlewareConfig constructor.
     * @param string $provider
     * @param PipeConfig $pipeConfig
     */
    public function __construct(string $provider, PipeConfig $pipeConfig)
    {
        $this->provider = $provider;
        $this->pipeConfig = $pipeConfig;
    }

    /**
     * @return string
     */
    public function provider(): string
    {
        return $this->provider;
    }

    /**
     * @return PipeConfig
     */
    public function pipeConfig(): PipeConfig
    {
        return $this->pipeConfig;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return \serialize([
            'provider' => $this->provider,
            'pipeConfig' => $this->pipeConfig,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = \unserialize($serialized);
        $this->pipeConfig = $data['pipeConfig'];
        $this->provider = $data['provider'];
    }
}
