<?php
function search($array, $search_list) {
  
    // Create the result array
    $result = array();
  
    // Iterate over each array element
    foreach ($array as $key => $value) {
  
        // Iterate over each search condition
        foreach ($search_list as $k => $v) {
      
            // If the array element does not meet
            // the search condition then continue
            // to the next element
            if (!isset($value[$k]) || $value[$k] != $v)
            {
                  
                // Skip two loops
                continue 2;
            }
        }
      
        // Append array element's key to the
        //result array
        $result[] = $value;
    }
  
    // Return result 
    return $result;
}

?>