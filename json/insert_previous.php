<?php
$tempArray = array();
$data[] = $_POST['data'];
$x = $_POST['x'];
$inp = file_get_contents('last_data.json');
$last_data = json_decode($inp);


function update_list($last_data, $id, $score) {
	$collection = null;
	$is_exists = false;
	foreach($last_data as $row) {
		echo $id.' - '.$row->id;
		if($id == $row->id) {
			$collection[] = array(
					'id' => $id,
					'score' => $score
				);
		} else {
			$collection[] = array(
					'id' => $row->id,
					'score' => $row->score
				);
		}
	}
	insert_to_list($last_data, $id, $score);
	
	return $collection;
}

function insert_to_list($last_data, $id, $score) {
	$collection = array();
	$is_exists = false;
	foreach($last_data as $row) {
		if($id == $row->id)  {
			$is_exists = true;
			echo 'true';
		}
	}

	if(!$is_exists) $collection[] = array('id' => $id, 'score' => $score);
	
	return $collection;
}

$id = null;
$score = null;
foreach($data as $row) {
	$current = json_decode($row);
	$id = $current->id;
	$score = $current->score;
}
$data_new = update_list($last_data, $id, $score);
$data_new_insert = insert_to_list($last_data, $id, $score);
if(is_array($data_new_insert)) {
	$data_m = array_merge($data_new, $data_new_insert);	
} else {
	$data_m = $data_new;
}
$jsonData = json_encode($data_m);
file_put_contents('last_data.json', $jsonData);
echo $jsonData;
