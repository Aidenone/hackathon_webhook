<?php
	include 'db_connect.php';
	include 'manage_post.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Max-Age: 1000");
	header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
	header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
	header('Content-Type: application/json');

	$data = file_get_contents("php://input");
	if($data) {
		$array = manage_post($data);
	}

	if(isset($array['id']) && $array['id'] ) {
		$user_key = $array['id'];
		$bdd = $PDO->prepare('SELECT * FROM liaison WHERE family_key = "'.$user_key.'" ORDER BY created_at DESC');
		$bdd->execute(); 
		$data = $bdd->fetchAll();
	} else {
		echo json_encode("Missing ID");
	}
	if(sizeof($data)) {
		echo json_encode($data);
	} else {
		echo json_encode("No parent found");
	}
?>