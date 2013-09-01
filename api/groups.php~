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
		
		if(strcmp($app->request()->get(Tags::$op), 'user_groups') == 0){
			$user = 	$app->request()->get(Tags::$user_id);		
			$query = "SELECT *  FROM group_members WHERE  user_id =".$user."; "	;
			$result = $db->query($query);
			
			$query = "SELECT * from groups WHERE ";
			$flag = true;
			while ($row = $result->fetch(PDO::FETCH_ASSOC)){
				if(!$flag){
					$query .= " OR ";
				}
				else{
					$flag = false;			
				}			
				$query.= "group_id = ".$row['group_id']."";
			}
			$query.=";";
			$result = $db->query($query);
			
			echo json_encode($result);
		}
		
		
	}
	if($app->request()->isPost()){
		
		$name =  $app->request()->post('name');
		$date_created = $app->request()->post('date_created');
		$description = $app->request()->post('description');
		$creator = $app->request()->post('creator');
			
		echo $app->request->post();		
		
		$query = "INSERT INTO entities(type) SELECT ".Tags::$newGroup.";";
		$result = $db->query($query);
		
		$query = "SELECT id FROM  entities ORDER BY id desc limit 1 ; "	;
		$result = $db->query($query);
		
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$entity_id = $row['id'];	
		
		$query ="INSERT INTO groups(date_created, name, description,  creator, entity)	SELECT".
		" '".$date_created."',".
		" '".$name."',".
		" '".$description."',".
		" ".$creator.",".
		" ".$entity_id.";";
		
		echo "querry is: ".$query;
		$result = $db->query($query);
		
		$query = "SELECT *  FROM groups ORDER BY group_id desc limit 1 ; "	;
		$result = $db->query($query);
		$row = $result->fetch(PDO::FETCH_ASSOC);				
		
		$query = "INSERT INTO group_members(group_id, user_id, status) SELECT".
		" ".$row['group_id'].", ".
		" ".$creator.", 1 ;";		
		echo "querry is: ".$query;
		
		echo json_encode($row);		
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


	