<?php
/**
 * @author Pavel Tsydzik <xagero@gmail.com>
 * @date 03.08.2018 13:52
 */


$pattern = __DIR__ . "/datafiles/[a-z0-9]*.ixt";

$list = glob($pattern);

foreach ($list as $path) {
    echo realpath($path) . "\n";
}