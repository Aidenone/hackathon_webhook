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

	if(isset($array['id']) && $array['id'] &&
	   isset($array['name']) && $array['name'] &&
	   isset($array['prenom']) && $array['prenom'] &&
	   isset($array['email']) && $array['email'] &&
	   isset($array['password']) && $array['password'] ) {
		$key = $array['id'];
		$lastname = $array['name'];
		$firstname = $array['prenom'];
		$mail = $array['email'];
		$password = password_hash($array['password'], PASSWORD_DEFAULT);
	} else {
		echo json_encode("Field missing");
		exit;
	}

	$bdd = $PDO->prepare('INSERT INTO user(id, id_key, firstname, lastname, password, mail) VALUES ("", :id_key, :firstname, :lastname, :password, :mail)');
	$bdd->execute(array(
		'id_key' => $key,
		'firstname' => $firstname,
		'lastname' => $lastname,
		'mail' => $mail,
		'password' => $password,
		)
    );
    echo json_encode("Success");
?>