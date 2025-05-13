<?php
/* Semi-compiled with ChunkShield (Level 1) */

$SBuzxY0G = 242;
$hjL1KhDl = 639;

// Protection level 1
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
