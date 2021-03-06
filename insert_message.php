<?php
	
	include 'db_connect.php';
	include 'manage_post.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Max-Age: 1000");
	header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
	header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
	header('Content-Type: application/json');

	//$data = '{"id":146380627,"name":"az","prenom":"az","email":"az@az.azazaz","password":"a"}';
	
	$data = file_get_contents("php://input");
	if($data) {
		$array = manage_post($data);
	}

	if(isset($array['status']) && $array['status'] &&
	   isset($array['content']) && $array['content'] &&
	   isset($array['user_key']) && $array['user_key'] ) {
		$status = $array['status'];
		$content = $array['content'];
		$user_key = $array['user_key'];
	} else {
		echo json_encode("Field missing");
		exit;
	}

	$bdd = $PDO->prepare('INSERT INTO message(id, user_key, status, content) VALUES ("", :user_key, :status, :content)');
	$bdd->execute(array(
		'user_key' => $user_key,
		'status' => $status,
		'content' => $content,
		)
    );
    echo json_encode("Success");

?>