<?php

declare(strict_types=1);

namespace Projecthanif\PhpTest\Support;

/**
 * Deterministic fake-data generator. Seeding it with the same integer
 * always reproduces the same sequence, so the API's dataset stays
 * stable across requests without needing real persistence.
 */
final class SeededFaker
{
    private const FIRST_NAMES = [
        'Amina', 'Bello', 'Chidi', 'Deng', 'Efe', 'Farida', 'Grace', 'Hassan',
        'Ifeoma', 'James', 'Khadija', 'Lola', 'Musa', 'Ngozi', 'Omar', 'Peju',
        'Quincy', 'Rita', 'Sani', 'Tolu', 'Uche', 'Victor', 'Wumi', 'Yusuf', 'Zainab',
    ];

    private const LAST_NAMES = [
        'Abubakar', 'Bassey', 'Chukwu', 'Danjuma', 'Eze', 'Falade', 'Garba',
        'Haruna', 'Ibekwe', 'Jibril', 'Kalu', 'Lawal', 'Mustapha', 'Nwosu',
        'Okafor', 'Peters', 'Quadri', 'Rufai', 'Suleiman', 'Tanko',
    ];

    private const DOMAINS = ['example.com', 'mail.test', 'fakemail.dev', 'sandbox.io'];

    private const WORDS = [
        'lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing',
        'elit', 'sed', 'eiusmod', 'tempor', 'incididunt', 'labore', 'magna',
        'aliqua', 'enim', 'minim', 'veniam', 'quis', 'nostrud', 'exercitation',
        'ullamco', 'laboris', 'nisi', 'aliquip', 'commodo', 'duis', 'aute',
    ];

    private const CITIES = [
        'Lagos', 'Abuja', 'Kano', 'Ibadan', 'Kaduna', 'Enugu', 'Jos', 'Owerri',
    ];

    private const PRODUCT_NOUNS = [
        'Chair', 'Lamp', 'Backpack', 'Keyboard', 'Monitor', 'Mug', 'Notebook',
        'Headphones', 'Wallet', 'Bicycle', 'Blender', 'Sneakers',
    ];

    private const PRODUCT_ADJECTIVES = [
        'Ergonomic', 'Sleek', 'Rustic', 'Compact', 'Handcrafted', 'Wireless',
        'Vintage', 'Durable', 'Portable', 'Premium',
    ];

    public function __construct(int $seed = 1)
    {
        mt_srand($seed);
    }

    public function fullName(): string
    {
        return self::FIRST_NAMES[array_rand(self::FIRST_NAMES)]
            . ' ' . self::LAST_NAMES[array_rand(self::LAST_NAMES)];
    }

    public function username(string $fullName): string
    {
        return strtolower(str_replace(' ', '.', $fullName)) . mt_rand(1, 99);
    }

    public function email(string $username): string
    {
        return $username . '@' . self::DOMAINS[array_rand(self::DOMAINS)];
    }

    public function city(): string
    {
        return self::CITIES[array_rand(self::CITIES)];
    }

    public function phone(): string
    {
        return sprintf('+234-%03d-%03d-%04d', mt_rand(700, 909), mt_rand(100, 999), mt_rand(1000, 9999));
    }

    public function sentence(int $words = 8): string
    {
        $picked = [];
        for ($i = 0; $i < $words; $i++) {
            $picked[] = self::WORDS[array_rand(self::WORDS)];
        }
        $sentence = implode(' ', $picked);

        return ucfirst($sentence) . '.';
    }

    public function paragraph(int $sentences = 3): string
    {
        $out = [];
        for ($i = 0; $i < $sentences; $i++) {
            $out[] = $this->sentence(mt_rand(6, 12));
        }

        return implode(' ', $out);
    }

    public function productName(): string
    {
        return self::PRODUCT_ADJECTIVES[array_rand(self::PRODUCT_ADJECTIVES)]
            . ' ' . self::PRODUCT_NOUNS[array_rand(self::PRODUCT_NOUNS)];
    }

    public function price(float $min = 5, float $max = 500): float
    {
        return round(mt_rand((int) ($min * 100), (int) ($max * 100)) / 100, 2);
    }

    public function pastDateTime(int $maxDaysAgo = 365): string
    {
        $timestamp = time() - mt_rand(0, $maxDaysAgo * 86400);

        return date(DATE_ATOM, $timestamp);
    }

    public function boolean(int $trueChance = 50): bool
    {
        return mt_rand(1, 100) <= $trueChance;
    }
}
