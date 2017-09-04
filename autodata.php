<?php

$link = mysqli_connect("localhost", "root", "", "mec_test");

// Check connection

if($link === false){
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

$url="https://api.autodata-group.com/docs/v1/manufacturers?country-code=us&limit=100&api_key=upnetk8sy88f7wtrjz5kxd75";

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
			//print_r($res); 	
			foreach ($res['data'] as $key => $value) {
				$name  = $value['manufacturer'];
				$id  = $value['manufacturer_id'];
				$inner_query = mysqli_query($link, "Select * from `vehicles` where `manufacturer_id`='$id'");
				if($inner_query){
					$num_rows = mysqli_num_rows($inner_query); 
					if($num_rows<1){
						$insert_sql = "INSERT INTO `vehicles` (`id`, `manufacturer`, `manufacturer_id`, `country_code`, `language`) VALUES ('', '$name', '$id', 'us', 'en-us')";
		  				   mysqli_query($link, $insert_sql);
					}
				}
			}
?>