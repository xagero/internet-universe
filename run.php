<?php
/**
 * @author Pavel Tsydzik <xagero@gmail.com>
 * @date 03.08.2018 12:26
 */

require 'Init.php';

$config = [
    'dsn' => "mysql:dbname=test;host=localhost",
    'username' => 'root',
    'password' => 'root'
];

$init = new Init($config['dsn'], $config['username'], $config['password']);
$init->get(5);
