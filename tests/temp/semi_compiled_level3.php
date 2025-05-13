<?php
/* Semi-compiled with ChunkShield (Level 3) */

$4VDbAMF3 = 879;
$0YbKnA1E = 347;
$kcbHWJ0e = 345;
$xFKgUbYy = 884;
$3CQywdyJ = 848;
$CE3iCXNS = 181;

// Protection level 3 with runtime checks
try {
    // Begin protected section
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
    // End protected section
} catch (Exception $e) {
    error_log($e->getMessage());
}

