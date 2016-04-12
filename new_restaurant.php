<?php

//include_once './DbConnect.php';

class restaurant{

	function createNewRestaurant($rest_name,$category,$latitude,$longitude) {
	//    if (isset($_GET["rest_name"]) && $_GET["rest_name"] != "" && isset($_GET["lat"]) && $_GET["lat"] != "" && isset($_GET["long"]) && $_GET["long"] != "") {
	        $response = array();
	//        $rest_name = $_GET["rest_name"];
	//        $latitude = $_GET["lat"];
	//        $longitude = $_GET["long"];\
	        $query = "INSERT INTO restaurant(rest_name,category,latitude,longitude) VALUES('$rest_name','$category','$latitude','$longitude')";
	        $result = mysql_query($query) or die(mysql_error());
	        if ($result)
	            $response["message"] = "Restaurant created successfully!";
	        else
	            $response["message"] = "Failed to create Restaurant!";
	//    }
	//    else {
	//        $response["error"] = true;
	//        $response["message"] = "Fields are missing!";
	//    }

	    $result = mysql_query("SELECT rest_id,rest_name FROM restaurant WHERE rest_name='$rest_name'");
		    if($row = mysql_fetch_array($result))
		    	$rest_id = $row["rest_id"];
	    return $rest_id;
	    //echo json_encode($response);

	}

}

$restaurant = new restaurant();
?>