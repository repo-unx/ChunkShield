<?php
/**
 * ChunkShield Function Tester
 * 
 * This script automatically tests all major functions of ChunkShield
 * to ensure they are working properly.
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define test output function
function output_test_result($test_name, $result, $details = "") {
    $status = $result ? "PASS" : "FAIL";
    $color = $result ? "\033[32m" : "\033[31m"; // Green for pass, red for fail
    echo "{$color}[{$status}]\033[0m {$test_name}\n";
    if (!$result && !empty($details)) {
        echo "       Details: {$details}\n";
    }
    return $result;
}

// Include required files
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/tools/utils.php';
require_once __DIR__ . '/tools/obfuscation.php';
require_once __DIR__ . '/tools/obfuscator.php';
require_once __DIR__ . '/tools/chunker.php';
require_once __DIR__ . '/tools/anti_debug.php';
require_once __DIR__ . '/tools/anti_crack.php';
require_once __DIR__ . '/tools/semi_compiler.php';

// Create test directory
$test_dir = __DIR__ . '/test_output';
if (!is_dir($test_dir)) {
    mkdir($test_dir, 0755, true);
}

echo "\n\033[1m🔍 CHUNKSHIELD FUNCTION TEST\033[0m\n";
echo "================================\n\n";

// Test 1: Test random variable name generator
$test1 = function() {
    $var1 = generate_random_var_name();
    $var2 = generate_random_var_name();
    
    $result = (
        is_string($var1) && 
        strlen($var1) > 3 && 
        $var1 !== $var2 && 
        preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $var1) === 1
    );
    
    return output_test_result(
        "Random Variable Generator", 
        $result, 
        "Generated: {$var1}, {$var2}"
    );
};

// Test 2: Test basic obfuscation
$test2 = function() {
    $test_code = "<?php\nfunction test() {\n    echo 'Hello World';\n}\n\ntest();";
    $obfuscated = obfuscate_php_code($test_code, []);
    
    $result = (
        is_string($obfuscated) && 
        strlen($obfuscated) > strlen($test_code) &&
        strpos($obfuscated, 'Hello World') !== false && // Key strings should still exist
        strpos($obfuscated, 'function test()') === false // Function name should be changed
    );
    
    // Save to test file
    if ($result) {
        file_put_contents(__DIR__ . '/test_output/obfuscated_test.php', $obfuscated);
    }
    
    return output_test_result(
        "Basic Code Obfuscation", 
        $result,
        "Original size: " . strlen($test_code) . ", Obfuscated size: " . strlen($obfuscated)
    );
};

// Test 3: Test chunking
$test3 = function() {
    global $test_dir;
    
    $test_code = "<?php\nfunction testChunking() {\n    return 'ChunkShield Test';\n}\n\necho testChunking();";
    $chunk_dir = $test_dir . '/chunks_test';
    
    // Ensure the directory is clean
    if (is_dir($chunk_dir)) {
        array_map('unlink', glob("$chunk_dir/*.*"));
        rmdir($chunk_dir);
    }
    
    mkdir($chunk_dir, 0755, true);
    
    // Create chunk files
    $chunk_result = create_chunks($test_code, $chunk_dir, [
        'obfuscate' => true,
        'add_junk_code' => true,
        'add_junk_eval' => true,
        'add_anti_debugging' => true
    ]);
    
    // Verify chunking results
    $result = (
        is_array($chunk_result) &&
        isset($chunk_result['chunks']) &&
        count($chunk_result['chunks']) > 0 &&
        file_exists($chunk_dir . '/loader.php')
    );
    
    // Count chunks
    $chunk_count = count(glob("$chunk_dir/*.chunk"));
    
    return output_test_result(
        "Code Chunking", 
        $result,
        "Generated loader and {$chunk_count} chunks"
    );
};

// Test 4: Test anti-debugging
$test4 = function() {
    $code = "<?php echo 'Test code'; ?>";
    $protected = add_anti_debugging($code);
    
    $result = (
        strpos($protected, 'microtime') !== false &&
        strpos($protected, 'debug') !== false &&
        strlen($protected) > strlen($code)
    );
    
    return output_test_result(
        "Anti-Debugging Protection", 
        $result,
        "Original size: " . strlen($code) . ", Protected size: " . strlen($protected)
    );
};

// Test 5: Test anti-crack
$test5 = function() {
    $code = "<?php echo 'Test code'; ?>";
    $protected = add_anti_crack_measures($code);
    
    $result = (
        strpos($protected, 'GLOBALS') !== false &&
        strpos($protected, 'eval') !== false &&
        strlen($protected) > strlen($code)
    );
    
    return output_test_result(
        "Anti-Crack Protection", 
        $result,
        "Original size: " . strlen($code) . ", Protected size: " . strlen($protected)
    );
};

// Test 6: Test semi-compiler
$test6 = function() {
    global $test_dir;
    
    $test_code = "<?php\nfunction testCompile() {\n    return 'Compilation Test';\n}\n\necho testCompile();";
    $compiled = semi_compile($test_code, 2); // Use protection level 2 for testing
    
    $result = (
        is_string($compiled) &&
        strlen($compiled) > strlen($test_code) &&
        strpos($compiled, 'testCompile') === false // Function name should be changed
    );
    
    // Save to test file
    if ($result) {
        file_put_contents($test_dir . '/semi_compiled_test.php', $compiled);
    }
    
    return output_test_result(
        "Semi-Compiler", 
        $result,
        "Original size: " . strlen($test_code) . ", Compiled size: " . strlen($compiled)
    );
};

// Test 7: Test loader creation
$test7 = function() {
    global $test_dir;
    
    $test_dir_loader = $test_dir . '/loader_test';
    if (!is_dir($test_dir_loader)) {
        mkdir($test_dir_loader, 0755, true);
    }
    
    // Create a test chunk info
    $chunk_info = [
        'key' => bin2hex(random_bytes(16)),
        'chunks' => [
            [
                'id' => bin2hex(random_bytes(8)),
                'index' => 0,
                'hash' => hash('sha256', 'test chunk 1')
            ],
            [
                'id' => bin2hex(random_bytes(8)),
                'index' => 1,
                'hash' => hash('sha256', 'test chunk 2')
            ]
        ]
    ];
    
    // Create loader
    $loader_result = create_loader($chunk_info, $test_dir_loader, [
        'add_junk_code' => true,
        'add_junk_eval' => true,
        'add_anti_debugging' => true,
        'enable_self_destruct' => true
    ]);
    
    $result = (
        $loader_result === true &&
        file_exists($test_dir_loader . '/loader.php')
    );
    
    // Test the loader for syntax errors
    if ($result) {
        $loader_code = file_get_contents($test_dir_loader . '/loader.php');
        ob_start();
        $syntax_check = exec('php -l ' . $test_dir_loader . '/loader.php 2>&1', $output, $return_var);
        ob_end_clean();
        
        $valid_syntax = ($return_var === 0);
        if (!$valid_syntax) {
            $result = false;
            $error_detail = implode("\n", $output);
        }
    }
    
    return output_test_result(
        "Loader Creation", 
        $result,
        isset($error_detail) ? "Syntax error: " . $error_detail : "Loader created and validated for syntax"
    );
};

// Run all tests
$tests = [$test1, $test2, $test3, $test4, $test5, $test6, $test7];
$passed = 0;
$failed = 0;

foreach ($tests as $test) {
    $result = $test();
    if ($result) $passed++; else $failed++;
}

// Display summary
echo "\n\033[1mTest Summary:\033[0m\n";
echo "Total Tests: " . count($tests) . "\n";
echo "\033[32mPassed: {$passed}\033[0m\n";
echo "\033[31mFailed: {$failed}\033[0m\n";

if ($failed === 0) {
    echo "\n\033[42m✓ All tests passed! ChunkShield is functioning correctly. ✓\033[0m\n\n";
} else {
    echo "\n\033[41m✗ Some tests failed. Please check the results above. ✗\033[0m\n\n";
}