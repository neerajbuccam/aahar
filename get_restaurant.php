<?php
include_once './DbConnect.php';
include_once './soundex.php';

function getDistance($lat1, $long1, $lat2, $long2) {

	//if ( ($data = http_get("maps.googleapis.com", "/maps/api/distancematrix/json?origins=$lat1,$long1&destinations=$lat2,$long2", 15) ) !== false )

	$url = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=$lat1,$long1&destinations=$lat2,$long2";
	$data = file_get_contents($url);
	$data = json_decode($data);
	$dist = $data->rows[0]->elements[0];
	if($dist->status != "ZERO_RESULTS")
	  	$dist = $dist->distance->value;
	else
	  	return 0;
	  //echo $dist;
  return $dist;
}

function getNearbyRest($lat, $long, $radius=10000){
    $response = array();
    $tmp = array();
    
    $result = mysql_query("SELECT * FROM restaurant");

    while($row = mysql_fetch_array($result)){
        $tmp["rest_id"] = $row["rest_id"];
        $tmp["name"] = $row["rest_name"];
        $tmp_lat = $row["latitude"];
        $tmp_long = $row["longitude"];
        
        $dist = getDistance($lat, $long, $tmp_lat, $tmp_long);
        if($dist <= $radius){
        	if($dist < 1000)
        		$tmp["distance"] = $dist." meters";
        	else
        		$tmp["distance"] = round($dist/1000,1)." km";
    		
    		$sum_rating=0;
    		$sum_type=0;
    		$count=0;
    		$rest_id = $tmp["rest_id"];
    		$result2 = mysql_query("SELECT type,rating FROM dish WHERE rest_id='$rest_id'");
    		while ($row2 = mysql_fetch_array($result2)) {
    			$sum_rating += $row2["rating"];
    			$sum_type += $row2["type"];
    			$count++;
    		}
    		$tmp["avg"] = $sum_rating/$count;

    		if($sum_type == $count)
    			$tmp["type"] = 1;
    		elseif($sum_type == 0)
    			$tmp["type"] = 0;
    		else
    			$tmp["type"] = 2;

    		$result3 = mysql_query("SELECT url FROM restaurant_images WHERE rest_id='11' ORDER BY rest_img_id DESC");
    		if($row3 = mysql_fetch_array($result3))
    			$tmp["url"] = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/".$row3["url"];

	        array_push($response, $tmp);
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response,JSON_UNESCAPED_SLASHES);
}

function searchRest($keyword=""){
	global $soundex;
	$response["restaurant"] = array();
	$tmp = array();

	$initial = substr($keyword, 0, 1);
	$codeKeyword = $soundex->MakeSoundEx($keyword);

	$result = mysql_query("SELECT * FROM restaurant WHERE rest_name like '$initial%'");

	while ($row = mysql_fetch_array($result)) {
        $tmp["rest_id"] = $row["rest_id"];
        $tmp["name"] = $row["rest_name"];
        $tmp["latitude"] = $row["latitude"];
        $tmp["longitude"] = $row["longitude"];

        if($codeKeyword == $soundex->MakeSoundEx($tmp["name"]))
        	array_push($response["restaurant"], $tmp);
	}

	header('Content-Type: application/json');
    echo json_encode($response);
}

function searchRestSuggest($keyword=""){
	global $soundex;
	$response["restaurant"] = array();
	$tmp = array();

	//$initial = substr($keyword, 0, 1);
	//$codeKeyword = $soundex->MakeSoundEx($keyword);

	$keyword_exploded = explode ( " ", $keyword );
       $x = 0; 
       foreach( $keyword_exploded as $keyword_each ) {
             $x++;
             $construct = " ";
             if( $x == 1 )
                    $construct .= "rest_name LIKE '%$keyword_each%' ";
             else
                    $construct .= "AND rest_name LIKE '%$keyword_each%' ";
       }

	$result = mysql_query("SELECT * FROM restaurant WHERE $construct");

	while ($row = mysql_fetch_array($result)) {
        $tmp["name"] = $row["rest_name"];

        //if($codeKeyword == $soundex->MakeSoundEx($tmp["name"]))
        	array_push($response["restaurant"], $tmp["name"]);
	}

	header('Content-Type: application/json');
    echo json_encode($response);
}

//getDistance(15.4584140, 73.8345550, 15.4909300, 73.8278500);
if(@$_GET['q'] == "nearby"){
	if(isset($_GET['max']))
		getNearbyRest($_GET['lat'], $_GET['long'], $_GET['max']);
	else
		getNearbyRest($_GET['lat'], $_GET['long']);
}
//	getNearbyRest(15.46008808, 73.83560906, 10000);
//getCategories();
//searchRest("Nias");
//searchRestSuggest("haji");

function http_get($host, $resource, $timeout)
{
    $fp = fsockopen($host, 80, $errno, $errstr, $timeout);
    if ($fp !== false)
    {
        $h = "GET $resource HTTP/1.1\r\n";
        $h .= "Host: $host\r\n";
        $h .= "Connection: Close\r\n\r\n";
        stream_set_timeout($fp, $timeout * 10000);
        fwrite($fp, $h);
        $buff = "";
        while (!feof($fp)) {
            $buff .= fgets($fp, 256);
        }
        fclose($fp);
        return $buff;
    }
    return false;
}
?>