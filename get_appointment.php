<?php
	include 'db_connect.php';
	include 'manage_post.php';

	header('Content-Type: application/json');

	$data = file_get_contents("php://input");
	if($data) {
		$array = manage_post($data);
	}
	if(isset($array['id']) && $array['id'] ) {
		if(isset($array['date']) && $array['date'] ) {
			$date = $array['date'];
			$user_key = $array['id'];
			$bdd = $PDO->prepare('SELECT * FROM appointment WHERE appointment_date = "'.$date.'" AND user_key = "'.$user_key.'" ORDER BY created_at DESC');
			$bdd->execute(); 
			$data = $bdd->fetchAll();
		} else {
			$bdd = $PDO->prepare('SELECT * FROM appointment WHERE user_key = "'.$user_key.'" ORDER BY created_at DESC');
			$bdd->execute(); 
			$data = $bdd->fetchAll();
		}
	} else {
		echo json_encode("Missing ID");
	}
	if(sizeof($data)) {
		echo json_encode($data);
	} else {
		echo json_encode("No appointment found");
	}
?>