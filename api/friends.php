<?php
require 'Slim/Slim.php';
require 'tags.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$dsn = "pgsql:" . "host=ec2-54-227-238-31.compute-1.amazonaws.com;" . "dbname=d3r468400g680j;" . "user=wzcdebwgjfehyz;" . "port=5432;" . "sslmode=require;" . "password=U2hPQsSC7_oM4bV-Fp7NiRy9j7 ";
$db = new PDO($dsn);


/**
 * get request
 * @param user_id
 * @return the user information of the users friends 
 */
if ($app -> request() -> isGet()) {

    $user_id = $app -> request() -> get(Tags::$user_id);
    $user_ids = array();

    $query = "SELECT *  FROM friends  WHERE from_user='" . $user_id . "' AND status = 1";
    $result = $db -> query($query);
    while ($row = $result -> fetch(PDO::FETCH_ASSOC)) {
        $user_ids[] = $row['to_user'];
    }

    $query = "SELECT *  FROM friends  WHERE to_user='" . $user_id . "' AND status = 1";
    $result = $db -> query($query);
    while ($row = $result -> fetch(PDO::FETCH_ASSOC)) {
        $user_ids[] = $row['from_user'];
    }

    if (sizeof($user_ids) > 0) {

        $query = "SELECT *  FROM users  WHERE ";
        $flag = true;
        foreach ($user_ids as $i => $value) {
            if (!$flag) {
                $query .= " OR ";
            } else {
                $flag = false;
            }
            $query .= " user_id = " . $user_ids[$i] . " ";
        }

        $result = $db -> query($query);

        echo json_encode($result -> fetchAll(PDO::FETCH_ASSOC));
    } else {
        $response = array('response' => 'nofriends');
        echo json_encode($response);
    }

}

/**
 * post request
 * @param op : operation either add a new friend or delete an existing one
 * @param from_user : sender of the request
 * @param to_user   : receiver of the request
 */

if ($app -> request() -> isPost()) {
    $op = $app -> request() -> post(Tags::$op);
    $from_user = $app -> request() -> post(Tags::$from_user);
    $to_user = $app -> request() -> post(Tags::$to_user);
    if ($op == "add") {

        $query = "INSERT INTO friends (from_user, to_user, status) VALUES (" . " " . $from_user . ", " . " " . $to_user . ", 1 );";

        $result = $db -> query($query);
    } else {
        $query = "DELETE from friends WHERE from_user = " . $from_user . " AND to_user" . $to_user . " AND EXISTS(SELECT 1 WHERE from friends WHERE from_user = " . $from_user . " AND to_user" . $to_user . " )";
        $result = $db -> query($query);
        $query = "DELETE from friends WHERE from_user = " . $to_user . " AND to_user" . $from_user . " AND EXISTS(SELECT 1 WHERE from friends WHERE from_user = " . $to_user . " AND to_user" . $from_user . " )";
        $result = $db -> query($query);
    }
    $response = array('response' => 'ok');
    echo json_encode($response);
}
?>

