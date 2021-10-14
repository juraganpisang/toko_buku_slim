<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'koneksi.php';
require 'vendor/autoload.php';

$app = new \Slim\App;

// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    return new \Slim\Views\PhpRenderer('src/public');
};

$app->get('/', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'pesanan.php');
});

$app->get('/tambah', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'tambah-pesanan.php');
});

$app->post('/tambah', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'proses-tambah.php');
});

$app->post('/hapus', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'proses-hapus.php');
});

$app->post('/print', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'print.php');
});

// Render PHP template in route
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $this->view->render($response, 'coba.php', [
        'name' => $args['name']
    ]);
})->setName('profile');

$app->run();

