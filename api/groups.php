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
		$user_array = array();
		/* select your_fields from your_table where your_condition order by 
oid desc limit 1; */
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$user_array['group_id']           = $row['group_id'];
			$user_array['creator']            = $row['creator'];
			$user_array['name']               = $row['name'];
			$user_array['description']        = $row['description'];
			$user_array['date_created']       = $row['date_created'];
			$user_array['total_duration']     = $row['total_duration'];
			$user_array['entity']             = $row['entity'];
		}
		
		$res = $app->response();
		
		$res['Content-Type'] = 'application/json';
		$res['X-Powered-By'] = 'Slim';
		
		echo json_encode($user_array);
	}
	if($app->request()->isPost()){
		
		$creator =  $app->request()->post('creator');
		$name = $app->request()->post('name');
		$description = $app->request()->post('description');
		$date = $app->request()->post('date_created');

		
		
		$query = "INSERT INTO entities(type) SELECT ".Tags::$newGroup.";";
		$result = $db->query($query);
		
		$query = "SELECT id FROM  entities ORDER BY id desc limit 1 ; "	;
		$result = $db->query($query);
		
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$entity_id = $row['id'];	
		
		$query ="INSERT INTO users(creator, name, description, date_created, entity)	SELECT".
		" ".$creator.",".
		" '".$name."',".
		" '".$description."',".
		" '".$date."',".
		" ".$entity_id.";";
		
		//echo "querry is: ".$query;
		$result = $db->query($query);
		
			
		
	}
?>