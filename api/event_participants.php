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
		if($app->request()->post(Tags::$op) == "single"){
			$participant  	= $app->request()->post('participant');
			$event	= $app->request()->post(Tags::$event_id );	
			$query = "SELECT * FROM events WHERE event_id =".$event." ;";
			$result = $db->query($query);
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			if($row['type_of_participants'] == 0){
				$query = "INSERT INTO event_participants (event, participant, status) VALUES (".
				" ".$event.", ".
				" ".$participant.", 1 );";		
				echo $query ;
				$result = $db->query($query);
			}
			else {

				$query = "SELECT * FROM group_members gm WHERE gm.group_id =".$participant." ;";
				$result = $db->query($query);
				
								
				$query = "INSERT INTO event_participants (event, participant, status, group_id ) VALUES ";
				while ($row = $result->fetch(PDO::FETCH_ASSOC)){
					$query .= "("." ".$event.", "." ".$row[Tags::$user_id].", 1 ".$participant."),";
				}
				$query = substr($query, 0, -1);
				$query .= " ;";
				$result = $db->query($query);
			}
			
		}
		else if($app->request()->post(Tags::$op) == "multiple"){
			$participantss = $app->request()->post('participants') ;
			$event	= $app->request()->post(Tags::$event_id );	
			$participantss = json_decode($participantsss, true);
			//var_dump($user_ids); 
			//echo $user_ids;
			
						
			$query = "SELECT * FROM events WHERE event_id =".$event." ;";
			$result = $db->query($query);
			$row = $result->fetch(PDO::FETCH_ASSOC);
			if($row['type_of_participants'] == 0){
				
				$query = "INSERT INTO event_participants(event, participant, status) VALUES ";
				foreach ($participants as $i => $value){
					//echo $user_ids[$i];
					$query .= " ( ".$event.", ". $participantss[$i].", 1 ),";		
					
				}
				$query = substr($query, 0, -1);
				$query .= " ;";
				echo $query;
				$result = $db->query($query);	
			}
			else{
				$query = "SELECT * FROM group_members gm WHERE ";
				$flag = true;
				foreach ($participants as $i => $value){
					if(!$flag){
						$query .= " OR ";
					}
					else{
						$flag = false;			
					}	
					$query .=	"gm.group_id =".$participantss[$i]." "; 					
				}		
				$query .= ";";
				$result = $db->query($query);	
				
				$query = "INSERT INTO event_participants (event, participant, status, group_id ) VALUES ";
				while ($row = $result->fetch(PDO::FETCH_ASSOC)){
					$query .= "("." ".$event.", "." ".$row[Tags::$user_id].", 0, ".$row[Tags::$group_id]."),";
				}
				$query = substr($query, 0, -1);
				$query .= " ;";
				echo $query;
				$result = $db->query($query);	
			}
			
		}
		echo "ok";
	}

?>


	