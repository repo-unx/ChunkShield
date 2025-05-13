            </div><!-- End of main-content -->
        </div><!-- End of container -->
    </div><!-- End of container-fluid -->
    
    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-md-start">
                    <h5><i class="fas fa-shield-alt me-2"></i> ChunkShield</h5>
                    <p class="small mb-0">Secure your PHP applications with advanced obfuscation, encryption, and license protection.</p>
                </div>
                <div class="col-md-4 text-md-center">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> ChunkShield</p>
                    <p class="small mb-0">Version 1.0.0</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex justify-content-md-end justify-content-center">
                        <a href="https://github.com/repo-unx/ChunkShield" class="text-white me-3" target="_blank"><i class="fab fa-github"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="assets/js/main.js"></script>
    <?php if (defined('DEBUG_MODE') && DEBUG_MODE && isset($GLOBALS['page_start_time'])): ?>
    <!-- Performance Debug Information -->
    <div class="container mt-4">
        <div class="card bg-light text-muted">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Performance Metrics</span>
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#performanceInfo">
                    Show Details
                </button>
            </div>
            <div class="collapse" id="performanceInfo">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Page Generation</h6>
                            <ul class="list-unstyled">
                                <li>Load Time: <?php echo round((microtime(true) - $GLOBALS['page_start_time']) * 1000, 2); ?> ms</li>
                                <li>Memory Usage: <?php echo round(memory_get_usage() / 1024 / 1024, 2); ?> MB</li>
                                <li>Peak Memory: <?php echo round(memory_get_peak_usage() / 1024 / 1024, 2); ?> MB</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>PHP Info</h6>
                            <ul class="list-unstyled">
                                <li>PHP Version: <?php echo PHP_VERSION; ?></li>
                                <li>Zend Version: <?php echo zend_version(); ?></li>
                                <li>Server Software: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'PHP Development Server'; ?></li>
                            </ul>
                        </div>
                    </div>
                    <?php if (class_exists('CacheManager')): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Cache Status</h6>
                            <?php 
                            $cache_manager = CacheManager::getInstance();
                            $cache_status = $cache_manager->getStatus();
                            ?>
                            <table class="table table-sm table-bordered table-striped small">
                                <tbody>
                                    <tr>
                                        <td>Cache Enabled</td>
                                        <td><?php echo $cache_status['enabled'] ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Cache Hits</td>
                                        <td><?php echo $cache_status['hits']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Cache Misses</td>
                                        <td><?php echo $cache_status['misses']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Cache Hit Ratio</td>
                                        <td><?php echo $cache_status['ratio']; ?>%</td>
                                    </tr>
                                    <tr>
                                        <td>Using APCu</td>
                                        <td><?php echo $cache_status['using_apcu'] ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Using Memcached</td>
                                        <td><?php echo $cache_status['using_memcached'] ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Memory Usage</td>
                                        <td><?php echo $cache_status['memory_usage']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</body>
</html>