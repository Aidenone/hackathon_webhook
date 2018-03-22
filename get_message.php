<?php
	include 'db_connect.php';
	include 'manage_post.php';

	header('Content-Type: application/json');

	$data = file_get_contents("php://input");
	if($data) {
		$array = manage_post($data);
	}

	if(isset($array['id']) && $array['id']){
		$user_key = $array['id'];
		$bdd = $PDO->prepare('SELECT * FROM message INNER JOIN liaison ON message.user_key = liaison.parent_key WHERE message.user_key = "'.$user_key.'"');
		$bdd->execute(); 
		$data = $bdd->fetchAll();
	} else {
		echo json_encode("Missing ID");
		exit;
	}
	

	if(sizeof($data)) {
		echo json_encode($data);
		exit;
	} else {
		echo json_encode("No message found");
		exit;
	}
?>