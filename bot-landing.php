<?php
	include 'db_connect.php';

	function processMessage($update) {
		if (substr($update["result"]["action"], 0, 9) === 'smalltalk') return false;
		$user_key = $update["result"]["contexts"][0]["name"];
		switch ($update["result"]["action"]) {
			case "AppointmentList":
				//recup key user
				//requête rdv du jour
				$appointments = get_today_appointment($user_key);
				if(sizeof($appointments)){
					$text = "Vos rdv de la journée :";
					foreach ($appointments as $event) {
						$text .= "<br>".$event["description"]."<br> A ".substr($event["appointment_date"], -8);
						$text .= "<br>";
					}
				} else {
					$text = "Pas de rendez-vous aujourd'hui, reposez vous";
				}
				
				if(sizeof($appointments)){
					$speech = "Vos rdv de la journée :";
					foreach ($appointments as $event) {
						$speech .= "<br>".$event["description"]."<br> A ".substr($event["appointment_date"], -8);
						$speech .= "<br>";
					}
				} else {
					$speech = "Pas de rendez-vous aujourd'hui, reposez vous";
				}

				sendMessage(array(
		            "source" => $update["result"]["source"],
		            "speech" => $speech,
		            "displayText" => $text,
		            "contextOut" => array()
		        ));
			break;

			case "BirthdayInfo":
				$birthdays = get_today_birthdays($user_key);
				if(sizeof($birthdays)){
					$text = "Les anniversaire 🎂:";
					foreach ($birthdays as $event) {
						$age = date("Y") - substr($event["birthdate"], 0, 4);
						$text .= "<br>".$event["firstname"]." ".$event["lastname"]." fête ses ".$age." ans, pensez à lui souhaiter";
						$text .= "<br>";
					}
				} else {
					$text = "Pas d'anniversaire aujourd'hui";
				}
				
				if(sizeof($birthdays)){
					$speech = "Les anniversaire :";
					foreach ($birthdays as $event) {
						$age = date("Y") - substr($event["birthdate"], 0, 4);
						$speech .= "<br>".$event["firstname"]." ".$event["lastname"]." fête ses ".$age." ans, pensez à lui souhaiter";
						$speech .= "<br>";
					}
				} else {
					$speech = "Pas d'anniversaire aujourd'hui";
				}

				sendMessage(array(
		            "source" => $update["result"]["source"],
		            "speech" => $speech,
		            "displayText" => $text,
		            "contextOut" => array()
		        ));
			break;

			case "MemoryLoss":
				$memos = get_memos($user_key);
				if(sizeof($memos)){
					$text = "Vos mémos 📝:<br>";
					foreach ($memos as $memo) {
						$text .= "<br>".$memo["title"]."<br>".$memo["content"];
						$text .= "<br>";
					}
				} else {
					$text = "Rien de particulier à se rappeler";
				}
				
				if(sizeof($memos)){
					$speech = "Vos mémos :<br>";
					foreach ($memos as $memo) {
						$speech .= "<br>".$memo["title"]."<br>".$memo["content"];
						$speech .= "<br>";
					}
				} else {
					$speech = "Rien de particulier à se rappeler";
				}

				sendMessage(array(
		            "source" => $update["result"]["source"],
		            "speech" => $speech,
		            "displayText" => $text,
		            "contextOut" => array()
		        ));
			break;
			
			default:
				sendMessage(array(
		            "source" => $update["result"]["source"],
		            "speech" => "default webhook response",
		            "displayText" => "default webhook response",
		            "contextOut" => array()
		        ));
			break;
		}
	}
	 
	function sendMessage($parameters) {
	    echo json_encode($parameters);
	}

	function get_today_appointment($user_key) {
		$user = "dbo730037003";
		$pass = "hackathon";
		$today = date("Y-m-d");
	    $PDO = new PDO('mysql:host=db730037003.db.1and1.com;dbname=db730037003', $user, $pass);
		$bdd = $PDO->prepare('SELECT * FROM appointement WHERE user_key = "'.$user_key.'" AND appointment_date LIKE "%'.$today.'%" ORDER BY created_at DESC ');
		$bdd->execute(); 
		$data = $bdd->fetchAll();
		for ($i=0; $i < sizeof($data); $i++) { 
			$data[$i]['description'] = utf8_encode($data[$i]['description']);
		}
		return $data;
	}

	function get_today_birthdays($user_key) {
		$user = "dbo730037003";
		$pass = "hackathon";
		$today = date("m-d");
	    $PDO = new PDO('mysql:host=db730037003.db.1and1.com;dbname=db730037003', $user, $pass);
		$bdd = $PDO->prepare('SELECT * FROM birthday WHERE user_key = "'.$user_key.'" AND birthdate LIKE "%'.$today.'%" ORDER BY created_at DESC ');
		$bdd->execute(); 
		$data = $bdd->fetchAll();
		return $data;
	}

	function get_memos($user_key) {
		$user = "dbo730037003";
		$pass = "hackathon";
	    $PDO = new PDO('mysql:host=db730037003.db.1and1.com;dbname=db730037003', $user, $pass);
		$bdd = $PDO->prepare('SELECT * FROM memo WHERE user_key = "'.$user_key.'" ORDER BY created_at DESC ');
		$bdd->execute(); 
		$data = $bdd->fetchAll();
		return $data;
	}
	 
	$update_response = file_get_contents("php://input");
	$update = json_decode($update_response, true);
	if (isset($update["result"]["action"])) {
	    processMessage($update);
	} else {
		echo "pas de requête";
	}

	/* $json = '{
			  "originalRequest": {},
			  "id": "7811ac58-5bd5-4e44-8d06-6cd8c67f5406",
			  "sessionId": "1515191296300",
			  "timestamp": "2018-01-05T22:35:05.903Z",
			  "timezone": "",
			  "lang": "en-us",
			  "result": {
			    "source": "agent",
			    "resolvedQuery": "users original query to your agent",
			    "speech": "Text defined in Dialogflows console for the intent that was matched",
			    "action": "Matched Dialogflow intent action name",
			    "actionIncomplete": false,
			    "parameters": {
			      "param": "param value"
			    },
			    "contexts": [
			      {
			        "name": "incoming context name",
			        "parameters": {},
			        "lifespan": 0
			      }
			    ],
			    "metadata": {
			      "intentId": "29bcd7f8-f717-4261-a8fd-2d3e451b8af8",
			      "webhookUsed": "true",
			      "webhookForSlotFillingUsed": "false",
			      "nluResponseTime": 6,
			      "intentName": "Name of Matched Dialogflow Intent"
			    },
			    "fulfillment": {
			      "speech": "Text defined in Dialogflow console for the intent that was matched",
			      "messages": [
			        {
			          "type": 0,
			          "speech": "Text defined in Dialogflow console for the intent that was matched"
			        }
			      ]
			    },
			    "score": 1
			  }
			}';

	$json_array = json_decode($json);

	header('Content-Type: application/json');
	$json = json_encode($json_array);
	echo $json; */
?>