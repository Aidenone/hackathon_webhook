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

	if(isset($array['email']) && $array['email'] &&
	   isset($array['password']) && $array['password'] ) {
		$mail = $array['email'];
		$password = $array['password'];
	} else {
		echo json_encode("Missing field");
		exit;
	}

	$bdd = $PDO->prepare('SELECT password, id_key, status FROM user WHERE mail = "'.$mail.'"');
	$bdd->execute(); 
	$row = $bdd->fetch();

	if($row) {
		$hash = $row['password'];
		if (password_verify($password, $hash)) {
			$response = array("Response" => "Valid user", "Key" => $row['id_key'], "Status" => $row['status']);
		    echo json_encode($response);
		} else {
		    echo json_encode("Invalid user");
		}
	} else {
		echo json_encode("Invalid email");
	}

?>