<?php
$tempArray = array();
$data[] = $_POST['data'];
$x = $_POST['x'];
$inp = file_get_contents('last_data.json');

$obj_new = json_decode($data[0]);
$last_data = json_decode($inp);

function previous($data, $id) {
	$item = null;
	if($item == null) {
		foreach($data as $row) {
			if($id == $row->id) {
				$item = array(
						'id' => $row->id,
						'score' => $row->score
					);
				return $item;
			}
		}
	}
	return null;
}

$id = null;
$score = null;
foreach($data as $row) {
	$current = json_decode($row);
	$previous = previous($last_data, $current->id);
	$id = $current->id;
	$score = $current->score;
}

if ($previous['score'] < $score) {
	echo 2;
} else if ($previous['score'] > $score) {
	echo 1; 
} else if ($previous['score'] == $score) {
	echo 0;
}




/** Insert only*/
/*function update_list($data, $id, $score) {
	$collection = null;
	foreach($data as $row) {
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
	return $collection;
}

$data_new = update_list($last_data, $id, $score);

$jsonData = json_encode($data_new);
file_put_contents('last_data.json', $jsonData);*/


