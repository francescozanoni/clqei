#!/usr/bin/env php
<?php

define('BASE_PATH', realpath(__DIR__ . '/..'));

$filePathsToDelete = [

    BASE_PATH . '/public/.htaccess',

    BASE_PATH . '/database/database.sqlite',

    BASE_PATH . '/resources/lang/it/auth.php',
    BASE_PATH . '/resources/lang/it/pagination.php',
    BASE_PATH . '/resources/lang/it/passwords.php',
    BASE_PATH . '/resources/lang/it/validation.php',

    BASE_PATH . '/.env',

    BASE_PATH . '/phpunit.xml',

];

$optionalFilePathsToDelete = [

	BASE_PATH . '/public/phpliteadmin/phpliteadmin.config.php',
	BASE_PATH . '/public/phpliteadmin/phpliteadmin.config.sample.php',
	BASE_PATH . '/public/phpliteadmin/phpliteadmin.php',
	BASE_PATH . '/public/phpliteadmin/readme.md',

];

// Check all mandatory file paths are correct: if not found, execution is interrupted.
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

// Check optional file paths are correct: if not found, warnings are displayed.
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
