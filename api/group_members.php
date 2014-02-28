<?php
require 'Slim/Slim.php';
require 'tags.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$dsn = "pgsql:" . "host=ec2-54-227-238-31.compute-1.amazonaws.com;" . "dbname=d3r468400g680j;" . "user=wzcdebwgjfehyz;" . "port=5432;" . "sslmode=require;" . "password=U2hPQsSC7_oM4bV-Fp7NiRy9j7 ";
$db = new PDO($dsn);
/**
 * @param group
 * @param op : user 
 * if op = user
 *      @return the user info of the group_members
 * else
 *      @return the group members of the groups
 * 
 */
if ($app -> request() -> isGet()) {

    $group = $app -> request() -> get(Tags::$group_id);

    //echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));

    if ($app -> request() -> get(Tags::$op) == Tags::$user) {
        $query = "SELECT *  FROM users WHERE ";
        $flag = true;

        $query = "SELECT DISTINCT ON (u.user_id) * FROM group_members gm, users u WHERE gm.group_id='" . $group . "' AND gm.status = 1 AND gm.user_id = u.user_id";
        //echo $query;

        $result = $db -> query($query);
        echo json_encode($result -> fetchAll(PDO::FETCH_ASSOC));
    } else {
        $query = "SELECT *  FROM group_members gm WHERE gm.group_id='" . $group . "' AND gm.status = 1";
        $result = $db -> query($query);
        echo json_encode($result -> fetchAll(PDO::FETCH_ASSOC));
    }

}

/**
 * @param op : single (for one user), multiple (for may users )
 * if op = single
 *      @param user_id
 *      @param group_id
 *      inserts a single user in a group
 * else op = multiple
 *      @param user_ids : the list of user ids
 *      @param gruop_id : 
 */

if ($app -> request() -> isPost()) {
    if ($app -> request() -> post(Tags::$op) == "single") {
        $user = $app -> request() -> post(Tags::$user_id);
        $group = $app -> request() -> post(Tags::$group_id);
        $query = "INSERT INTO group_members(group_id, user_id, status) VALUES (" . " " . $group . ", " . " " . $user . ", 0 );";
        $result = $db -> query($query);
    } else if ($app -> request() -> post(Tags::$op) == "multiple") {
        $user_idss = $app -> request() -> post(Tags::$user_ids);
        $group = $app -> request() -> post(Tags::$group_id);
        $user_ids = json_decode($user_idss, true);
        //var_dump($user_ids);
        //echo $user_ids;

        foreach ($user_ids as $i => $value) {
            //echo $user_ids[$i];
            $query = "INSERT INTO group_members(group_id, user_id, status) VALUES (" . " " . $group . ", " . " " . $user_ids[$i] . ", 0 );";
            $result = $db -> query($query);
        }

    }
     $response = array('response' => 'ok');
        echo json_encode($response);
}
?>

