<?php

include_once './DbConnect.php';
include_once './new_restaurant.php';
include_once './img_upload.php';

function createNewDish() {
	global $upload;

    if (isset($_POST["rest"]) && $_POST["rest"] != "" 
    	&& isset($_POST["category"]) && $_POST["category"] != "" 
    	&& isset($_POST["dish"]) && $_POST["dish"] != ""
    	&& isset($_POST["cost"]) && $_POST["cost"] != "" 
    	&& isset($_POST["type"]) && $_POST["type"] != "" 
    	&& isset($_POST["lat"]) && $_POST["lat"] != ""
    	&& isset($_POST["long"]) && $_POST["long"] != "") {

        $rest_name = $_POST["rest"];
        $category = $_POST["category"];
        $dish_name = $_POST["dish"];
        if(isset($_POST["description"]))
        	$description = $_POST["description"];
        else
        	$description = "";
        $cost = $_POST["cost"];
        $type = $_POST["type"];
        if(isset($_POST["rating"]))
        	$rating = $_POST["rating"];
        else
        	$rating = 0;
        $lat = $_POST["lat"];
        $long = $_POST["long"];

        //$text = $rest_name." ~ ".$category." ~ ".$dish_name." ~ ".$description." ~ ".$cost." ~ ".$type." ~ ".$rating." ~ ".$lat." ~ ".$long;
		//$fp = fopen("test.txt", 'w');
		//fwrite($fp, $text);
		//fclose($fp);

	    $result = mysql_query("SELECT rest_id,rest_name FROM restaurant WHERE rest_name='$rest_name'");
	    
	    if($row = mysql_fetch_array($result))
	    	$rest_id = $row["rest_id"];
	    else{
	    	global $restaurant;
	    	$rest_id = $restaurant->createNewRestaurant($rest_name,$category,$lat,$long);
	    }

	    if($result = mysql_query("SELECT rest_id,dish_name FROM dish WHERE rest_id='$rest_id' and dish_name='$dish_name'")){
		    if(mysql_fetch_array($result)){
	        $result = mysql_query("UPDATE dish SET description='$description',cost='$cost',type='$type',rating='$rating' WHERE rest_id='$rest_id' and dish_name='$dish_name'");
				if ($result)
		            echo "Dish Updated";
		        else
		            echo "Error Updating Dish";
		    }
		    else{
		    	$result = mysql_query("INSERT INTO dish(rest_id,dish_name,description,cost,type,rating) VALUES('$rest_id','$dish_name','$description','$cost','$type','$rating')") or die(mysql_error());
		        if ($result)
		            echo "Dish Added";
		        else
		            echo "Error Adding Dish";
		    }
		}


	    //$result = mysql_query("SELECT restaurant.rest_name, dish.dish_name FROM restaurant INNER JOIN dish ON restaurant.rest_id=dish.rest_id WHERE restaurant.rest_id='$rest_id' AND dish.dish_name='$dish_name'");
        //    while ($row = mysql_fetch_array($result)){
        //        $rest_name = $row["rest_name"];
        //        $dish_name = $row["dish_name"];
        //    }
            //$upload->uploadImage($_POST['image_rest'], "Rest_Images", $rest_name, $rest_id);
            //$upload->uploadImage($_POST['image_dish'], "Dish_Images", $dish_name, $dish_id);

    } else
        echo "Dish Details are Missing!";

    //echo json_encode($response);
}

createNewDish();
?>