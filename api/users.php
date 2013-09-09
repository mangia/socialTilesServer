<?php
require 'Slim/Slim.php';
require 'tags.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$dsn = "pgsql:" . "host=ec2-54-227-238-31.compute-1.amazonaws.com;" . "dbname=d3r468400g680j;" . "user=wzcdebwgjfehyz;" . "port=5432;" . "sslmode=require;" . "password=U2hPQsSC7_oM4bV-Fp7NiRy9j7 ";
$db = new PDO($dsn);

if ($app -> request() -> isGet()) {
    $option = $app -> request() -> get('option');
    if ($option == "info") {
        $fbid = $app -> request() -> get('fbid');
        $query = "SELECT *  FROM users u WHERE u.fbid='" . $fbid . "'";
        $result = $db -> query($query);
        $row = $result -> fetch(PDO::FETCH_ASSOC);
        echo json_encode($row);
    } else if ($option == "search") {
        $user_id = $app -> request() -> get('user_id');
        $search_entries = explode(" ", $app -> request() -> get('search_entry'));

        $query = "SELECT * FROM users WHERE user_id != " . $user_id . " AND ";
        $flag = true;
        foreach ($search_entries as $entry) {
            if (!$flag) {
                $query .= " OR ";
            } else {
                $flag = false;
            }

            $query .= " name_first LIKE '" . $entry . "' OR name_last LIKE '" . $entry . "' ";
        }
        //echo $query;
        //echo json_encode($search_entries);
        $result = $db -> query($query);
        $rows = $result -> fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $isfriend = "false";
            echo $row;
            $query = "SELECT *  FROM friends  WHERE from_user='" . $user_id . "' AND to_user = " . $row[Tags::$user_id] . "AND status = 1";
            $result1 = $db -> query($query);
            if ($result1 -> rowCount() > 0) {
                $isfriend = "true";
            } else {
                $query = "SELECT *  FROM friends  WHERE to_user='" . $user_id . "' AND from_user = " . $row[Tags::$user_id] . "AND status = 1";
                $result1 = $db -> query($query);
                if ($result1 -> rowCount() > 0) {
                    $isfriend = "true";
                }
            }
            $row["isfriend"] = $isfriend;
            echo $row["isfriend"];
        }
        echo json_encode($rows);
    }

}
if ($app -> request() -> isPost()) {

    $fbid = $app -> request() -> post('fbid');
    $name_first = $app -> request() -> post('name_first');
    $name_last = $app -> request() -> post('name_last');

    $query = "INSERT INTO entities(type) SELECT " . Tags::$newUser . ";";
    $result = $db -> query($query);

    $query = "SELECT id FROM  entities ORDER BY id desc limit 1 ; ";
    $result = $db -> query($query);

    $row = $result -> fetch(PDO::FETCH_ASSOC);
    $entity_id = $row['id'];

    $query = "INSERT INTO users(fbid, name_first, name_last,  entity)	SELECT" . " '" . $fbid . "'," . " '" . $name_first . "'," . " '" . $name_last . "'," . " " . $entity_id . " WHERE NOT exists( SELECT * FROM users where fbid = '" . $fbid . "');";

    //echo "querry is: ".$query;
    $result = $db -> query($query);

    $query = "SELECT *  FROM users u WHERE u.fbid='" . $fbid . "'";
    $result = $db -> query($query);

    $row = $result -> fetch(PDO::FETCH_ASSOC);

    echo json_encode($row);
}
//select your_fields from your_table where your_condition order by oid desc limit 1;
/*$user_array = array();

 while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
 $user_array['user_id']         = $row['user_id'];
 $user_array['fbid']            = $row['fbid'];
 $user_array['name_first']      = $row['name_first'];
 $user_array['name_last']       = $row['name_last'];
 $user_array['total_score']     = $row['total_score'];
 $user_array['total_duration']  = $row['total_duration'];
 $user_array['num_achievments'] = $row['num_achievments'];
 $user_array['entity']          = $row['entity'];
 }*/
?>

