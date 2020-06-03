<?php
require_once 'Router.php';
require_once 'Request.php';

$router = new Router(new Request);


$router->get('/', function() {
    echo 'Brouette';
});


$router->get('/toto', function() {
    echo 'Brouette2';
});