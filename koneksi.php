<?php

require 'vendor/autoload.php';

$config = [
  'DB_DRIVER'      => 'mysql',
  'DB_HOST'        => 'localhost',
  'DB_USER'        => 'root',
  'DB_PASS'        => '',
  'DB_NAME'        => 'toko_buku',
];

$db = new Cahkampung\Landadb($config);
