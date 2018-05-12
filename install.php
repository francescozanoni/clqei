#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';
use Illuminate\Filesystem\Filesystem;

# #####################################################

# INSTALLATION OPTIONS

$options = [
    'application_url' => 'http://localhost:8000', // Laravel's default

    'with_phpliteadmin' => false,
    'phpliteadmin_url' => 'https://bitbucket.org/phpliteadmin/public/downloads/phpLiteAdmin_v1-9-7-1.zip',

    'locale' => 'it',
    'locale_source_path' => __DIR__ . '/vendor/caouecs/laravel-lang/src',
    'locale_destination_path' => __DIR__ . '/resources/lang',
];

$inputOptions = getopt('', ['application_url:', 'with_phpliteadmin', 'locale:']);

if (isset($inputOptions['application_url']) === true) {
    if (filter_var($inputOptions['application_url'], FILTER_VALIDATE_URL) === false) {
        die('Invalid application URL' . PHP_EOL);
    }
    $options['application_url'] = $inputOptions['application_url'];
}
if (array_key_exists('with_phpliteadmin', $inputOptions) === true) {
    $options['with_phpliteadmin'] = true;
}
if (isset($inputOptions['locale']) === true) {
    // @todo validate locale against locales within folder vendor/caouecs/laravel-lang/src
    if (preg_match($inputOptions['locale'], '/^[a-z]{2}(\-[A-Z]{2})?$/') !== 1) {
        die('Invalid locale' . PHP_EOL);
    }
    $options['locale'] = $inputOptions['locale'];
}

# #####################################################

# FILE/FOLDER PATH CONSTANTS

define('HTACCESS_FILE_PATH', __DIR__ . '/public/.htaccess');
define('HTACCESS_EXAMPLE_FILE_PATH', __DIR__ . '/public/.htaccess.example');
define('DATABASE_FILE_PATH', __DIR__ . '/database/database.sqlite');
define('DOT_ENV_FILE_PATH', __DIR__ . '/.env');
define('DOT_ENV_EXAMPLE_FILE_PATH', __DIR__ . '/.env.example');
define('PHPUNIT_XML_FILE_PATH', __DIR__ . '/phpunit.xml');
define('PHPUNIT_XML_EXAMPLE_FILE_PATH', __DIR__ . '/phpunit.xml.example');
define('PHPLITEADMIN_FOLDER_PATH', __DIR__ . '/public/phpliteadmin');
define('PHPLITEADMIN_ZIP_FILE_PATH', sys_get_temp_dir() . '/phpliteadmin.zip');

# #####################################################

# FILE INEXISTENCE CHECK

$filePathsToCheck = [
    HTACCESS_FILE_PATH,
    DATABASE_FILE_PATH,
    DOT_ENV_FILE_PATH,
    PHPUNIT_XML_FILE_PATH,
];

if ($options['with_phpliteadmin'] === true) {
    $filePathsToCheck[] = PHPLITEADMIN_FOLDER_PATH . '/phpliteadmin.config.php';
    $filePathsToCheck[] = PHPLITEADMIN_FOLDER_PATH . '/phpliteadmin.config.sample.php';
    $filePathsToCheck[] = PHPLITEADMIN_FOLDER_PATH . '/phpliteadmin.php';
    $filePathsToCheck[] = PHPLITEADMIN_FOLDER_PATH . '/readme.md';
}

foreach ($filePathsToCheck as $filePath) {
    if (file_exists($filePath) === true) {
        die($filePath . ' already exists' . PHP_EOL);
    }
}

# #####################################################

# SCRIPT REQUIREMENT CHECK

if ($options['with_phpliteadmin'] === true) {
    echo 'Checking installation script requirements...' . PHP_EOL;
    if (extension_loaded('zip') === false) {
        die('PHP zip extension is required but unavailable. Aborting...' . PHP_EOL);
    }
}

# #####################################################

echo 'Setting up directories...' . PHP_EOL;

# Directories writable by the web user
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('storage'));
foreach ($iterator as $item) {
    chmod($item, 0777);
}
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('bootstrap/cache'));
foreach ($iterator as $item) {
    chmod($item, 0777);
}

# #####################################################

echo 'Setting up database...' . PHP_EOL;

# Database files
touch(DATABASE_FILE_PATH);
chmod(DATABASE_FILE_PATH, 0777);
chmod(dirname(DATABASE_FILE_PATH), 0777);
echo PHP_EOL;

# #####################################################

echo 'Setting up configuration files...' . PHP_EOL;

# Web server-related configuration
copy(HTACCESS_EXAMPLE_FILE_PATH, HTACCESS_FILE_PATH);

# Configuration files
copy(DOT_ENV_EXAMPLE_FILE_PATH, DOT_ENV_FILE_PATH);
copy(PHPUNIT_XML_EXAMPLE_FILE_PATH, PHPUNIT_XML_FILE_PATH);

# #####################################################

echo 'Setting up locale files...' . PHP_EOL;

$source = $options['locale_source_path'] . '/' . $options['locale'];
$destination = $options['locale_destination_path'] . '/' . $options['locale'];
(new Filesystem)->copyDirectory($source, $destination);

# #####################################################

if ($options['with_phpliteadmin'] === true) {

    echo 'Setting up additional software...' . PHP_EOL;

    # phpLiteAdmin download
    $file = file_get_contents($options['phpliteadmin_url']);
    file_put_contents(PHPLITEADMIN_ZIP_FILE_PATH, $file);
    $zip = new ZipArchive();
    if ($zip->open(PHPLITEADMIN_ZIP_FILE_PATH) === true) {
        $zip->extractTo(PHPLITEADMIN_FOLDER_PATH . '/');
        $zip->close();
    }
    unlink(PHPLITEADMIN_ZIP_FILE_PATH);
    copy(
        PHPLITEADMIN_FOLDER_PATH . '/phpliteadmin.config.clqei.php',
        PHPLITEADMIN_FOLDER_PATH . '/phpliteadmin.config.php'
    );

}

# #####################################################

# Base URL and database file path setting
$file = file_get_contents(DOT_ENV_FILE_PATH);
$file = preg_replace('#http://localhost#', $options['application_url'], $file);
$file = preg_replace('#/absolute/path/to/database#', realpath(dirname(DATABASE_FILE_PATH)), $file);
file_put_contents(DOT_ENV_FILE_PATH, $file);

$file = file_get_contents(PHPUNIT_XML_FILE_PATH);
$file = preg_replace('#http://localhost#', $options['application_url'], $file);
$file = preg_replace('#/absolute/path/to/database#', realpath(dirname(DATABASE_FILE_PATH)), $file);
file_put_contents(PHPUNIT_XML_FILE_PATH, $file);

$baseUrl = parse_url($options['application_url'], PHP_URL_PATH);
if (empty($baseUrl) === true) {
    $baseUrl = '/';
}
$file = file_get_contents(HTACCESS_FILE_PATH);
$file = preg_replace('#RewriteBase\s/#', 'RewriteBase ' . $baseUrl, $file);
file_put_contents(HTACCESS_FILE_PATH, $file);

# #####################################################

exec('php artisan key:generate');

# Post-install instructions
if ($options['with_phpliteadmin'] === true) {
    echo PHP_EOL;
    echo "Manual steps:" . PHP_EOL;
    echo PHP_EOL;
    echo " - password and cookie name in " . PHPLITEADMIN_FOLDER_PATH . "/phpliteadmin.config.php" . PHP_EOL;
    echo "    - \$password = 'admin'" . PHP_EOL;
    echo "    - \$cookie_name = 'pla3412'" . PHP_EOL;
}

# #####################################################

echo PHP_EOL;
