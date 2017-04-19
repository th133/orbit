<?php
function binary_search_str($array, $value){
    $low = 0;
    $high = count($array) - 1;
    while($low <= $high){
        $mid = floor($low + ($high - $low) / 2);
        if(strcmp($array[$mid], $value) == 0){
            return $value;
        } else if(strcmp($array[$mid], $value) < 0){
            $low = $mid + 1;
        } else {
            $high = $mid - 1;
        }
    }
    return null;
}
?>