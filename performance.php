<?php
/**
 * ChunkShield Performance Optimization
 * 
 * This file contains optimizations for Replit's environment.
 */

// Do not enable output buffering here, as it's already started in index.php
// This prevents duplicate output buffering issues

/**
 * Cache manager for optimizing repeated operations
 */
class CacheManager {
    private static $instance = null;
    private $cache = [];
    private $cacheTTL = [];
    private $cacheHits = 0;
    private $cacheMisses = 0;
    private $cacheEnabled = true;
    private $useAPCu = false;
    private $useMemcached = false;
    private $memcached = null;
    private $cachePrefix = 'cs_'; // Cache key prefix

    /**
     * Get singleton instance
     * 
     * @return CacheManager
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new CacheManager();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Initialize cache and detect available acceleration methods
        register_shutdown_function([$this, 'shutdown']);

        // Check for APCu - preferred caching method
        if (function_exists('apcu_enabled') && apcu_enabled()) {
            $this->useAPCu = true;
        }

        // Check for Memcached as a fallback
        if (!$this->useAPCu && class_exists('Memcached')) {
            $this->memcached = new Memcached();
            // Try to use local memcached server if available
            if (@$this->memcached->addServer('127.0.0.1', 11211)) {
                $stats = @$this->memcached->getStats();
                // Check if connection actually works
                if ($stats && isset($stats['127.0.0.1:11211']['pid']) && $stats['127.0.0.1:11211']['pid'] > 0) {
                    $this->useMemcached = true;
                }
            }
        }
    }

    /**
     * Shutdown function to log cache statistics
     */
    public function shutdown() {
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            error_log("Cache hits: {$this->cacheHits}, Cache misses: {$this->cacheMisses}");
        }
    }

    /**
     * Enable or disable cache
     * 
     * @param bool $enabled
     */
    public function setEnabled($enabled) {
        $this->cacheEnabled = $enabled;
    }

    /**
     * Get cache status report
     *
     * @return array Cache status information
     */
    public function getStatus() {
        return [
            'enabled' => $this->cacheEnabled,
            'hits' => $this->cacheHits,
            'misses' => $this->cacheMisses,
            'ratio' => ($this->cacheHits + $this->cacheMisses > 0) ? 
                round($this->cacheHits / ($this->cacheHits + $this->cacheMisses) * 100, 2) : 0,
            'using_apcu' => $this->useAPCu,
            'using_memcached' => $this->useMemcached,
            'memory_usage' => $this->getMemoryUsage()
        ];
    }

    /**
     * Get approximate memory usage
     *
     * @return string Formatted memory usage
     */
    private function getMemoryUsage() {
        // For APCu we can get actual memory usage
        if ($this->useAPCu && function_exists('apcu_cache_info')) {
            $info = @apcu_cache_info(true);
            if ($info && isset($info['mem_size'])) {
                return $this->formatBytes($info['mem_size']);
            }
        }

        // For array cache, estimate based on memory_get_usage
        return $this->formatBytes(memory_get_usage(true));
    }

    /**
     * Format bytes to readable format
     * 
     * @param int $bytes
     * @return string
     */
    private function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Get an item from cache
     * 
     * @param string $key Cache key
     * @param callable $callback Callback to get data if not in cache
     * @param int $ttl Time to live in seconds (0 = forever)
     * @return mixed Cached data
     */
    public function get($key, $callback, $ttl = 0) {
        if (!$this->cacheEnabled) {
            return $callback();
        }

        $prefixedKey = $this->cachePrefix . $key;
        $data = null;
        $found = false;

        // Try to get from APCu if enabled
        if ($this->useAPCu) {
            $success = false;
            $data = apcu_fetch($prefixedKey, $success);
            if ($success) {
                $this->cacheHits++;
                $found = true;
                return $data;
            }
        } 
        // Try to get from Memcached if enabled
        else if ($this->useMemcached) {
            $data = $this->memcached->get($prefixedKey);
            if ($this->memcached->getResultCode() === Memcached::RES_SUCCESS) {
                $this->cacheHits++;
                $found = true;
                return $data;
            }
        } 
        // Fallback to array cache
        else {
            // Check if item exists in cache and is not expired
            if (isset($this->cache[$key]) && 
                ($ttl === 0 || $this->cacheTTL[$key] > time())) {
                $this->cacheHits++;
                return $this->cache[$key];
            }
        }

        // Item not in cache or expired, call callback
        $this->cacheMisses++;
        $data = $callback();

        // Store in cache
        $this->set($key, $data, $ttl);

        return $data;
    }

    /**
     * Store an item in cache
     * 
     * @param string $key Cache key
     * @param mixed $data Data to store
     * @param int $ttl Time to live in seconds (0 = forever)
     */
    public function set($key, $data, $ttl = 0) {
        if (!$this->cacheEnabled) {
            return;
        }

        $prefixedKey = $this->cachePrefix . $key;

        // APCu storage
        if ($this->useAPCu) {
            if ($ttl > 0) {
                apcu_store($prefixedKey, $data, $ttl);
            } else {
                apcu_store($prefixedKey, $data);
            }
        } 
        // Memcached storage
        else if ($this->useMemcached) {
            $this->memcached->set($prefixedKey, $data, $ttl > 0 ? $ttl : 0);
        } 
        // Array cache fallback
        else {
            $this->cache[$key] = $data;
            $this->cacheTTL[$key] = $ttl > 0 ? time() + $ttl : 0;
        }
    }

    /**
     * Check if an item exists in cache
     * 
     * @param string $key Cache key
     * @return bool True if item exists and is not expired
     */
    public function has($key) {
        if (!$this->cacheEnabled) {
            return false;
        }

        $prefixedKey = $this->cachePrefix . $key;

        // Check APCu
        if ($this->useAPCu) {
            return apcu_exists($prefixedKey);
        } 
        // Check Memcached
        else if ($this->useMemcached) {
            $this->memcached->get($prefixedKey);
            return $this->memcached->getResultCode() === Memcached::RES_SUCCESS;
        } 
        // Check array cache
        else {
            return isset($this->cache[$key]) && 
                ($this->cacheTTL[$key] === 0 || $this->cacheTTL[$key] > time());
        }
    }

    /**
     * Remove an item from cache
     * 
     * @param string $key Cache key
     */
    public function remove($key) {
        $prefixedKey = $this->cachePrefix . $key;

        // Remove from APCu
        if ($this->useAPCu) {
            apcu_delete($prefixedKey);
        } 
        // Remove from Memcached
        else if ($this->useMemcached) {
            $this->memcached->delete($prefixedKey);
        } 
        // Remove from array cache
        else if (isset($this->cache[$key])) {
            unset($this->cache[$key]);
            unset($this->cacheTTL[$key]);
        }
    }

    /**
     * Clear the entire cache
     */
    public function clear() {
        // Clear APCu
        if ($this->useAPCu) {
            // Only clear our cache entries
            $iterator = new APCUIterator('/^' . preg_quote($this->cachePrefix) . '/');
            apcu_delete($iterator);
        } 
        // Clear Memcached
        else if ($this->useMemcached) {
            $this->memcached->flush();
        } 
        // Clear array cache
        else {
            $this->cache = [];
            $this->cacheTTL = [];
        }
    }
}

