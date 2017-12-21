#!/usr/bin/env php
<?php

# #####################################################

# Other software URLs
$urls = [
    'phpliteadmin' => 'https://bitbucket.org/phpliteadmin/public/downloads/phpLiteAdmin_v1-9-7-1.zip',
];

# #####################################################

echo 'Checking installation script requirements...' . PHP_EOL;

# Current script dependencies
if (extension_loaded('zip') === false) {
    die('PHP zip extension is required but unavailable. Aborting...' . PHP_EOL);
}

echo 'Setting up directories...' . PHP_EOL;

# Directories writable by the web user
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('storage'));
foreach($iterator as $item) {
    chmod($item, 0777);
}
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('bootstrap/cache'));
foreach($iterator as $item) {
    chmod($item, 0777);
}

echo 'Setting up database...' . PHP_EOL;

# Database files
$db = new SQLite3(__DIR__ . '/database/database.sqlite');
echo PHP_EOL;
$db->close();
chmod(__DIR__ . '/database/database.sqlite', 0777);
chmod(__DIR__ . '/database', 0777);

echo 'Setting up files...' . PHP_EOL;

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

echo 'Setting up additional software...' . PHP_EOL;

# phpLiteAdmin download
$file = file_get_contents($urls['phpliteadmin']);
file_put_contents(sys_get_temp_dir() . '/phpliteadmin.zip', $file);
$zip = new ZipArchive();
if ($zip->open(sys_get_temp_dir() . '/phpliteadmin.zip') === true) {
    $zip->extractTo(__DIR__ . '/public/lib/phpliteadmin/');
    $zip->close();
}
unlink(sys_get_temp_dir() . '/phpliteadmin.zip');
copy(
    __DIR__ . '/public/phpliteadmin/phpliteadmin.config.clqei.php',
    __DIR__ . '/public/phpliteadmin/phpliteadmin.config.php'
);

# Base URL setting
echo 'Application URL? (e.g.: http://localhost/clqei) ';
$applicationUrl = trim(fgets(STDIN));
$file = file_get_contents(__DIR__ . '/.env');
$file = preg_replace('#http://localhost#', $applicationUrl, $file);
file_put_contents(__DIR__ . '/application/configs/application.ini', $file);
echo PHP_EOL;
echo 'Base URL? (e.g.: / or /clqei) ';
$baseUrl = trim(fgets(STDIN));
$file = file_get_contents(__DIR__ . '/public/.htaccess');
$file = preg_replace('#RewriteBase\s/#', 'RewriteBase ' . $baseUrl, $file);
file_put_contents(__DIR__ . '/public/.htaccess', $file);

# Post-install instructions
echo PHP_EOL;
echo "Manual steps:" . PHP_EOL;
echo PHP_EOL;
echo " - password in public/phpliteadmin/phpliteadmin.config.php" . PHP_EOL;
echo "    - \$password = 'admin'" . PHP_EOL;
echo PHP_EOL;
echo " - in .env" . PHP_EOL;
echo "    - APP_KEY" . PHP_EOL;
echo "    - absolute path within DB_DATABASE" . PHP_EOL;
echo PHP_EOL;
