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

	if(isset($array['idSenior']) && $array['idSenior'] &&
	   isset($array['datepicker']) && $array['datepicker'] &&
	   isset($array['nom']) && $array['nom'] &&
	   isset($array['content']) && $array['content'] ) {
		$key = $array['idSenior'];
		$appointment_date = $array['datepicker'].":30";
		$description = $array['content'];
		$title = $array['nom'];
	} else {
		echo json_encode($array);
		exit;
	}

	$bdd = $PDO->prepare('INSERT INTO appointement(id, user_key, appointment_date, title, description) VALUES ("", :user_key, :appointment_date, :title, :description)');
	$bdd->execute(array(
		'user_key' => $key,
		'appointment_date' => $appointment_date,
		'description' => $description,
		'title' => $title,
		)
    );
    echo json_encode("Success");

?>