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
			
			$user_array['fbid']            = $row['fbid'];
			$user_array['name_first']      = $row['name_first'];
			$user_array['name_last']       = $row['name_last'];
			$user_array['total_score']     = $row['total_score'];
			$user_array['total_duration']  = $row['total_duration'];
			$user_array['num_achievments'] = $row['num_achievments'];
		}
		
		$res = $app->response();
		
		$res['Content-Type'] = 'application/json';
		$res['X-Powered-By'] = 'Slim';
		
		echo json_encode($user_array);
	}
	if($app->request()->isPost()){
		
		$fbid =  $app->request()->post('fbid');
		$name_first = $app->request()->post('name_first');
		$name_last = $app->request()->post('name_last');

		
		
		$query = "INSERT INTO entities(type) SELECT ".Tags::$newUser.";";
		$result = $db->query($query);
		
		$query = "SELECT id FROM  entities ORDER BY id desc limit 1 ; "	;
		$result = $db->query($query);
		
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$entity_id = $row['id'];	
		
		$query ="INSERT INTO users(name_first, name_last, fbid, entity)	SELECT".
		" '".$fbid."',".
		" '".$name_first."',".
		" '".$name_last."',".
		" ".$entity_id." WHERE NOT exists( SELECT * FROM users where fbid = '".$fbid."');";
		
		echo "querry is: ".$query;
		$result = $db->query($query);
		echo "Result is: ".$result;
	}
?>