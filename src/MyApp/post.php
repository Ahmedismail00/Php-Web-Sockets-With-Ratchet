<?php

$dsn = 'mysql:host=localhost;dbname=websocket';
$user = 'root';
$password = '';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);
try {

    $pdo = new PDO($dsn , $user , $password , $options);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    echo 'error ' . $error->getMessage();
}






// post.php ???
// This all was here before  ;)
$entryData = array(
    'category' => $_POST['category']
, 'title' => $_POST['title']
, 'article' => $_POST['article']
, 'when' => time()
);

$pdo->prepare("INSERT INTO blogs (title, article, category, published) VALUES (?, ?, ?, ?)")
    ->execute($entryData['title'], $entryData['article'], $entryData['category'], $entryData['when']);

// This is our new stuff
$context = new ZMQContext();
$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
$socket->connect("tcp://localhost:5555");

$socket->send(json_encode($entryData));