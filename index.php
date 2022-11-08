<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/vendor/autoload.php';

 /*
 logic for displaying templates
 */

$loader = new \Twig\Loader\FilesystemLoader('templates');
$view = new \Twig\Environment($loader);

/* variables for the database connection */
$config = include 'config/database.php';
$dsn = $config['dsn'];
$username = $config['username'];
$password = $config['password'];

/* uses the setAttribute method to connect */
try { /* I use try catch to prevent any exceptional situations when connecting the DB */
$connection = new PDO($dsn, $username, $password);
/* error type */
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
/* the logic is how I will load the data when I use select, FETCH_ASSOS is an associative array */
$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    echo 'Database error: ' . $exception->getMessage();
    die();
}
// Create app
$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) use ($view) {
    $body = $view->render('index.twig');
    $response->getBody()->write($body);
    return $response;
});

$app->get('/about', function (Request $request, Response $response, $args) use ($view) {
    $body = $view->render('about.twig', [
        'name' => 'Yurii'
    ]);
    $response->getBody()->write($body);
    return $response;
});

/* route for post URL (normal link)*/
$app->get('/{url_key}', function (Request $request, Response $response, $args) use ($view) {
    $body = $view->render('post.twig', [
        'url_key' => $args['url_key']
    ]);
    $response->getBody()->write($body);
    return $response;
});


$app->run();