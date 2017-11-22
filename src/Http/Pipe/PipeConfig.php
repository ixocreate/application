<?php
declare(strict_types=1);
namespace KiwiSuite\Application\Http\Pipe;


final class PipeConfig
{
    /**
     * @var array
     */
    private $config;

    /**
     * PipeConfigurator constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        //TODO check config
        $this->config = $config;
    }

    public function getGlobalPipe(): array
    {
        return $this->config['globalPipe'];
    }

    public function getRoutingPipe(): array
    {
        return $this->config['routingPipe'];
    }

    public function getDispatchPipe(): array
    {
        return $this->config['dispatchPipe'];
    }
}
