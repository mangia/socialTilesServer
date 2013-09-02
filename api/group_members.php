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
		
		//echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));		
				
		if($app->request()->get(Tags::$op) == Tags::$user){
			$query = "SELECT *  FROM users WHERE ";			
			$flag = true;			
			//while ($row = $result->fetch(PDO::FETCH_ASSOC)){
				//echo json_encode($row);
				/*if(!$flag){
					$query .= " OR ";
				}
				else{
					$flag = false;			
				}
				
				$query .= " user_id =".$row[Tags::$user_id]."";			
			}
			
			$query.=";";
			//echo $query;
			$result = $db->query($query);
			
			echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));*/
			$query = "SELECT DISTINCT ON (u.user_id) * FROM group_members gm, users u WHERE gm.group_id='".$group."' AND gm.status = 1 AND gm.user_id = u.user_id";
			//echo $query;
						
			$result = $db->query($query);			
			echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
		}
		else{
			$query = "SELECT *  FROM group_members gm WHERE gm.group_id='".$group."' AND gm.status = 1";
			$result = $db->query($query);		
			echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));	
		}

		
	}
	if($app->request()->isPost()){
		if($app->request()->post(Tags::$op) == "sinngle"){
			$user  	= $app->request()->post(Tags::$user_id);
			$group	= $app->request()->post(Tags::$group_id);	
			$query = "INSERT INTO group_members(group_id, user_id, status) VALUES (".
			" ".$group.", ".
			" ".$user.", 0 );";		
			$result = $db->query($query);
		}
		else if($app->request()->post(Tags::$op) == "multiple"){
			$user_idss = $app->request()->post(Tags::$user_ids) ;
			$group	= $app->request()->post(Tags::$group_id);
			$user_ids = json_decode($user_idss, true);
			//var_dump($user_ids); 
			//echo $user_ids;

			foreach ($user_ids as $i => $value){
				//echo $user_ids[$i];
				$query = "INSERT INTO group_members(group_id, user_id, status) VALUES (".
			" ".$group.", ".
			" ". $user_ids[$i].", 0 );";		
			$result = $db->query($query);
			}
			
		}
		echo "ok";
	}

?>


	