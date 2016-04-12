<?php

class upload{
    
    function uploadImage($image, $dir, $name, $id){
     
         $stamp = time();
         //$rest_dir = "Rest_Images";

         if (!file_exists($dir))
            mkdir($dir, 0777, true);

         $name = str_replace(" ", "_", $name);
         $path = $dir."/".$name."_".$stamp.".png";

         if($dir == "Rest_Images")
            $sql = "INSERT INTO restaurant_images(rest_id,url) VALUES ('$id','$path')";
         else
            $sql = "INSERT INTO dish_images(dish_id,url) VALUES ('$id','$path')";
        
        file_put_contents($path,base64_decode($image));
        echo $sql;
        mysql_query($sql);
    }
}

$upload = new upload();

?>