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
 * @return all the feedbacks for this user
 */
if ($app -> request() -> isGet()) {
    $user_id = $app -> request() -> get(Tags::$user_id);
    $query = "SELECT * FROM feedback  WHERE user_id=" . $user_id . " ORDER BY date_created DESC ";
    $result = $db -> query($query);
    //echo $query;
    echo json_encode($result -> fetchAll(PDO::FETCH_ASSOC));
}

/**
 * adds the new feedback and makes sure to update the hiscore if needed and update all the pending goals and events
 * 
 * @param user_id
 * @param gamename
 * @param date
 * @param points
 * @param miss
 * @param duration
 * @param winner
 * @param level
 * @param size
 * @param score
 * 
 */

if ($app -> request() -> isPost()) {
    $response_array = array();
    $user_id = $app -> request() -> post(Tags::$user_id);
    $gamename = $app -> request() -> post('gamename');
    $date = $app -> request() -> post('date_created');
    $points = $app -> request() -> post('points');
    $miss = $app -> request() -> post('miss');
    $duration = $app -> request() -> post('duration');
    $winner = $app -> request() -> post('winner');
    $level = $app -> request() -> post('level');
    $size = $app -> request() -> post('size');
    $score = $app -> request() -> post('score');

    $response_array = array();

    //echo "Received post request";

    $query = "INSERT INTO feedback (user_id, gamename, date_created, points, miss, duration, winner, level, size, score) SELECT " . $user_id . " ,'" . $gamename . "','" . $date . "'," . $points . "," . $miss . "," . $duration . "," . $winner . "," . $level . "," . $size . "," . $score . " WHERE exists( SELECT * FROM users s WHERE s.user_id='" . $user_id . "');";
    echo "querry is: ".$query;
    $result = $db -> query($query);
    //echo "Result is: ".$result;

    $query = "SELECT *  FROM feedback ORDER BY feedback_id desc limit 1 ; ";
    $result = $db -> query($query);
    $response_array['feedback'] = $result -> fetch(PDO::FETCH_ASSOC);

    $query = "UPDATE users SET total_score = total_score +" . $score . " ,total_duration = total_duration+" . $duration . " WHERE user_id='" . $user_id . "'";
    //echo "querry is: ".$query;
    $result = $db -> query($query);

    $response = "";

    $query = "SELECT h.highscore FROM highscores h WHERE h.user_id = '" . $user_id . "' AND h.gamename = '" . $gamename . "';";
    //echo "querry is: ".$query;
    $result = $db -> query($query);

    if ($result -> rowCount() > 0) {// checks if there is another highscore of this game
        $row = $result -> fetch(PDO::FETCH_ASSOC);
        $highscore = $row['highscore'];
        if ($highscore < $score) {
            $query = "UPDATE highscores SET highscore=" . $score . " WHERE user_id='" . $user_id . "' AND gamename LIKE '" . $gamename . "' ;";
            //echo "querry is: ".$query;
            $result = $db -> query($query);
            $response = "newHighscore";
        } else {
            $response = "noHighscore";
        }
    } else {
        $query = "INSERT INTO highscores (user_id, gamename, highscore)  VALUES('" . $user_id . "', '" . $gamename . "', " . $score . " )";
        $result = $db -> query($query);
        $response = "newEntry";
    }

    $goals_array = array();
    $response_array['highscores'] = $response;
    $response_array['has_goals'] = FALSE;

    $query = "SELECT * from goals WHERE achieved_by =" . $user_id . " AND now()::date >= start_date AND now()::date<= end_date AND is_finished = 0;";
    $result = $db -> query($query);
    //echo $query;
    $rows = $result -> fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
    foreach ($rows as $row) {
        // echo "I am in the goals loop";
        $goal_id   = $row['goal_id'];
        $goal_type = $row['goal_type'];
        $threshold = $row['threshold'];
        $currently = $row['currently'];
        $goal_gamename = $row['game_name'];
        $is_finished = $row['is_finished'];
        $is_finished = 0;
        
        //echo "game name is ".$gamename." and goal game name is ".$goal_gamename." and are they equal ? ".($gamename == $goal_gamename)."";
        
        if ($goal_type == 2) { //goal type 2 are the events
            //echo "i am here in goal type 2";
            $currently = $currently + $score;
            $query = "UPDATE goals SET currently=" . $currently . " WHERE achieved_by =" . $user_id . " AND goal_id =".$goal_id.";";
            //echo $query;
            $result = $db -> query($query);

        } 
        if ($gamename == $goal_gamename) {
            // echo "i am here in game names equaliti";
            if ($goal_type == 0) {// gather score
                $currently = $currently + $score;
            } else if ($goal_type == 1) {//play for
                $currently = $currently + $duration;
            }
            if ($currently >= $threshold) {
                $response_array['has_goals'] = TRUE;
                $is_finished = 1;
                $row['currently'] = $currently;
                $row['is_finished'] = 1;
                $goals_array[] = $row;
            }

            $query = "UPDATE goals SET currently =" . $currently . ", is_finished = " . $is_finished . " WHERE achieved_by =" . $user_id . " AND goal_id  =".$goal_id." ;";
            //echo $query;
            $result = $db -> query($query);
        }

    }

    if ($response_array['has_goals'] == TRUE) {
        $response_array['goals'] = $goals_array;
    }

    echo json_encode($response_array);
}
?>