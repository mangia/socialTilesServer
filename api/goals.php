<?php
require 'Slim/Slim.php';
require 'tags.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$dsn = "pgsql:" . "host=ec2-54-227-238-31.compute-1.amazonaws.com;" . "dbname=d3r468400g680j;" . "user=wzcdebwgjfehyz;" . "port=5432;" . "sslmode=require;" . "password=U2hPQsSC7_oM4bV-Fp7NiRy9j7 ";
$db = new PDO($dsn);

/**
 * @param op : created_for (if it a goal for a group, a single user or if it is an event) achieved_by ( which user should acieve this goal) 
 * if op = created for
 *      @return the goals created for the entity
 * if op = achieved_by
 *      @return the user's goal      
 */

if ($app -> request() -> isGet()) {
    if ($app -> request() -> get(Tags::$op) == "created_for") {
        $entity = $app -> request() -> get(Tags::$entity);

        $query = "SELECT *  " . "  FROM goals g, users u WHERE  g.created_for =" . $entity . " AND g.achieved_by = u.user_id ;";
        //echo $query;
        $result = $db -> query($query);
        echo json_encode($result -> fetchAll(PDO::FETCH_ASSOC));
    } else if ($app -> request() -> get(Tags::$op) == "achieved_by") {
        $user_id = $app -> request() -> get(Tags::$user_id);
        $query = "SELECT *  FROM goals g, users u WHERE g.achieved_by=" . $user_id . " AND g.achieved_by = u.user_id ;";
        $result = $db -> query($query);
        //echo $query;
        echo json_encode($result -> fetchAll(PDO::FETCH_ASSOC));
    }
}

/**
 * @param name
 * @param gmae_name
 * @param goal_type
 * @param threshold
 * @param reward_points
 * @param created_for
 * @param start_date
 * @param end_date
 * @param date_created
 * 
 * @param  op : single or multiple
 * if op  = single
 *      @param achieved_by
 *      inserts the goal for a single user
 * if op = multiple
 *  inserts goal to all the groupmembers of a group
 */

if ($app -> request() -> isPost()) {

    $name = $app -> request() -> post('name');
    $game_name = $app -> request() -> post('game_name');
    $goal_type = $app -> request() -> post('goal_type');
    $threshold = $app -> request() -> post('threshold');
    $reward_points = $app -> request() -> post('reward_points');
    $created_for = $app -> request() -> post('created_for');

    $start_date = $app -> request() -> post('start_date');
    $end_date = $app -> request() -> post('end_date');
    $date = $app -> request() -> post('date_created');

    //echo "post vars are :";
    //var_dump($app->request()->post());

    if ($app -> request() -> post(Tags::$op) == "single") {
        $achieved_by = $app -> request() -> post('achieved_by');
        $query = "INSERT INTO goals (name, game_name, goal_type, threshold, reward_points, created_for, achieved_by, start_date, end_date, date_created)   VALUES ( " . " '" . $name . "'," . " '" . $game_name . "'," . " " . $goal_type . "," . " " . $threshold . "," . " " . $reward_points . ", " . " " . $created_for . "," . " " . $achieved_by . "," . " '" . $start_date . "'," . " '" . $end_date . "'," . " '" . $date . "' );";

        echo "querry is: " . $query;
        $result = $db -> query($query);

        $query = "SELECT *  FROM goals ORDER BY goal_id desc limit 1 ; ";
        $result = $db -> query($query);
        $row = $result -> fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($row);
    } else if ($app -> request() -> post(Tags::$op) == "multiple") {

        $query = "SELECT * from groups g, group_members gm WHERE g.entity=" . $created_for . " AND g.group_id = gm.group_id";
        echo $query;

        $result = $db -> query($query);
        $achieved_by_array = array();
        //echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));

        while ($row = $result -> fetch(PDO::FETCH_ASSOC)) {
            echo "HIIII";
            echo $row;
            echo "HOLA";
            echo $row[Tags::$user_id];
            $achieved_by_array[] = $row[Tags::$user_id];
        }

        // echo json_encode($achieved_by_array);

        $query = "INSERT INTO goals (name, game_name, goal_type, threshold, reward_points, created_for, achieved_by, start_date, end_date, date_created)   VALUES";
        foreach ($achieved_by_array as $achieved_by) {
            $query .= "('" . $name . "'," . "'" . $game_name . "'," . " " . $goal_type . "," . " " . $threshold . "," . " " . $reward_points . ", " . " " . $created_for . "," . " " . $achieved_by . "," . " '" . $start_date . "'," . " '" . $end_date . "'," . " '" . $date . "'),";

        }
        $query = substr($query, 0, -1);
        $query .= " ;";
        echo $query;
        $result = $db -> query($query);

        echo "Everythins is ok. Chillax";
    }

}
?>