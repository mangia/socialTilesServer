<?php
require 'Slim/Slim.php';
require 'tags.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$dsn = "pgsql:" . "host=ec2-54-227-238-31.compute-1.amazonaws.com;" . "dbname=d3r468400g680j;" . "user=wzcdebwgjfehyz;" . "port=5432;" . "sslmode=require;" . "password=U2hPQsSC7_oM4bV-Fp7NiRy9j7 ";
$db = new PDO($dsn);

/**
 * @param user_id
 * @return all the hiscores from the user
 */
if ($app -> request() -> isGet()) {
    $user_id = $app->request()->get(Tags::$user_id);
    $query = "SELECT * FROM highscores WHERE  user_id = ".$user_id." ";
    $result = $db -> query($query);
    echo json_encode($result -> fetchAll(PDO::FETCH_ASSOC));
}
?>