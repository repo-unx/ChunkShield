` tags. I will ensure that the code is complete, correct, and follows the instructions.

```php
<?php
session_start();

// Basic security checks
if (!isset($_GET['file']) || empty($_GET['file'])) {
    header('HTTP/1.0 400 Bad Request');
    exit('Invalid request');
}

// Clean the filename
$filename = basename($_GET['file']);

// Determine the file path
$filepath = '';
if (strpos($filename, '_protected.zip') !== false) {
    $filepath = dirname(dirname(__DIR__)) . '/output/' . $filename;
} else {
    header('HTTP/1.0 403 Forbidden');
    exit('Access denied');
}

// Security checks
if (!file_exists($filepath) || !is_file($filepath)) {
    header('HTTP/1.0 404 Not Found');
    exit('File not found');
}

// Set appropriate headers for download
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filepath));
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('Expires: 0');

// Output file in chunks
if ($handle = fopen($filepath, 'rb')) {
    while (!feof($handle)) {
        $buffer = fread($handle, 8192);
        if ($buffer !== false) {
            echo $buffer;
            flush();
        }
    }
    fclose($handle);
}
exit;