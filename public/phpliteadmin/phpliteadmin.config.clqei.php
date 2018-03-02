<?php
/**
 * Example configuration file
 *
 * Full parameter descriptions available on the original phpliteadmin.config.sample.php
 * and at URL https://bitbucket.org/phpliteadmin/public/wiki/Configuration .
 */
/* ---- Main settings ---- */
$password = 'admin';
$directory = '../../database';
$subdirectories = false;
$databases = [
    [
        'path' => 'database.sqlite',
        'name' => 'CLQEI',
    ],
];

/* ---- Interface settings ---- */
$theme = 'phpliteadmin.css';
$language = 'en';
$rowsNum = 30;
$charsNum = 300;
$maxSavedQueries = 10;

/* ---- Custom functions ---- */
$custom_functions = [
    'md5',
    'sha1',
    'time',
    'strtotime',
];

/* ---- Advanced options ---- */
$cookie_name = 'pla3412';
$debug = false;
$allowed_extensions = [
    'sqlite',
];
