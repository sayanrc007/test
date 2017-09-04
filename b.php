<?php

$link = mysqli_connect("localhost", "root", "", "cars");

 

// Check connection

if($link === false){

    die("ERROR: Could not connect. " . mysqli_connect_error());

}

 
$query = mysqli_query($link, "Select * from `vehicles`");

while($row = mysqli_fetch_assoc($query)){
		$veh_nicename = $row['vehicle_nicename'];
		$veh_table_id = $row['ID'];
		 $url="https://api.edmunds.com/api/vehicle/v2/".$veh_nicename."/models?fmt=json&api_key=8vejxmnyubpafvwppcnve28q";
			//  Initiate curl
			$ch = curl_init();
			// Disable SSL verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			// Will return the response, if false it print the response
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// Set the url
			curl_setopt($ch, CURLOPT_URL,$url);
			// Execute
			$result=curl_exec($ch);
			// Closing
			curl_close($ch);

			// Will dump a beauty json :3
			$res = json_decode($result, true);
			// echo '<pre>';
			// print_r($res);
			// echo '</pre>';
			foreach($res['models'] as $val){
				$year_arr = $val['years'];
				foreach($year_arr as $ya){
				$styles_arr = $ya['styles'];
				$year_id = $ya['id'];
				$year_value = $ya['year'];
					foreach($styles_arr as $sa){
					$style_id = $sa['id'];
					$style_name = $sa['name'];
					$submodel_body = $sa['submodel']['body'];
					$submodel_name = $sa['submodel']['modelName'];
					$submodel_nicename = $sa['submodel']['niceName'];
					$style_trim = $sa['trim'];	
					$sql = "INSERT INTO `vehicle_models_yearwise_styles` (`id`, `vehicle_table_id`, `year_id`, `year_value`, `style_id`, `style_name`, `submodel_body`, `submodel_name`, `submodel_nicename`, `style_trim`) VALUES ('', '$veh_table_id', '$year_id', '$year_value', '$style_id', '$style_name', '$submodel_body', '$submodel_name', '$submodel_nicename', '$style_trim')";
					mysqli_query($link, $sql);		
					}
				}
			
			}
}

mysqli_close($link);



 ?>