/**
 * Optimized file reader for large files
 */
class OptimizedFileReader {
    private $filePath;
    private $fileHandle;
    private $fileSize;
    private $memoryLimit;
    private $chunkSize;

    /**
     * Constructor
     * 
     * @param string $filePath Path to the file
     * @param int $chunkSize Size of chunks to read (default: 1MB)
     */
    public function __construct($filePath, $chunkSize = 1048576) {
        $this->filePath = $filePath;
        $this->chunkSize = $chunkSize;
        $this->memoryLimit = $this->getMemoryLimit();

        // Calculate a reasonable chunk size based on memory limit
        // Use no more than 10% of available memory
        $recommendedChunk = floor($this->memoryLimit * 0.1);
        if ($recommendedChunk < $this->chunkSize && $recommendedChunk > 65536) {
            $this->chunkSize = $recommendedChunk;
        }
    }

    /**
     * Get available memory limit in bytes
     * 
     * @return int Memory limit in bytes
     */
    private function getMemoryLimit() {
        $memoryLimit = ini_get('memory_limit');

        // Convert to bytes
        if (preg_match('/^(\d+)(.)$/', $memoryLimit, $matches)) {
            if ($matches[2] == 'G') {
                $memoryLimit = $matches[1] * 1024 * 1024 * 1024;
            } else if ($matches[2] == 'M') {
                $memoryLimit = $matches[1] * 1024 * 1024;
            } else if ($matches[2] == 'K') {
                $memoryLimit = $matches[1] * 1024;
            }
        }

        return (int)$memoryLimit;
    }

