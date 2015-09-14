<?php

// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif','zip');

if(isset($_FILES['item-image']) && $_FILES['item-image']['error'] == 0){

	$extension = pathinfo($_FILES['item-image']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}

	if(move_uploaded_file($_FILES['item-image']['tmp_name'], '../../admin/assets/itemimages/'.$_FILES['item-image']['name'])){
		echo '{"status":"success"}';
		exit;
	}
}

echo '{"status":"error"}';
exit;