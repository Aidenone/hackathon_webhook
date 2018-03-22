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
	} else {
		echo json_encode("No post");
		exit;
	}

	if(isset($array['id']) && $array['id'] &&
	   isset($array['idParent']) && $array['idParent'] &&
	   isset($array['nickname']) && $array['nickname'] ) {

		$uploaddir = '/photos/';
		$uploadfile = $uploaddir . $array['idParent'];

		/*if(isset($_FILES)) {
			if (!move_uploaded_file($_FILES['test']['name'], $uploadfile)) {
			    echo json_encode($_FILES);
			    exit;
			}
		}*/

		$parent_key = $array['idParent'];
		$family_key = $array['id'];
		$nickname = $array['nickname'];
	} else {
		echo json_encode("Field missing");
		exit;
	}

	$bdd = $PDO->prepare('INSERT INTO liaison(id, parent_key, family_key, nickname, image_name) VALUES ("", :parent_key, :family_key, :nickname, :image_name)');
	$bdd->execute(array(
		'parent_key' => $parent_key,
		'family_key' => $family_key,
		'nickname' => $nickname,
		'image_name' => $uploadfile,
		)
    );
    echo json_encode("Success");

?>