<?php

declare(strict_types=1);

namespace Projecthanif\PhpTest\Http;

final class Request
{
    /** @param array<string, mixed> $query */
    private function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly array $query,
        private readonly string $rawBody,
    ) {
    }

    public static function fromGlobals(): self
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = rtrim((string) parse_url($uri, PHP_URL_PATH), '/');
        $path = $path === '' ? '/' : $path;

        return new self($method, $path, $_GET, (string) file_get_contents('php://input'));
    }

    /** @return array<string, mixed>|null */
    public function jsonBody(): ?array
    {
        if ($this->rawBody === '') {
            return null;
        }

        $decoded = json_decode($this->rawBody, true);

        return is_array($decoded) ? $decoded : null;
    }
}
