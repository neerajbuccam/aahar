<?php
include_once './DbConnect.php';
include_once './soundex.php';

function getDishes($rest_id){
    $tmp = array();
    $response = array();
    
    $result = mysql_query("SELECT * FROM dish INNER JOIN restaurant ON dish.rest_id=restaurant.rest_id WHERE dish.rest_id='$rest_id'");
    
    while($row = mysql_fetch_array($result)){
        $tmp["latitude"] = $row["latitude"];
        $tmp["longitude"] = $row["longitude"];
        $tmp["dish_name"] = $row["dish_name"];
        $tmp["description"] = $row["description"];
        $tmp["cost"] = $row["cost"];
        $tmp["type"] = $row["type"];
        $tmp["rating"] = $row["rating"];

        array_push($response, $tmp);
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
}

function getTopRated(){
    $tmp = array();
    $response = array();

    $result = mysql_query("SELECT * FROM dish INNER JOIN restaurant ON dish.rest_id=restaurant.rest_id WHERE dish.rest_id IN (SELECT rest_id FROM restaurant) GROUP BY dish.rest_id ORDER BY avg(dish.rating) DESC");

    $count=0;
    while($row = mysql_fetch_array($result)){
        $tmp["rest_name"] = $row["rest_name"];
        $tmp["latitude"] = $row["latitude"];
        $tmp["longitude"] = $row["longitude"];

        if($count < 2)
        	array_push($response, $tmp);
        else
        	break;
        $count++;
    }

    $result = mysql_query("SELECT * FROM dish INNER JOIN restaurant ON dish.rest_id=restaurant.rest_id ORDER BY dish.rating DESC");

    $count=0;
    while($row = mysql_fetch_array($result)){
        $tmp["rest_name"] = $row["rest_name"];
        $tmp["dish_name"] = $row["dish_name"];
        unset($tmp["latitude"]);
        unset($tmp["longitude"]);
        $tmp["description"] = $row["description"];
        $tmp["cost"] = $row["cost"];
        $tmp["type"] = $row["type"];
        $tmp["rating"] = $row["rating"];

        if($count < 2)
        	array_push($response, $tmp);
        else
        	break;
        $count++;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
}

function searchDish($keyword=""){
	global $soundex;
	$response["dish"] = array();
	$tmp = array();

	$initial = substr($keyword, 0, 1);
	$codeKeyword = $soundex->MakeSoundEx($keyword);

	$result = mysql_query("SELECT * FROM dish WHERE dish_name like '$initial%'");

	while ($row = mysql_fetch_array($result)) {
        $tmp["dish_id"] = $row["dish_id"];
        $tmp["rest_id"] = $row["rest_id"];
        $tmp["name"] = $row["dish_name"];
        $tmp["description"] = $row["description"];
        $tmp["cost"] = $row["cost"];
        $tmp["type"] = $row["type"];
        $tmp["rating"] = $row["rating"];

        if($codeKeyword == $soundex->MakeSoundEx($tmp["name"]))
        	array_push($response["dish"], $tmp);
	}

	header('Content-Type: application/json');
    echo json_encode($response);
}

function searchDishSuggest($keyword=""){
	global $soundex;
	$response = array();
	$tmp = array();

	$initial = substr($keyword, 0, 1);
	$codeKeyword = $soundex->MakeSoundEx($keyword);

	$result = mysql_query("SELECT DISTINCT * FROM dish WHERE dish_name like '$initial%'");

	while ($row = mysql_fetch_array($result)) {
        $tmp["name"] = $row["dish_name"];

        if($codeKeyword == $soundex->MakeSoundEx($tmp["name"]))
        	array_push($response, $tmp);
	}

	header('Content-Type: application/json');
    echo json_encode($response);
}


if(@$_GET['q'] == "all" && isset($_GET['rest_id']))
	getDishes($_GET['rest_id']);

if(@$_GET['q'] == "top")
	getTopRated();
//searchDishSuggest("Chicken Pizza");
?>