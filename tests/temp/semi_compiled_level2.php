<?php
/* Semi-compiled with ChunkShield (Level 2) */

$goa4N99o = 927;
$YP0P8ubN = 983;
$v42O9sMx = 718;
$uaQyB6ss = 730;

// Protection level 2
/**
 * Basic Test Function
 */
function testBasicFunction($param1, $param2 = null) {
    $result = [];
    
    // Process input parameters
    if (is_string($param1)) {
        $result['type'] = 'string';
        $result['value'] = $param1;
    } else if (is_array($param1)) {
        $result['type'] = 'array';
        $result['value'] = implode(',', $param1);
    } else {
        $result['type'] = 'other';
        $result['value'] = (string)$param1;
    }
    
    // Add optional parameter
    if ($param2 !== null) {
        $result['extra'] = $param2;
    }
    
    return $result;
}

// Example usage
$testVar = "Hello World";
$output = testBasicFunction($testVar, 123);
echo "Output: " . json_encode($output);
