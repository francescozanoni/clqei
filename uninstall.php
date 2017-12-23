#!/usr/bin/env php
<?php

$filePathsToDelete = [

	__DIR__ . '/public/.htaccess',

	__DIR__ . '/database/database.sqlite',

	__DIR__ . '/.env',

	__DIR__ . '/public/phpliteadmin/phpliteadmin.config.php',
	__DIR__ . '/public/phpliteadmin/phpliteadmin.config.sample.php',
	__DIR__ . '/public/phpliteadmin/phpliteadmin.php',
	__DIR__ . '/public/phpliteadmin/readme.md',

];

// Check all file paths are correct.
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

// Delete file paths.
foreach ($filePathsToDelete as $filePath) {
    if (file_exists($filePath) === false) {
        return;
    }
    unlink($filePath);
}
