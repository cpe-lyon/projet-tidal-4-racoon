<?php

namespace Helper\App\Routes;

class Request
{
    public function __construct(
        private array $GET,
        private array $POST,
        private array $FILE,
        private array $COOKIE,
        private array $SERVER,
    ) {}

    public function get(string $key, mixed $default): mixed
    {
        return $this->GET[$key] ?? $default;
    }

    public function post(string $key, mixed $default): mixed
    {
        return $this->POST[$key] ?? $default;
    }

    public function server(string $key, mixed $default): mixed
    {
        return $this->SERVER[$key] ?? $default;
    }

    public function file(string $key, mixed $default): mixed
    {
        return $this->FILE[$key] ?? $default;
    }

    public function cookie(string $key, mixed $default): mixed
    {
        return $this->COOKIE[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->GET[$key]) || isset($this->POST[$key]) || isset($this->SERVER[$key]) || isset($this->COOKIE[$key]) || isset($this->FILE[$key]);
    }
}