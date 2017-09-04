<?php

$link = mysqli_connect("localhost", "root", "", "mechanicly");

 

// Check connection

if($link === false){

    die("ERROR: Could not connect. " . mysqli_connect_error());

}

 
$query = mysqli_query($link, "Select * from `vehicles`");

while($row = mysqli_fetch_assoc($query)){
		$veh_name = $row['vehicle_name'];
		if ( preg_match('/\s/',$veh_name)){
			$veh_name_trim = str_replace(" ","%20",$veh_name);
		}else{
			$veh_name_trim = $veh_name;
		}
		
		$veh_table_id = $row['ID'];
		 $url="https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMake/".$veh_name_trim."?format=json";
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
			$resval = $res['Results'];
			if(isset($resval) && count($resval)>0 && $resval!=0){
			foreach($resval as $val){
				$model_id = $val['Model_ID'];
				$model_name = $val['Model_Name'];
				$inner_query = mysqli_query($link, "Select * from `models` where `vehicle_table_id`='$veh_table_id' and `model_id`='$model_id' and `model_name`='$model_name'");
				if($inner_query){
					$num_rows = mysqli_num_rows($inner_query); 
					if($num_rows<1){
					$sql = "INSERT INTO `models` (`id`, `vehicle_table_id`, `model_id`, `model_name`) VALUES ('', '$veh_table_id', '$model_id', '$model_name')";
					mysqli_query($link, $sql);		
				   }
				  }	
				}
			}
			
}

mysqli_close($link);



 ?>