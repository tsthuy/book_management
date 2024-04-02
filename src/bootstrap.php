<?php

require_once 'functions.php';
require_once __DIR__ . '/../lib/Psr4AutoloaderClass.php';

$loader = new Psr4AutoloaderClass;
$loader->register();


$loader->addNamespace('QTDL\Project', __DIR__ . '/classes');

try {
    $PDO = (new QTDL\Project\PDOFactory())->create([
        'dbhost' => 'localhost',
        'dbname' => 'library_management',
        'dbuser' => 'root',
        'dbpass' => '1234567890-='
    ]);
} catch (Exception $ex) {
    echo 'Không thể kết nối đến MySQL,
    kiểm tra lại username/password đến MySQL.<br>';
    exit("<pre>${ex}</pre>");
}
