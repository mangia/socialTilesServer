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
		
		$user_id	= $app->request()->get(Tags::$user_id);
		$user_ids = array();	
		
		$query = "SELECT *  FROM friends  WHERE from_user='".$user_id."' AND status = 1";
		$result = $db->query($query);	
		while ($row = $result->fetch(PDO::FETCH_ASSOC)){	
			$user_ids[] = $row['to_user'];	
		}
		
			
		$query = "SELECT *  FROM friends  WHERE to_user='".$user_id."' AND status = 1";
		$result = $db->query($query);	
		while ($row = $result->fetch(PDO::FETCH_ASSOC)){	
			$user_ids[] = $row['from_user'];	
		}
		
		$query = "SELECT *  FROM users  WHERE ";
		$flag = true;
		foreach($user_ids as  $i => $value){
			if(!$flag){
				$query .= " OR ";
			}
			else{
				$flag = false;			
			}	
			$query .= " user_id = ".$user_ids[$i]." ";			
		}	
		$result = $db->query($query);
		
		echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
	
	}
	if($app->request()->isPost()){
		$from_user	= $app->request()->post(Tags::$from_user);
		$to_user	= $app->request()->post(Tags::$to_user);	
	
		
		$query = "INSERT INTO friends (from_user, to_user, status) VALUES (".
		" ".$from_user.", ".
		" ".$from_user.", 0 );";		
		//$allPostVars = $app->request()->post();
		//echo json_encode($allPostVars);
		$result = $db->query($query);
	}

?>


	