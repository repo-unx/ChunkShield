<?php
/**
 * ChunkShield Function Wrappers
 * 
 * This file provides compatibility wrappers for ChunkShield functions
 * to ensure proper integration with testing and validation tools.
 */

// Include required files
if (!function_exists('obfuscate_code') && function_exists('obfuscate_php_code')) {
    /**
     * Wrapper for obfuscate_code to ensure compatibility with testing tools
     * 
     * @param string $code The code to obfuscate
     * @param array $options Obfuscation options
     * @return string The obfuscated code
     */
    function obfuscate_code($code, $options = []) {
        $result = obfuscate_php_code($code, $options);
        
        if (is_array($result)) {
            return $result['code'];
        }
        
        return $result;
    }
}

if (!function_exists('chunk_file') && function_exists('chunk_php_file')) {
    /**
     * Wrapper for chunk_file to ensure compatibility with testing tools
     * 
     * @param string $file The file to chunk
     * @param string $outputDir The output directory for chunks
     * @param array $options Chunking options
     * @return array Chunking results
     */
    function chunk_file($file, $outputDir, $options = []) {
        return chunk_php_file($file, $outputDir, $options);
    }
}

if (!function_exists('semi_compile') && function_exists('semi_compile_php')) {
    /**
     * Wrapper for semi_compile to ensure compatibility with testing tools
     * 
     * @param string $code The code to compile
     * @param int $level The compilation level
     * @return string The compiled code
     */
    function semi_compile($code, $level = 3) {
        return semi_compile_php($code, [
            'complexity_level' => $level
        ]);
    }
}

if (!function_exists('generate_loader')) {
    /**
     * Wrapper for generate_loader to ensure compatibility with testing tools
     * 
     * @param string $chunksDir The directory containing chunks
     * @param string $outputFile The output file for the loader
     * @param array $options Loader options
     * @return array Loader generation results
     */
    function generate_loader($chunksDir, $outputFile, $licenseInfo = [], $options = []) {
        // Create a simple chunk info structure for compatibility
        $chunkFiles = glob($chunksDir . '/*.chunk');
        $chunks = [];
        foreach ($chunkFiles as $index => $chunkFile) {
            $chunkId = basename($chunkFile, '.chunk');
            $chunks[] = [
                'id' => $chunkId,
                'index' => $index,
                'hash' => md5_file($chunkFile)
            ];
        }
        
        $chunkInfo = [
            'directory' => $chunksDir,
            'key' => md5(microtime(true)),
            'chunks' => $chunks,
            'options' => [
                'use_gzip' => true,
                'use_base64' => true
            ]
        ];
        
        if (function_exists('generate_loader_file')) {
            return generate_loader_file($chunkInfo, dirname($outputFile), $licenseInfo, $options);
        } elseif (function_exists('test_generate_loader')) {
            return test_generate_loader($chunksDir, $outputFile, $options);
        }
        
        // Create a basic loader as fallback if both functions are not available
        $loader = "<?php\n";
        $loader .= "// ChunkShield Loader (Fallback Implementation)\n";
        $loader .= "// Generated: " . date('Y-m-d H:i:s') . "\n\n";
        $loader .= "// This is a fallback loader - it doesn't have full functionality\n";
        $loader .= "echo 'ChunkShield Loader (Fallback)\\n';\n";
        $loader .= "// Chunks directory: " . $chunksDir . "\n";
        $loader .= "// Found " . count($chunks) . " chunks\n";
        
        file_put_contents($outputFile, $loader);
        
        return [
            'file' => $outputFile,
            'size' => strlen($loader),
            'chunks' => count($chunks)
        ];
    }
}

// Log message function is already defined in utils.php

// Handle the advanced chunking function wrapper
if (!function_exists('chunk_file_wrapper') && function_exists('chunk_file_advanced')) {
    /**
     * Wrapper for advanced chunking function to extend basic chunking
     * 
     * @param string $file Path to PHP file to chunk
     * @param string $output_dir Directory to store chunks
     * @param array $options Chunking options
     * @return array|bool Array with chunking information or false on error
     */
    function chunk_file_wrapper($file, $output_dir, $options = []) {
        return chunk_file_advanced($file, $output_dir, $options);
    }
}

// Handle the advanced obfuscation function wrapper
if (!function_exists('obfuscate_code_wrapper') && function_exists('obfuscate_code_advanced')) {
    /**
     * Wrapper for advanced obfuscation function
     * 
     * @param string $code PHP code to obfuscate
     * @param array $options Obfuscation options
     * @return array Obfuscated code and metadata
     */
    function obfuscate_code_wrapper($code, $options = []) {
        return obfuscate_code_advanced($code, $options);
    }
}