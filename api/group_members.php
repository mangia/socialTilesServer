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
		echo  $query ;	
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
		$allPostVars = $app->request->post();
		echo $allPostVars;
		//echo "querry is: ".$query;
		$result = $db->query($query);
		//$row = $result->fetch(PDO::FETCH_ASSOC);
		echo  $query ;	
		//echo json_encode($row);		
	}

?>


	