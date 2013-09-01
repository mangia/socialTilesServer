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

		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$user_array['post_id']		 = $row['post_id'];
			$user_array['creator']		 = $row['creator'];
			$user_array['posted_to']	 = $row['posted_to'];
			$user_array['post_date']	 = $row['post_date'];
			$user_array['post_text']	 = $row['post_text'];
		}
		
		$res = $app->response();
		
		$res['Content-Type'] = 'application/json';
		$res['X-Powered-By'] = 'Slim';
		
		echo json_encode($user_array);
		
		if($app->request()->get(Tags::$op) == Tags::$group){
			$entity = $app->request()->get(Tags::$posted_to);
			$query = "SELECT * from posts WHERE posted_to =".$entity." ORDER BY post_date desc limit 40 ; ";		
			$result = $db->query($query);
			
			echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));	
		}	

		if($app->request()->get(Tags::$op) == Tags::$event){
			$entity = $app->request()->get(Tags::$posted_to);
			$query = "SELECT * from posts WHERE posted_to =".$entity." ORDER BY post_date desc limit 40 ; ";			
			$result = $db->query($query);
			
			echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));		
		}
		
		if($app->request()->get(Tags::$op) == Tags::$user){
					
		}	
		
		
	}
	if($app->request()->isPost()){
		
		$creator =		$app->request()->post('creator');
		$post_to =		$app->request()->post('post_to');
		$post_text = 	$app->request()->post('post_text');
		$post_date = 	$app->request()->post('post_date');

		
		$query ="INSERT INTO posts(creator, post_to, post_text, post_date)	SELECT".
		" ".$creator.",".
		" ".$post_to.",".
		" '".$post_text."',".
		" '".$post_date."';";
		
		echo "querry is: ".$query;
		$result = $db->query($query);
		
			
		
	}
?>