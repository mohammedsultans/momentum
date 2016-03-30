<?php
	if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

	// A list of permitted file extensions
	$allowedimgs = array('png', 'jpg', 'gif');
	$alloweddocs = array('pdf');

	if(isset($_FILES['scandoc']) && $_FILES['scandoc']['error'] == 0){

		$extension = pathinfo($_FILES['scandoc']['name'], PATHINFO_EXTENSION);

		/*if(in_array(strtolower($extension), $allowedimgs)){
			if(move_uploaded_file($_FILES['scandoc']['tmp_name'], '../documents/'.$_FILES['scandoc']['name'])){
				$result['status'] = 'success';
				$result['type'] = 'img';
				echo json_encode($result);
				exit;
			}
		}elseif (in_array(strtolower($extension), $alloweddocs)) {
			if(move_uploaded_file($_FILES['scandoc']['tmp_name'], '../documents/'.$_FILES['scandoc']['name'])){
				$result['status'] = 'success';
				$result['type'] = 'pdf';
				echo json_encode($result);
				exit;
			}
		}*/

		if(move_uploaded_file($_FILES['scandoc']['tmp_name'], '../documents/'.$_FILES['scandoc']['name'])){
			$result['status'] = 'success';
			$result['type'] = 'artifact';
			echo json_encode($result);
			exit;
		}
	}

	

	$result['status'] = 'error';
	echo json_encode($result);
	exit;
?>