<?php
/**
 * PHP Code Obfuscator
 * 
 * This file provides functions to obfuscate PHP code by:
 * - Renaming variables, functions, and class names
 * - Removing comments and whitespace
 * - Encoding string literals
 */

/**
 * Main function to obfuscate PHP code
 * 
 * @param string $code The PHP code to obfuscate
 * @param array $options Options for obfuscation
 * @return string The obfuscated code
 */
function obfuscateCode($code, $options = []) {
    // Default options
    $options = array_merge([
        'rename_variables' => true,
        'remove_whitespace' => true,
        'encode_strings' => true
    ], $options);
    
    // Ensure code starts with <?php
    if (strpos($code, '<?php') !== 0) {
        $code = "<?php\n" . $code;
    }
    
    // Backup the original code
    $originalCode = $code;
    
    try {
        // Start obfuscation process
        if ($options['rename_variables']) {
            $code = renameVariablesAndFunctions($code);
        }
        
        if ($options['remove_whitespace']) {
            $code = removeCommentsAndWhitespace($code);
        }
        
        if ($options['encode_strings']) {
            $code = encodeStrings($code);
        }
        
        // Check if the resulting code is valid PHP
        if (!isValidPHP($code)) {
            throw new Exception("Obfuscation resulted in invalid PHP code.");
        }
        
        return $code;
    } catch (Exception $e) {
        // If something went wrong, return the original code with a warning comment
        return "<?php\n// Obfuscation failed: " . $e->getMessage() . "\n" . $originalCode;
    }
}

/**
 * Renames variables and functions to random strings
 * 
 * @param string $code The PHP code
 * @return string Code with renamed identifiers
 */
function renameVariablesAndFunctions($code) {
    // Load code into a temporary file for token analysis
    $tempFile = tempnam(sys_get_temp_dir(), 'obf');
    file_put_contents($tempFile, $code);
    
    // Parse the PHP file and get tokens
    $tokens = token_get_all(file_get_contents($tempFile));
    unlink($tempFile);
    
    // Extract identifiers (variables, functions, classes)
    $variables = [];
    $functions = [];
    $classes = [];
    
    // First pass: collect identifiers
    $inClass = false;
    $inFunction = false;
    $inGlobalSpace = true;
    $bracketDepth = 0;
    
    foreach ($tokens as $index => $token) {
        if (is_array($token)) {
            list($tokenId, $tokenText) = $token;
            
            // Track context
            if ($tokenId === T_CLASS) {
                $inClass = true;
            } elseif ($tokenId === T_FUNCTION) {
                $inFunction = true;
            } elseif ($tokenId === T_VARIABLE) {
                // Variable names start with $, remove it for mapping
                $varName = substr($tokenText, 1);
                
                // Skip superglobals and other special variables
                $skipVars = ['_GET', '_POST', '_REQUEST', '_SESSION', '_COOKIE', 
                           '_SERVER', '_ENV', '_FILES', 'GLOBALS', 'this'];
                            
                if (!in_array($varName, $skipVars)) {
                    $variables[$varName] = true;
                }
            } elseif ($inFunction && $tokenId === T_STRING) {
                // Possibly a function name
                if (isset($tokens[$index-2]) && is_array($tokens[$index-2]) && $tokens[$index-2][0] === T_FUNCTION) {
                    // Skip magic methods
                    $skipMethods = ['__construct', '__destruct', '__call', '__callStatic', 
                                  '__get', '__set', '__isset', '__unset', '__sleep', 
                                  '__wakeup', '__toString', '__invoke', '__set_state', 
                                  '__clone', '__debugInfo'];
                                  
                    if (!in_array($tokenText, $skipMethods)) {
                        $functions[$tokenText] = true;
                    }
                }
            } elseif ($inClass && $tokenId === T_STRING) {
                // Possibly a class name
                if (isset($tokens[$index-2]) && is_array($tokens[$index-2]) && $tokens[$index-2][0] === T_CLASS) {
                    $classes[$tokenText] = true;
                }
            }
        } else {
            // Track bracket depth to determine scope
            if ($token === '{') {
                $bracketDepth++;
            } elseif ($token === '}') {
                $bracketDepth--;
                if ($inFunction && $bracketDepth === 0) {
                    $inFunction = false;
                    $inGlobalSpace = !$inClass;
                } elseif ($inClass && $bracketDepth === 0) {
                    $inClass = false;
                    $inGlobalSpace = true;
                }
            }
        }
    }
    
    // Generate random names for identifiers
    $variableMappings = [];
    foreach (array_keys($variables) as $variable) {
        $variableMappings[$variable] = '_' . obfuscatorGenerateRandomString(8);
    }
    
    $functionMappings = [];
    foreach (array_keys($functions) as $function) {
        $functionMappings[$function] = '_' . obfuscatorGenerateRandomString(10);
    }
    
    $classMappings = [];
    foreach (array_keys($classes) as $class) {
        $classMappings[$class] = '_' . obfuscatorGenerateRandomString(12);
    }
    
    // Second pass: replace identifiers with random names
    $result = '';
    foreach ($tokens as $token) {
        if (is_array($token)) {
            list($tokenId, $tokenText) = $token;
            
            if ($tokenId === T_VARIABLE) {
                $varName = substr($tokenText, 1);
                if (isset($variableMappings[$varName])) {
                    $result .= '$' . $variableMappings[$varName];
                } else {
                    $result .= $tokenText;
                }
            } elseif ($tokenId === T_STRING) {
                if (isset($functionMappings[$tokenText])) {
                    $result .= $functionMappings[$tokenText];
                } elseif (isset($classMappings[$tokenText])) {
                    $result .= $classMappings[$tokenText];
                } else {
                    $result .= $tokenText;
                }
            } else {
                $result .= $tokenText;
            }
        } else {
            $result .= $token;
        }
    }
    
    return $result;
}