    /**
     * Open the file for reading
     * 
     * @return bool True if successful
     */
    public function open() {
        if (!file_exists($this->filePath)) {
            return false;
        }

        $this->fileHandle = fopen($this->filePath, 'r');
        if ($this->fileHandle === false) {
            return false;
        }

        $this->fileSize = filesize($this->filePath);
        return true;
    }

    /**
     * Close the file
     */
    public function close() {
        if ($this->fileHandle) {
            fclose($this->fileHandle);
            $this->fileHandle = null;
        }
    }

    /**
     * Read the file with progress callback
     * 
     * @param callable $callback Function to call with each chunk of data
     * @param callable $progressCallback Function to call with progress updates
     * @return bool True if successful
     */
    public function read($callback, $progressCallback = null) {
        if (!$this->fileHandle) {
            if (!$this->open()) {
                return false;
            }
        }

        $bytesRead = 0;

        while (!feof($this->fileHandle)) {
            $data = fread($this->fileHandle, $this->chunkSize);
            $bytesRead += strlen($data);

            // Call the data callback
            $result = $callback($data);
            if ($result === false) {
                break;
            }

            // Call the progress callback
            if ($progressCallback && $this->fileSize > 0) {
                $progress = min(100, round(($bytesRead / $this->fileSize) * 100));
                $progressCallback($progress, $bytesRead, $this->fileSize);
            }
        }

        $this->close();
        return true;
    }

    /**
     * Read the entire file content in an optimized way
     * Uses memory mapping for large files if available
     * 
     * @return string|false File content or false on failure
     */
    public function readAll() {
        // For small files, use regular file_get_contents
        if (filesize($this->filePath) < $this->chunkSize) {
            return file_get_contents($this->filePath);
        }

        // For larger files, try memory mapping if available
        if (function_exists('mmap') && function_exists('munmap')) {
            $this->open();
            $map = mmap(null, $this->fileSize, PROT_READ, MAP_PRIVATE, $this->fileHandle, 0);
            if ($map) {
                $data = $map;
                munmap($map);
                $this->close();
                return $data;
            }
        }

        // Fall back to chunked reading
        $content = '';
        $this->read(function($data) use (&$content) {
            $content .= $data;
            return true;
        });

        return $content;
    }
}

/**
 * Memory usage tracker for optimizing memory-intensive operations
 */
class MemoryTracker {
    private static $peakUsage = 0;
    private static $lastUsage = 0;
    private static $trackingEnabled = false;

    /**
     * Start tracking memory usage
     */
    public static function startTracking() {
        self::$trackingEnabled = true;
        self::$peakUsage = 0;
        self::$lastUsage = memory_get_usage(true);
    }

    /**
     * Stop tracking memory usage
     * 
     * @return array Memory usage statistics
     */
    public static function stopTracking() {
        $currentUsage = memory_get_usage(true);
        self::updatePeak($currentUsage);

        $stats = [
            'peak' => self::$peakUsage,
            'current' => $currentUsage,
            'difference' => $currentUsage - self::$lastUsage
        ];

        self::$trackingEnabled = false;
        return $stats;
    }

    /**
     * Mark a checkpoint in memory tracking
     * 
     * @param string $label Label for the checkpoint
     * @return array Memory usage at this checkpoint
     */
    public static function checkpoint($label) {
        if (!self::$trackingEnabled) {
            return null;
        }

        $currentUsage = memory_get_usage(true);
        self::updatePeak($currentUsage);

        $diff = $currentUsage - self::$lastUsage;
        self::$lastUsage = $currentUsage;

        return [
            'label' => $label,
            'usage' => $currentUsage,
            'diff' => $diff,
            'peak' => self::$peakUsage,
            'formatted' => self::formatBytes($currentUsage)
        ];
    }

    /**
     * Update peak memory usage
     * 
     * @param int $currentUsage Current memory usage
     */
    private static function updatePeak($currentUsage) {
        if ($currentUsage > self::$peakUsage) {
            self::$peakUsage = $currentUsage;
        }
    }

