<?php
require dirname(__DIR__) . '/vendor/autoload.php';
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSocket\Chat;
use App\WebSocket\ChatServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8090
);

echo "Serveur WebSocket démarré sur le port 8090\n";
$server->run();

