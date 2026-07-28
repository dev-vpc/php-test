<?php

declare(strict_types=1);

$greeting = 'Hello, world!';
$now = new DateTime();
$luckyNumber = random_int(1, 100);

$facts = [
    'PHP has been around since 1994.',
    'Composer is the standard dependency manager for PHP.',
    'DateTime objects make date handling much easier.',
];

echo $greeting . PHP_EOL;
echo 'Current time: ' . $now->format('Y-m-d H:i:s') . PHP_EOL;
echo 'Your lucky number is: ' . $luckyNumber . PHP_EOL;
echo 'Random fact: ' . $facts[array_rand($facts)] . PHP_EOL;
