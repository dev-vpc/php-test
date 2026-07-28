<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Projecthanif\PhpTest\Domain\Dataset;
use Projecthanif\PhpTest\Http\Request;
use Projecthanif\PhpTest\Http\Response;
use Projecthanif\PhpTest\Http\Router;

$dataset = new Dataset();
$router = new Router();

$collections = [
    'users' => fn () => $dataset->users(),
    'posts' => fn () => $dataset->posts(),
    'comments' => fn () => $dataset->comments(),
    'products' => fn () => $dataset->products(),
];

$router->get('/', function (): void {
    Response::json([
        'name' => 'Fake API',
        'description' => 'A mock REST API serving deterministic fake data. Writes are simulated and not persisted.',
        'endpoints' => [
            'GET /users', 'GET /users/{id}', 'GET /users/{id}/posts',
            'GET /posts', 'GET /posts/{id}', 'GET /posts/{id}/comments',
            'GET /comments',
            'GET /products', 'GET /products/{id}',
            'POST /{resource}', 'PUT /{resource}/{id}', 'PATCH /{resource}/{id}', 'DELETE /{resource}/{id}',
        ],
    ]);
});

foreach ($collections as $name => $getAll) {
    $router->get("/{$name}", function (Request $request) use ($getAll): void {
        $items = array_values($getAll());

        if (isset($request->query['_limit'])) {
            $items = array_slice($items, 0, max(0, (int) $request->query['_limit']));
        }

        Response::json($items);
    });

    $router->get("/{$name}/{id}", function (Request $request, array $params) use ($getAll, $name): void {
        $items = $getAll();
        $id = (int) $params['id'];

        if (!isset($items[$id])) {
            Response::notFound(rtrim(ucfirst($name), 's') . ' not found');

            return;
        }

        Response::json($items[$id]);
    });

    $router->post("/{$name}", function (Request $request) use ($getAll): void {
        $items = $getAll();
        $nextId = ($items === [] ? 0 : max(array_keys($items))) + 1;
        $body = $request->jsonBody() ?? [];

        Response::json(['id' => $nextId] + $body, 201);
    });

    foreach (['put', 'patch'] as $verb) {
        $router->{$verb}("/{$name}/{id}", function (Request $request, array $params) use ($getAll): void {
            $items = $getAll();
            $id = (int) $params['id'];

            if (!isset($items[$id])) {
                Response::notFound();

                return;
            }

            $body = $request->jsonBody() ?? [];
            Response::json(array_merge($items[$id], $body, ['id' => $id]));
        });
    }

    $router->delete("/{$name}/{id}", function (Request $request, array $params) use ($getAll): void {
        $items = $getAll();
        $id = (int) $params['id'];

        if (!isset($items[$id])) {
            Response::notFound();

            return;
        }

        Response::json([]);
    });
}

$router->get('/users/{id}/posts', function (Request $request, array $params) use ($dataset): void {
    Response::json($dataset->postsByUser((int) $params['id']));
});

$router->get('/posts/{id}/comments', function (Request $request, array $params) use ($dataset): void {
    Response::json($dataset->commentsByPost((int) $params['id']));
});

$router->dispatch(Request::fromGlobals());
