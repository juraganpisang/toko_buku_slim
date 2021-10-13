<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'koneksi.php';
require 'vendor/autoload.php';

$app = new \Slim\App;
$app->get('/', function (Request $request, Response $response, array $args) {
    return require 'src/public/pesanan.php';
});

$app->get('/tambah', function (Request $request, Response $response, array $args) {
    return require 'src/public/tambah-pesanan.php';
});

$app->post('/tambah', function (Request $request, Response $response, array $args) {
    return require 'src/public/proses-tambah.php';
});

$app->post('/hapus', function (Request $request, Response $response, array $args) {
    return require 'src/public/proses-hapus.php';
});

$app->post('/print', function (Request $request, Response $response, array $args) {
    // $hasil_buku = $args['hasil_buku'];
    // $response = $this->view->render($response, 'src/public/.php', ['hasil_buku' => $hasil_buku]);
    // return $response;
    
    return require 'src/public/print.php';
});

$app->run();

