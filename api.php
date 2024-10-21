<?php

use App\Services\Log;
use Slim\Exception\HttpNotFoundException;

require dirname(__DIR__) . '/core/bootstrap.php';

$container = new DI\Container();
$container->set(Illuminate\Database\Capsule\Manager::class, new Vesp\Services\Eloquent());

$app = DI\Bridge\Slim\Bridge::create($container);
$app->add(App\Middlewares\Auth::class);
$app->add(new RKA\Middleware\IpAddress());
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

require BASE_DIR . 'core/routes.php';

try {
    $app->run();
} catch (HttpNotFoundException $e) {
    http_response_code(404);
    echo json_encode('Not Found');
} catch (Throwable $e) {
    Log::error($e);
    http_response_code(500);
    echo json_encode('Internal Server Error');
}
