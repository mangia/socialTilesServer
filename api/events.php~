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
		$fbid =$app->request()->get('fbid');
		$query = "SELECT u.username, u.name_first, u.name_last, u.email_id, u.picture, u.location , u.fbid, u.age, u.total_score, u.total_duration, u.num_achievments  FROM users u WHERE u.fbid='".$fbid."'";
		$result = $db->query($query);
			//echo $query;
		echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
	}
	if($app->request()->isPost()){
		
		$creator                  = $app->request()->post('creator');
		$name                     	= $app->request()->post('name');
		$type_of_participants   = $app->request()->post('type_of_participants');
		$date                     	= $app->request()->post('date_created');
		$start_date              	= $app->request()->post('start_date');
		$end_date                	= $app->request()->post('end_date');
		$reward_text            	= $app->request()->post('reward_text');
    	$reward_points         	= $app->request()->post('reward_points');
		
		
		$query = "INSERT INTO entities(type) SELECT ".Tags::$newEvent.";";
		$result = $db->query($query);
		
		$query = "SELECT id FROM  entities ORDER BY id desc limit 1 ; "	;
		$result = $db->query($query);
		
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$entity_id = $row['id'];	
		
		$query ="INSERT INTO events (creator,name,type_of_participants,reward_text,date_created,start_date,end_date,reward_points, entity)	SELECT ".
		" ".$creator.",".
		" '".$name."',".
		" ".$type_of_participants.",".
		" '".$reward_text."',".
		" '".$date."',".
		" '".$start_date."',".
		" '".$end_date."',".
		" ".$reward_points.", ".
		" ".$entity_id.";";
		
		//echo "querry is: ".$query;
		$result = $db->query($query);
		
		$query = "SELECT *  FROM events ORDER BY event_id desc limit 1 ; "	;
		$result = $db->query($query);
		$row = $result->fetch(PDO::FETCH_ASSOC);	
		
		echo json_encode($row);
		
	}
?>