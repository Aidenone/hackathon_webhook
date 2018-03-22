<?php
	$user = "dbo730037003";
	$pass = "hackathon";

	try {
    	$PDO = new PDO('mysql:host=db730037003.db.1and1.com;dbname=db730037003', $user, $pass);
	} catch (PDOException $e) {
	    echo "Erreur !: " . $e->getMessage() . "<br/>";
	    die();
	}
?>