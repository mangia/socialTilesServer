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
		
		if($app->request()->get(Tags::$op) == "user_groups"){
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
			
			echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
		}
		else if ($app->request()->get(Tags::$op) == "group_info"){
			$group = 	$app->request()->get(Tags::$group_id);		
			$query = "SELECT *  FROM groups WHERE  group_id =".$group."; "	;
			$result = $db->query($query);	
			$group_info = $result->fetch(PDO::FETCH_ASSOC);
			
			$query = "SELECT *  FROM users WHERE  user_id =".$group_info[Tags::$creator]."; "	;			
			$result = $db->query($query);		
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$group_info[Tags::$first_name] = $row[$first_name ];
			$group_info[Tags::$last_name] = $row[$last_name ];
			$group_info[Tags::$fbid] = $row[$fbid ];
			echo json_encode($group_info);	
		}
		else{
			echo $app->request()->get(Tags::$op);
			echo json_encode($app->request()->get());		
			echo strcmp($app->request()->get(Tags::$op), 'user_groups');
			echo $app->request()->get(Tags::$op) == 'user_groups' ;
			echo strcmp($app->request()->get(Tags::$op), "user_groups");
			echo $app->request()->get(Tags::$op) == "user_groups" ;
		}
		
		
	}
	if($app->request()->isPost()){
		
		$name =  $app->request()->post('name');
		$date_created = $app->request()->post('date_created');
		$description = $app->request()->post('description');
		$creator = $app->request()->post('creator');	
		
		$query = "INSERT INTO entities(type) SELECT ".Tags::$newGroup.";";
		$result = $db->query($query);
		
		
		$query = "SELECT id FROM  entities ORDER BY id desc limit 1 ; "	;
		$result = $db->query($query);
			
				
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$entity_id = $row['id'];	
		
		$query ="INSERT INTO groups (date_created, name, description,  creator, entity)	VALUES (".
		" '".$date_created."',".
		" '".$name."',".
		" '".$description."',".
		" ".$creator.",".
		" ".$entity_id.");";
		
		//echo "querry is: ".$query;
		$result = $db->query($query);
		
		
		$query = "SELECT *  FROM groups ORDER BY group_id desc limit 1 ; "	;
		$result = $db->query($query);
		$row = $result->fetch(PDO::FETCH_ASSOC);				
		
		$query = "INSERT INTO group_members(group_id, user_id, status) VALUES (".
		" ".$row['group_id'].", ".
		" ".$creator.", 1 );";		
		
		//echo "querry is: ".$query;
		$result = $db->query($query);
			
		echo json_encode($row);		
	}

?>


	