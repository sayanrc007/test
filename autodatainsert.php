<?php
$link = mysqli_connect("localhost", "root", "", "mechanicly");

// Check connection

if($link === false){

    die("ERROR: Could not connect. " . mysqli_connect_error());

}

//$query = mysqli_query($link, "Select * from `vehicles`");

//while($row = mysqli_fetch_assoc($query)){
// $man_id = 'VVO0';

// $vehicle_table_id = '52';


$url="https://api.autodata-group.com/docs/v1/vehicle-identifiers/fr-vins/WAUBB28D7XA081070?country-code=gb";

$headers = array(
    'Accept-Language : en-us',
    'Accept : application/json',
    'X-Originating-Ip : 103.244.242.43'
);

			//  Initiate curl
			$ch = curl_init();
			// Disable SSL verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			// Will return the response, if false it print the response
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			// Set the url
			curl_setopt($ch, CURLOPT_URL,$url);
			// Execute
			$result=curl_exec($ch);
			// Closing
			curl_close($ch);

			// Will dump a beauty json :3
			$res = json_decode($result, true);

			print_r($res);

			// foreach ($res['data']['models'] as $key => $value) {
			// 	$name  = $value['model'];
			// 	$id  = $value['model_id'];
			// 			$sql = "INSERT INTO `models` (`id`, `vehicle_table_id`, `model_id`, `model_name`, `model_nicename`) VALUES ('', '$vehicle_table_id', '$id', '$name', '')";
			// 			mysqli_query($link, $sql);
			// 	}	
//}

 ?>