    /**
     * Format bytes into a human-readable format
     * 
     * @param int $bytes Bytes to format
     * @param int $precision Decimal precision
     * @return string Formatted size string
     */
    public static function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

/**
 * Performance-optimized file operations for ChunkShield
 */
class OptimizedFileOperations {
    private $cache;

    /**
     * Constructor
     */
    public function __construct() {
        $this->cache = CacheManager::getInstance();
    }

    /**
     * Read a file with caching
     * 
     * @param string $filepath Path to the file
     * @param int $cacheTTL Cache time to live in seconds (0 = do not cache)
     * @return string|false File content or false on failure
     */
    public function readFile($filepath, $cacheTTL = 0) {
        if (!file_exists($filepath)) {
            return false;
        }

        if ($cacheTTL <= 0) {
            return file_get_contents($filepath);
        }

        // Use cache for repeated reads
        $cacheKey = 'file_' . md5($filepath);
        return $this->cache->get($cacheKey, function() use ($filepath) {
            return file_get_contents($filepath);
        }, $cacheTTL);
    }

    /**
     * Write content to a file optimized for Replit
     * 
     * @param string $filepath Path to the file
     * @param string $content Content to write
     * @param bool $invalidateCache Whether to invalidate cache for this file
     * @return bool True if successful
     */
    public function writeFile($filepath, $content, $invalidateCache = true) {
        // Ensure directory exists
        $dir = dirname($filepath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $success = file_put_contents($filepath, $content, LOCK_EX);

        if ($success !== false && $invalidateCache) {
            $cacheKey = 'file_' . md5($filepath);
            $this->cache->remove($cacheKey);
        }

        return $success !== false;
    }

    /**
     * Optimized file copy for large files
     * 
     * @param string $source Source file path
     * @param string $destination Destination file path
     * @param callable $progressCallback Progress callback function
     * @return bool True if successful
     */
    public function copyFile($source, $destination, $progressCallback = null) {
        if (!file_exists($source)) {
            return false;
        }

        // Ensure destination directory exists
        $destDir = dirname($destination);
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        // For small files, use standard copy
        if (filesize($source) < 1048576) {
            return copy($source, $destination);
        }

        // For large files, use optimized chunked copy
        $reader = new OptimizedFileReader($source);
        $output = fopen($destination, 'w');

        if ($output === false) {
            return false;
        }

        $success = $reader->read(function($data) use ($output) {
            return fwrite($output, $data) !== false;
        }, $progressCallback);

        fclose($output);
        return $success;
    }

    /**
     * Process a file in chunks to minimize memory usage
     * 
     * @param string $filepath Path to the file
     * @param callable $processor Function to process each chunk
     * @param callable $progressCallback Progress callback function
     * @return bool True if successful
     */
    public function processFileInChunks($filepath, $processor, $progressCallback = null) {
        $reader = new OptimizedFileReader($filepath);
        return $reader->read($processor, $progressCallback);
    }
}

/**
 * Global performance optimization functions
 */

/**
 * Get optimized file operations instance
 * 
 * @return OptimizedFileOperations
 */
function get_optimized_file_ops() {
    static $instance = null;
    if ($instance === null) {
        $instance = new OptimizedFileOperations();
    }
    return $instance;
}

/**
 * Optimized function to delete directory contents
 * 
 * @param string $dir Directory path
 * @param bool $deleteDir Whether to delete the directory itself
 * @return bool True if successful
 */
function optimized_rmdir_recursive($dir, $deleteDir = true) {
    if (!is_dir($dir)) {
        return false;
    }

    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($items as $item) {
        if ($item->isDir()) {
            rmdir($item->getRealPath());
        } else {
            unlink($item->getRealPath());
        }
    }

    if ($deleteDir) {
        return rmdir($dir);
    }

    return true;
}

/**
 * Apply performance optimizations to PHP configuration
 */
function apply_php_optimizations() {
    // Set optimal PHP settings for performance
    ini_set('max_execution_time', 300);
    ini_set('memory_limit', '256M');
    ini_set('realpath_cache_size', '4096k');
    ini_set('realpath_cache_ttl', 600);

    // OPcache settings removed - cannot be temporarily enabled at runtime
}

// Apply optimizations when this file is included
apply_php_optimizations();
?>