/**
 * Removes comments and unnecessary whitespace from PHP code
 * 
 * @param string $code The PHP code
 * @return string Minified code
 */
function removeCommentsAndWhitespace($code) {
    // Load code into a temporary file for token analysis
    $tempFile = tempnam(sys_get_temp_dir(), 'obf');
    file_put_contents($tempFile, $code);
    
    // Parse the PHP file and get tokens
    $tokens = token_get_all(file_get_contents($tempFile));
    unlink($tempFile);
    
    // Remove comments and unnecessary whitespace
    $result = '';
    $prevTokenWasWS = false;
    $prevTokenWasNewline = false;
    
    foreach ($tokens as $token) {
        if (is_array($token)) {
            list($tokenId, $tokenText) = $token;
            
            // Skip comments
            if ($tokenId === T_COMMENT || $tokenId === T_DOC_COMMENT) {
                continue;
            }
            
            // Handle whitespace
            if ($tokenId === T_WHITESPACE) {
                // Preserve just one space between tokens if needed
                if (!$prevTokenWasWS && !$prevTokenWasNewline) {
                    $result .= ' ';
                }
                $prevTokenWasWS = true;
                $prevTokenWasNewline = (strpos($tokenText, "\n") !== false);
                continue;
            }
            
            // Add the token text to the result
            $result .= $tokenText;
            $prevTokenWasWS = false;
            $prevTokenWasNewline = false;
        } else {
            // Add the token to the result
            $result .= $token;
            $prevTokenWasWS = false;
            $prevTokenWasNewline = false;
        }
    }
    
    return $result;
}

/**
 * Encodes string literals in PHP code using base64
 * 
 * @param string $code The PHP code
 * @return string Code with encoded strings
 */
function encodeStrings($code) {
    // Load code into a temporary file for token analysis
    $tempFile = tempnam(sys_get_temp_dir(), 'obf');
    file_put_contents($tempFile, $code);
    
    // Parse the PHP file and get tokens
    $tokens = token_get_all(file_get_contents($tempFile));
    unlink($tempFile);
    
    // Encode string literals
    $result = '';
    foreach ($tokens as $token) {
        if (is_array($token)) {
            list($tokenId, $tokenText) = $token;
            
            // Process string literals
            if ($tokenId === T_CONSTANT_ENCAPSED_STRING || $tokenId === T_ENCAPSED_AND_WHITESPACE) {
                // Only encode strings longer than 3 characters
                if (strlen($tokenText) > 3) {
                    $stringContent = '';
                    
                    // Extract string content without quotes
                    if ($tokenText[0] === '"' && $tokenText[strlen($tokenText) - 1] === '"') {
                        $stringContent = substr($tokenText, 1, -1);
                    } elseif ($tokenText[0] === "'" && $tokenText[strlen($tokenText) - 1] === "'") {
                        $stringContent = substr($tokenText, 1, -1);
                    } else {
                        // Not a typical string, add as is
                        $result .= $tokenText;
                        continue;
                    }
                    
                    // Skip strings that look like they might be part of code or HTML
                    if (preg_match('/^[a-zA-Z0-9_]+$/', $stringContent) ||
                        strpos($stringContent, '<?') !== false ||
                        strpos($stringContent, '?>') !== false ||
                        strpos($stringContent, '<') !== false) {
                        $result .= $tokenText;
                        continue;
                    }
                    
                    // Encode string
                    $encoded = base64_encode($stringContent);
                    $result .= 'base64_decode("' . $encoded . '")';
                } else {
                    $result .= $tokenText;
                }
            } else {
                $result .= $tokenText;
            }
        } else {
            $result .= $token;
        }
    }
    
    return $result;
}

/**
 * Generate a random string for obfuscation
 * 
 * @param int $length Length of the random string
 * @return string Random string
 */
function obfuscatorGenerateRandomString($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Check if the PHP code is valid
 * 
 * @param string $code The PHP code to check
 * @return bool True if valid, false otherwise
 */
function isValidPHP($code) {
    // Create a temporary file
    $tempFile = tempnam(sys_get_temp_dir(), 'obf');
    file_put_contents($tempFile, $code);
    
    // Use PHP to check syntax
    $output = [];
    $returnVar = 0;
    exec('php -l ' . escapeshellarg($tempFile), $output, $returnVar);
    
    // Clean up
    unlink($tempFile);
    
    // Return true if the syntax is valid
    return $returnVar === 0;
}
?>
