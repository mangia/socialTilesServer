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
		$group	= $app->request()->get(Tags::$group_id);
		$query = "SELECT *  FROM group_members gm WHERE gm.group_id='".$group."'";
		$result = $db->query($query);		
		$row = $result->fetch(PDO::FETCH_ASSOC);
		echo json_encode($row);
		
	}
	if($app->request()->isPost()){
		$user  	= $app->request()->get(Tags::$user_id);
		$group	= $app->request()->get(Tags::$group_id);	
	
		
		$query = "INSERT INTO group_members(group_id, user_id, status) VALUES (".
		" ".$group.", ".
		" ".$user.", 0 );";		
		
		//echo "querry is: ".$query;
		$result = $db->query($query);
		//$row = $result->fetch(PDO::FETCH_ASSOC);
				
		//echo json_encode($row);		
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


	