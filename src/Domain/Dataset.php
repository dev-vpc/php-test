<?php

declare(strict_types=1);

namespace Projecthanif\PhpTest\Domain;

use Projecthanif\PhpTest\Support\SeededFaker;

/**
 * Builds the in-memory fake dataset the API serves. Regenerated fresh on
 * every request but always from the same seed, so ids and content stay
 * stable between requests (e.g. GET /users/3 always returns the same user).
 */
final class Dataset
{
    /** @var array<int, array> */
    private array $users;

    /** @var array<int, array> */
    private array $posts;

    /** @var array<int, array> */
    private array $comments;

    /** @var array<int, array> */
    private array $products;

    public function __construct(int $seed = 42)
    {
        $faker = new SeededFaker($seed);

        $this->users = $this->buildUsers($faker, 10);
        $this->posts = $this->buildPosts($faker, 30, count($this->users));
        $this->comments = $this->buildComments($faker, 60, count($this->posts));
        $this->products = $this->buildProducts($faker, 20);
    }

    /** @return array<int, array> */
    private function buildUsers(SeededFaker $faker, int $count): array
    {
        $users = [];
        for ($id = 1; $id <= $count; $id++) {
            $name = $faker->fullName();
            $username = $faker->username($name);

            $users[$id] = [
                'id' => $id,
                'name' => $name,
                'username' => $username,
                'email' => $faker->email($username),
                'phone' => $faker->phone(),
                'city' => $faker->city(),
                'createdAt' => $faker->pastDateTime(720),
            ];
        }

        return $users;
    }

    /** @return array<int, array> */
    private function buildPosts(SeededFaker $faker, int $count, int $userCount): array
    {
        $posts = [];
        for ($id = 1; $id <= $count; $id++) {
            $posts[$id] = [
                'id' => $id,
                'userId' => mt_rand(1, $userCount),
                'title' => $faker->sentence(6),
                'body' => $faker->paragraph(4),
                'published' => $faker->boolean(80),
                'createdAt' => $faker->pastDateTime(365),
            ];
        }

        return $posts;
    }

    /** @return array<int, array> */
    private function buildComments(SeededFaker $faker, int $count, int $postCount): array
    {
        $comments = [];
        for ($id = 1; $id <= $count; $id++) {
            $name = $faker->fullName();
            $comments[$id] = [
                'id' => $id,
                'postId' => mt_rand(1, $postCount),
                'name' => $name,
                'email' => $faker->email($faker->username($name)),
                'body' => $faker->sentence(15),
                'createdAt' => $faker->pastDateTime(200),
            ];
        }

        return $comments;
    }

    /** @return array<int, array> */
    private function buildProducts(SeededFaker $faker, int $count): array
    {
        $products = [];
        for ($id = 1; $id <= $count; $id++) {
            $products[$id] = [
                'id' => $id,
                'name' => $faker->productName(),
                'price' => $faker->price(9.99, 299.99),
                'inStock' => $faker->boolean(70),
                'description' => $faker->sentence(12),
                'createdAt' => $faker->pastDateTime(500),
            ];
        }

        return $products;
    }

    /** @return array<int, array> */
    public function users(): array
    {
        return $this->users;
    }

    /** @return array<int, array> */
    public function posts(): array
    {
        return $this->posts;
    }

    /** @return array<int, array> */
    public function comments(): array
    {
        return $this->comments;
    }

    /** @return array<int, array> */
    public function products(): array
    {
        return $this->products;
    }

    /** @return array<int, array> */
    public function postsByUser(int $userId): array
    {
        return array_values(array_filter($this->posts, static fn (array $p) => $p['userId'] === $userId));
    }

    /** @return array<int, array> */
    public function commentsByPost(int $postId): array
    {
        return array_values(array_filter($this->comments, static fn (array $c) => $c['postId'] === $postId));
    }
}
