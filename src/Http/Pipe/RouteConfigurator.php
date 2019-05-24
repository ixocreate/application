<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Pipe;

final class RouteConfigurator
{
    private $name;

    private $path;

    /**
     * @var array
     */
    private $methods = [
        'GET' => false,
        'POST' => false,
        'PUT' => false,
        'DELETE' => false,
        'PATCH' => false,
    ];

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $before = [];

    /**
     * @var array
     */
    private $after = [];

    private $options = [];

    private $priority = 500000;

    public function __construct(string $path, string $action, string $name)
    {
        $this->path = $path;
        //TODO check MiddlewareInterface|HandlerInterface
        $this->action = $action;
        $this->name = $name;
    }

    public function before(string $middleware, bool $prepend = false): void
    {
        //TODO check MiddlewareInterface

        if ($prepend === true) {
            \array_unshift($this->before, $middleware);
            return;
        }

        $this->before[] = $middleware;
    }

    public function after(string $middleware, bool $prepend = false): void
    {
        //TODO check MiddlewareInterface|HandlerInterface
        if ($prepend === true) {
            \array_unshift($this->after, $middleware);
            return;
        }

        $this->after[] = $middleware;
    }

    public function addOption(string $name, $value): void
    {
        $this->options[$name] = $value;
    }

    public function enableGet(): void
    {
        $this->methods['GET'] = true;
    }

    public function disableGet(): void
    {
        $this->methods['GET'] = false;
    }

    public function enablePost(): void
    {
        $this->methods['POST'] = true;
    }

    public function disablePost(): void
    {
        $this->methods['POST'] = false;
    }

    public function enablePut(): void
    {
        $this->methods['PUT'] = true;
    }

    public function disablePut(): void
    {
        $this->methods['PUT'] = false;
    }

    public function enableDelete(): void
    {
        $this->methods['DELETE'] = true;
    }

    public function disableDelete(): void
    {
        $this->methods['DELETE'] = false;
    }

    public function enablePatch(): void
    {
        $this->methods['PATCH'] = true;
    }

    public function disablePatch(): void
    {
        $this->methods['PATCH'] = false;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethods(): array
    {
        return \array_keys(\array_filter($this->methods));
    }

    public function getPipe(): array
    {
        return \array_merge($this->before, [$this->action], $this->after);
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function __invoke(callable $callable)
    {
        $callable($this);
    }
}
