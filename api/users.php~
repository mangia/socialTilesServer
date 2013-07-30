<?php
	require 'Slim/Slim.php';
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
		
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			
			$user_array[$row['fbid']]     = $row['fbid'];
			$user_array['username']        = $row['username'];
			$user_array['name_first']      = $row['name_first'];
			$user_array['name_last']       = $row['name_last'];
			$user_array['email_id']        = $row['email_id'];
			$user_array['picture']         = $row['picture'];
			$user_array['location']        = $row['location'];
			$user_array['age']             = $row['age'];
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
		$username = $app->request()->post('username');
		$name_first = $app->request()->post('name_first');
		$name_last = $app->request()->post('name_last');
		$email_id = $app->request()->post('email_id');
		$picture = $app->request()->post('picture');
		$location = $app->request()->post('location');
		$age = $app->request()->post('age');
		$total_score = $app->request()->post('total_score');
		$total_duration = $app->request()->post('total_duration');
		$num_achievments = $app->request()->post('num_achievments');		
		
		$query ="INSERT INTO users(username, name_first, name_last, email_id, picture, location , fbid, age, total_score, total_duration, num_achievments)	SELECT".
		"'".$fbid."'".
		"'".$username."'".
		"'".$name_first."'".
		"'".$name_last."'".
		"'".$email_id ."'".
		"'".$picture."'".
		"'".$location."'".
		"'".$age."'".
		"'".$total_score."'".
		"'".$total_duration."'".
		"'".$num_achievments."' WHERE NOT exists( SELECT * FROM users where fbid = '".$fbid."');";
		
		echo "querry is: ".$query;
		$result = $db->query($query);
		echo "Result is: ".$result;
	}
?>