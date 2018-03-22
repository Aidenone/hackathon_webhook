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

	if(isset($array['key']) && $array['key'] &&
	   isset($array['appointment_date']) && $array['appointment_date'] &&
	   isset($array['description']) && $array['description'] ) {
		$key = $array['key'];
		$appointment_date = $array['appointment_date'];
		$description = $array['description'];
	} else {
		echo json_encode("Field missing");
		exit;
	}

	$bdd = $PDO->prepare('INSERT INTO appointment(id, user_key, appointment_date, description) VALUES ("", :user_key, :appointment_date, :description)');
	$bdd->execute(array(
		'user_key' => $key,
		'appointment_date' => $appointment_date,
		'description' => $description,
		)
    );
    echo json_encode("Success");

?>