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
		
		$event	= $app->request()->get(Tags::$event_id );	
		$query = "SELECT * FROM events WHERE event_id =".$event." ;";
		$result = $db->query($query);
		$row = $result->fetch(PDO::FETCH_ASSOC);		
		
		if($row['type_of_participants'] == 0){
			if($app->request()->get(Tags::$op) == Tags::$user){
				$query = "SELECT DISTINCT ON (u.user_id) * FROM event_participants ep, users u WHERE ep.event='".$event."' AND ep.status = 1 AND ep.participant = u.user_id";
				//echo $query;				
				$result = $db->query($query);		
				echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));	
			}
			else{
				$query = "SELECT *  FROM event_participants ep WHERE ep.event='".$event."' AND ep.status = 1";
				$result = $db->query($query);		
				echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));	
			}
		}
		else {
			$group	= $app->request()->get(Tags::$group_id);
			if($app->request()->get(Tags::$op) == Tags::$user){
				$query = "SELECT * FROM event_participants ep, users u, groups g WHERE ep.event=".$event." AND ep.status = 1 AND ep.participant = u.user_id AND ep.group_id  IS NOT NULL AND ep.group_id = g.group_id ";			
				//echo $query;				
				$result = $db->query($query);		
				echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));				
			}
			else{
				$query = "SELECT *  FROM event_participants ep WHERE ep.event='".$event."' AND ep.status = 1";
				$result = $db->query($query);		
				echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));	
			}
		}
			
	}
	if($app->request()->isPost()){
	    
        $user_ids = array();
        
		if($app->request()->post(Tags::$op) == "single"){
			$participant  	= $app->request()->post('participant');
			$user_ids[] = $participant;
		    $event	= $app->request()->post(Tags::$event_id );	
			$query = "SELECT * FROM events WHERE event_id =".$event." ;";
			$result = $db->query($query);
			$row = $result->fetch(PDO::FETCH_ASSOC);
			//echo $row['type_of_participants'] ;
			if($row['type_of_participants'] == 0){
				$query = "INSERT INTO event_participants (event, participant, status) VALUES (".
				" ".$event.", ".
				" ".$participant.", 1 );";		
				//echo $query ;
				$result = $db->query($query);
			}
			else {

				$query = "SELECT * FROM group_members gm WHERE gm.group_id =".$participant." ;";
				$result = $db->query($query);
				//echo $query;
							
				$query = "INSERT INTO event_participants (event, participant, status, group_id ) VALUES ";
				while ($row = $result->fetch(PDO::FETCH_ASSOC)){
					$query .= "("." ".$event.", "." ".$row[Tags::$user_id].", 1, ".$participant."),";
				}
				$query = substr($query, 0, -1);
				$query .= " ;";
				//echo $query;
				$result = $db->query($query);
			}
			
		}
		else if($app->request()->post(Tags::$op) == "multiple"){
			$participantss = $app->request()->post('participants') ;
			$event	= $app->request()->post(Tags::$event_id );	
			$participants = json_decode($participantss, true);
			//var_dump($participants); 
			//echo $participants;

			$query = "SELECT * FROM events WHERE event_id =".$event." ;";
			$result = $db->query($query);
			$row = $result->fetch(PDO::FETCH_ASSOC);
		
		
			if($row['type_of_participants'] == 0){
				
				$query = "INSERT INTO event_participants(event, participant, status) VALUES ";
				foreach ($participants as $i => $value){
					//echo $participantss[$i];
					$user_ids [] = $participants[$i];
					$query .= " ( ".$event.", ". $participants[$i].", 0 ),";		
					
				}
				$query = substr($query, 0, -1);
				$query .= " ;";
				//echo $query;
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
					$query .=	"gm.group_id =".$participants[$i]." "; 					
				}		
				$query .= ";";
				$result = $db->query($query);	
				

				$queries = array();
				while ($row = $result->fetch(PDO::FETCH_ASSOC)){
					$query1 = "INSERT INTO event_participants (event, participant, status, group_id ) VALUES ";
					$query1 .= "("." ".$event.", "." ".$row[Tags::$user_id].", 0, ".$row[Tags::$group_id].");";
					$user_ids = $row[Tags::$user_id] ;
					$queries[] = $query1; 

				}
			
				$i = 0;
				foreach($queries as $q)	{
					//echo $q;
					$result = $db->query($q);				
				}				
			}
			
		}

        $quesry = "SELECT * from event_participants ep, events e WHERE ep.event = e.event_id AND e.event_id =".$app->request()->post(Tags::$event_id )." ;";
        $result = $db->query($quesry);
        
        $query = "INSERT INTO goals (name, game_name, goal_type, threshold, reward_points, created_for, achieved_by, start_date, end_date, date_created)   VALUES";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)){
            $query .= "('".$row['name']."',"."'all',"." 2 ,"." -1 ,"." ".$row['reward_points'].", "." ".$row['entity'].","." ".$row['participant'].","." '". $row['start_date']."',"." '".$row['end_date']."',"." CURRENT_DATE),";

        }
        $query = substr($query, 0, -1);
        $query .= " ;";
        echo $query;
        $result = $db -> query($query);
        
		$response = array('response' => 'ok');
        echo json_encode($response);
	}

?>


	