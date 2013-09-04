<?php
	require 'Slim/Slim.php';
	require 'tags.php';
	
	\Slim\Slim::registerAutoloader();

	$app = new \Slim\Slim();

	$dsn = "pgsql:"
			. "host=ec2-54-227-238-31.compute-1.amazonaws.com;"
    		. "dbname=d3r468400g680j;"
    		. "user=wzcdebwgjfehyz;"
    		. "port=5432;"
    		. "sslmode=require;"
    		. "password=U2hPQsSC7_oM4bV-Fp7NiRy9j7 ";
	$db = new PDO($dsn); 
	
	if($app->request()->isGet()){
		if($app->request()->get(Tags::$op) == "created_for"){
			$entity =  $app->request()->get(Tags::$entity);	

			$query = "SELECT *  ".
			"  FROM goals WHERE  created_for =".$entity." ;";
			//echo $query;			
			$result = $db->query($query);
			echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
		}		
		else if ($app->request()->get(Tags::$op) == "achieved_by"){		
			$user_id =$app->request()->get(Tags::$user_id);
			$query = "SELECT *  FROM goals WHERE achieved_by=".$goal_id.";";
			$result = $db->query($query);
			//echo $query;
			echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
		}
	}
	if($app->request()->isPost()){
		

		$name               	= $app->request()->post('name');
		$goal_type				= $app->request()->post('goal_type');
		$threshold				= $app->request()->post('threshold');
    	$reward_points     	= $app->request()->post('reward_points');
		$created_for			= $app->request()->post('creator');
		$achieved_by			= $app->request()->post('achieved_by');
		$start_date        	= $app->request()->post('start_date');
		$end_date           = $app->request()->post('end_date');		
		$date               	= $app->request()->post('date_created');
		
		
		$query = "INSERT INTO entities(type) SELECT ".Tags::$newEvent.";";
		$result = $db->query($query);
		
		$query = "SELECT id FROM  entities ORDER BY id desc limit 1 ; "	;
		$result = $db->query($query);
		
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$entity_id = $row['id'];	
		
		$query ="INSERT INTO goals (name, goal_type, threshold, reward_points, created_for, achieved_by, start_date, end_date, date_created)	SELECT ".
		" ".$name.",".
		" ".$goal_type.",".
		" ".$threshold.",".
		" ".$reward_points.", ".
		" ".$created_for.",".
		" ".$achieved_by."'".
		" '".$start_date."',".
		" '".$end_date."',".
		" '".$date."';";
		
		//echo "querry is: ".$query;
		$result = $db->query($query);
		
		$query = "SELECT *  FROM goals ORDER BY goal_id desc limit 1 ; "	;
		$result = $db->query($query);
		$row = $result->fetch(PDO::FETCH_ASSOC);	
				
		echo json_encode($row);
		
	}
?>