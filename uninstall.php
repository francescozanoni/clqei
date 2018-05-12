#!/usr/bin/env php
<?php

$filePathsToDelete = [

    __DIR__ . '/public/.htaccess',

    __DIR__ . '/database/database.sqlite',

    __DIR__ . '/resources/lang/it/auth.php',
    __DIR__ . '/resources/lang/it/pagination.php',
    __DIR__ . '/resources/lang/it/passwords.php',
    __DIR__ . '/resources/lang/it/validation.php',

    __DIR__ . '/.env',

    __DIR__ . '/phpunit.xml',

];

$optionalFilePathsToDelete = [

	__DIR__ . '/public/phpliteadmin/phpliteadmin.config.php',
	__DIR__ . '/public/phpliteadmin/phpliteadmin.config.sample.php',
	__DIR__ . '/public/phpliteadmin/phpliteadmin.php',
	__DIR__ . '/public/phpliteadmin/readme.md',

];

// Check all mandatory file paths are correct.
foreach ($filePathsToDelete as $filePath) {
    if (file_exists($filePath) === false) {
        die($filePath . ' does not exist' . PHP_EOL);
    }
    if (is_writable($filePath) === false) {
        die($filePath . ' is not writable' . PHP_EOL);
    }
    if (is_file($filePath) === false) {
        die($filePath . ' is not a file' . PHP_EOL);
    }
}

// Check optional file paths are correct (not blocking).
foreach ($optionalFilePathsToDelete as $filePath) {
    if (file_exists($filePath) === false) {
        echo $filePath . ' does not exist' . PHP_EOL;
        continue;
    }
    if (is_writable($filePath) === false) {
        echo $filePath . ' is not writable' . PHP_EOL;
        continue;
    }
    if (is_file($filePath) === false) {
        echo $filePath . ' is not a file' . PHP_EOL;
        continue;
    }
}

// Delete file paths.
foreach ($filePathsToDelete as $filePath) {
    unlink($filePath);
}
foreach ($optionalFilePathsToDelete as $filePath) {
    if (file_exists($filePath) === false ||
        is_writable($filePath) === false ||
        is_file($filePath) === false) {
        continue;
    }
    unlink($filePath);
}
