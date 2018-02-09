#!/usr/bin/env php
<?php
# #####################################################

# INSTALLATION OPTIONS

$options = [
    'application_url' => 'http://localhost',
    'with_phpliteadmin' => false,
    'locale' => 'it',
    'locale_source_path' => __DIR__ . '/vendor/caouecs/laravel-lang/src',
    'locale_destination_path' => __DIR__ . '/resources/lang',
    'phpliteadmin_url' => 'https://bitbucket.org/phpliteadmin/public/downloads/phpLiteAdmin_v1-9-7-1.zip'
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

# FILE INEXISTENCE CHECK

$filePathsToCheck = [
    __DIR__ . '/public/.htaccess',
    __DIR__ . '/database/database.sqlite',
    __DIR__ . '/database/test_database.sqlite',
    __DIR__ . '/.env',
    __DIR__ . '/phpunit.xml',
];

if ($options['with_phpliteadmin'] === true) {
    $filePathsToCheck[] = __DIR__ . '/public/phpliteadmin/phpliteadmin.config.php';
    $filePathsToCheck[] = __DIR__ . '/public/phpliteadmin/phpliteadmin.config.sample.php';
    $filePathsToCheck[] = __DIR__ . '/public/phpliteadmin/phpliteadmin.php';
    $filePathsToCheck[] = __DIR__ . '/public/phpliteadmin/readme.md';
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
$db = new SQLite3(__DIR__ . '/database/database.sqlite');
$db->close();
$db = new SQLite3(__DIR__ . '/database/test_database.sqlite');
$db->close();
chmod(__DIR__ . '/database/database.sqlite', 0777);
chmod(__DIR__ . '/database/test_database.sqlite', 0777);
chmod(__DIR__ . '/database', 0777);
echo PHP_EOL;

# #####################################################

echo 'Setting up configuration files...' . PHP_EOL;

# Web server-related configuration
copy(
    __DIR__ . '/public/.htaccess.example',
    __DIR__ . '/public/.htaccess'
);

# Configuration files
copy(
    __DIR__ . '/.env.example',
    __DIR__ . '/.env'
);
copy(
    __DIR__ . '/phpunit.xml.example',
    __DIR__ . '/phpunit.xml'
);

# #####################################################

echo 'Setting up locale files...' . PHP_EOL;

# Function taken from http://www.beliefmedia.com/copy-directory-php
function beliefmedia_recurse_copy($src, $dst)
{
    $dir = @opendir($src);
    if (!file_exists($dst)) {
        @mkdir($dst);
    }
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                beliefmedia_recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

$src = $options['locale_source_path'] . '/' . $options['locale'];
$dst = $options['locale_destination_path'] . '/' . $options['locale'];
beliefmedia_recurse_copy($src, $dst);

# #####################################################

echo 'Setting up additional software...' . PHP_EOL;

# phpLiteAdmin download
$file = file_get_contents($options['phpliteadmin_url']);
file_put_contents(sys_get_temp_dir() . '/phpliteadmin.zip', $file);
$zip = new ZipArchive();
if ($zip->open(sys_get_temp_dir() . '/phpliteadmin.zip') === true) {
    $zip->extractTo(__DIR__ . '/public/phpliteadmin/');
    $zip->close();
}
unlink(sys_get_temp_dir() . '/phpliteadmin.zip');
copy(
    __DIR__ . '/public/phpliteadmin/phpliteadmin.config.clqei.php',
    __DIR__ . '/public/phpliteadmin/phpliteadmin.config.php'
);

# #####################################################

# Base URL and database file path setting
$file = file_get_contents(__DIR__ . '/.env');
$file = preg_replace('#http://localhost#', $options['application_url'], $file);
$file = preg_replace('#/absolute/path/to/database#', realpath(__DIR__ . '/database'), $file);
file_put_contents(__DIR__ . '/.env', $file);

$file = file_get_contents(__DIR__ . '/phpunit.xml');
$file = preg_replace('#/absolute/path/to/database#', realpath(__DIR__ . '/database'), $file);
file_put_contents(__DIR__ . '/phpunit.xml', $file);

$baseUrl = parse_url($options['application_url'], PHP_URL_PATH);
if ($baseUrl === '') {
    $baseUrl = '/';
}
$file = file_get_contents(__DIR__ . '/public/.htaccess');
$file = preg_replace('#RewriteBase\s/#', 'RewriteBase ' . $baseUrl, $file);
file_put_contents(__DIR__ . '/public/.htaccess', $file);

# #####################################################

# Post-install instructions
if ($options['with_phpliteadmin'] === true) {
    echo PHP_EOL;
    echo "Manual steps:" . PHP_EOL;
    echo PHP_EOL;
    echo " - password and cookie name in public/phpliteadmin/phpliteadmin.config.php" . PHP_EOL;
    echo "    - \$password = 'admin'" . PHP_EOL;
    echo "    - \$cookie_name = 'pla3412'" . PHP_EOL;
}

# #####################################################

echo PHP_EOL;
