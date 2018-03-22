<?php
	function manage_post($data) {
		$data = str_replace('"', '', $data);
		$data = str_replace(':', ',', $data);
		$data = substr($data, 1, -1);
		$data = explode( ",", $data );
		$array = array();

		for ($i=0; $i < sizeof($data); $i++) { 
			if($i%2 == 0) {
				continue;
			} else {
				$array[$data[$i-1]] = $data[$i];
			}
		}
		return $array;
	}
?>