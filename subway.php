<?php
$host = "localhost";
$user = "xowns9418";
$pw = "*******";
$db = "xowns9418";

$my_db = new mysqli($host, $user, $pw, $db);
mysqli_set_charset($my_db, "utf8");

$result = mysqli_query($my_db, "select * from feeding");

$return_array = array();

while($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{
$row_array['subway'] = $row['subway'];
$row_array['map'] = $row['map'];
$row_array['addr'] = $row['addr'];
$row_array['weekday'] = $row['weekday'];
$row_array['saturday'] = $row['saturday'];
$row_array['holiday'] = $row['holiday'];
$row_array['tell'] = $row['tell'];

array_push($return_array, $row_array);
}

function array_to_json( $array ){ //인코딩 함수

    if( !is_array( $array ) ){ 
        return false; 
    } 

    $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) )); 
    if( $associative ){ 

        $construct = array(); 
        foreach( $array as $key => $value ){ 

            // We first copy each key/value pair into a staging array, 
            // formatting each key and value properly as we go. 

            // Format the key: 
            if( is_numeric($key) ){ 
                $key = "key_$key"; 
            } 
            $key = '"'.addslashes($key).'"'; 

            // Format the value: 
            if( is_array( $value )){ 
                $value = array_to_json( $value ); 
            } else if( !is_numeric( $value ) || is_string( $value ) ){ 
                $value = '"'.addslashes($value).'"'; 
            } 

            // Add to staging array: 
            $construct[] = "$key: $value"; 
        } 

        // Then we collapse the staging array into the JSON form: 
        $result = "{ " . implode( ", ", $construct ) . " }"; 

    } else { // If the array is a vector (not associative): 

        $construct = array(); 
        foreach( $array as $value ){ 

            // Format the value: 
            if( is_array( $value )){ 
                $value = array_to_json( $value ); 
            } else if( !is_numeric( $value ) || is_string( $value ) ){ 
                $value = '"'.addslashes($value).'"'; 
            } 

            // Add to staging array: 
            $construct[] = $value; 
        } 

        // Then we collapse the staging array into the JSON form: 
        $result = "[ " . implode( ", ", $construct ) . " ]"; 
    } 

    return $result; 
} 

$group_data = array();
$group_data['feed'] = $return_array;


echo array_to_json($group_data);
$my_db->close();
?>
