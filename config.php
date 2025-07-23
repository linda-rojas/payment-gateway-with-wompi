<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$publicKey = $_ENV['TEST_PUBLIC_KEY'];

define('TEST_PUBLIC_KEY', $publicKey);
define('WOMPI_API_BASE', 'https://sandbox.wompi.co/v1');
