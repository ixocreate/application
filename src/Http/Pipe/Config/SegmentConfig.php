<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Pipe\Config;

use Ixocreate\Application\Http\Pipe\PipeConfig;

final class SegmentConfig implements \Serializable
{
    /**
     * @var string
     */
    private $segment;

    /**
     * @var PipeConfig
     */
    private $pipeConfig;

    /**
     * MiddlewareConfig constructor.
     * @param string $segment
     * @param PipeConfig $pipeConfig
     */
    public function __construct(string $segment, PipeConfig $pipeConfig)
    {
        $this->segment = $segment;
        $this->pipeConfig = $pipeConfig;
    }

    /**
     * @return string
     */
    public function segment(): string
    {
        return $this->segment;
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
            'segment' => $this->segment,
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
        $this->segment = $data['segment'];
    }
}
