<?php

declare(strict_types=1);

namespace Projecthanif\PhpTest\Http;

final class Response
{
    public static function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
    }

    public static function noContent(): void
    {
        http_response_code(204);
    }

    public static function notFound(string $message = 'Resource not found'): void
    {
        self::json(['error' => $message], 404);
    }

    public static function error(string $message, int $status = 400): void
    {
        self::json(['error' => $message], $status);
    }
}
