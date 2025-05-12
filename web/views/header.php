<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChunkShield - PHP Code Protection System</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg">
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="app-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="app-brand">
                    <h1 class="app-title h3 mb-0">
                        <i class="fas fa-shield-alt me-2"></i>ChunkShield
                    </h1>
                </div>
                <div class="d-flex align-items-center">
                    <span class="app-tagline me-3 d-none d-md-block">Secure Your PHP Code with Advanced Protection</span>
                    <div class="theme-toggle-wrapper me-2">
                        <button class="btn btn-sm theme-toggle" id="darkModeToggle" title="Toggle Light/Dark Mode">
                            <i class="fas fa-sun light-icon"></i>
                            <i class="fas fa-moon dark-icon"></i>
                            <span class="theme-indicator d-none">Light Mode</span>
                        </button>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="index.php?tab=help"><i class="fas fa-question-circle me-2"></i>Help Center</a></li>
                            <li><a class="dropdown-item" href="index.php?tab=docs"><i class="fas fa-book me-2"></i>Documentation</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="index.php?action=clear_all"><i class="fas fa-trash-alt me-2"></i>Clear All Progress</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Container -->
    <div class="container-fluid py-4">
        <div class="container">
            <!-- Flash Messages -->
            <?php
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                      <i class="fas fa-check-circle me-2"></i>' . $_SESSION['success'] . '
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                unset($_SESSION['success']);
            }
            
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <i class="fas fa-exclamation-circle me-2"></i>' . $_SESSION['error'] . '
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                unset($_SESSION['error']);
            }
            ?>
            
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_tab === 'upload' ? 'active' : ''; ?>" href="index.php?tab=upload">
                        <i class="fas fa-upload"></i> <span>Upload</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_tab === 'obfuscate' ? 'active' : ''; ?> <?php echo !isset($_SESSION['uploaded_file']) ? 'disabled' : ''; ?>" href="index.php?tab=obfuscate">
                        <i class="fas fa-code"></i> <span>Obfuscate</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_tab === 'chunk' ? 'active' : ''; ?> <?php echo !isset($_SESSION['obfuscated_file']) ? 'disabled' : ''; ?>" href="index.php?tab=chunk">
                        <i class="fas fa-puzzle-piece"></i> <span>Chunk</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_tab === 'loader' ? 'active' : ''; ?> <?php echo !isset($_SESSION['chunks_info']) ? 'disabled' : ''; ?>" href="index.php?tab=loader">
                        <i class="fas fa-file-code"></i> <span>Loader</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_tab === 'license' ? 'active' : ''; ?>" href="index.php?tab=license">
                        <i class="fas fa-key"></i> <span>License</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_tab === 'output' ? 'active' : ''; ?> <?php echo !isset($_SESSION['loader_file']) ? 'disabled' : ''; ?>" href="index.php?tab=output">
                        <i class="fas fa-download"></i> <span>Output</span>
                    </a>
                </li>
            </ul>
            
            <!-- Step Indicator -->
            <div class="row step-indicator d-none d-md-flex">
                <div class="col">
                    <div class="step <?php echo in_array($current_tab, ['upload', 'obfuscate', 'chunk', 'loader', 'license', 'output']) ? 'active' : ''; ?>">
                        <span class="step-number">1</span> Upload File
                    </div>
                </div>
                <div class="col">
                    <div class="step <?php echo in_array($current_tab, ['obfuscate', 'chunk', 'loader', 'license', 'output']) ? 'active' : ''; ?>">
                        <span class="step-number">2</span> Obfuscate
                    </div>
                </div>
                <div class="col">
                    <div class="step <?php echo in_array($current_tab, ['chunk', 'loader', 'license', 'output']) ? 'active' : ''; ?>">
                        <span class="step-number">3</span> Chunk & Encrypt
                    </div>
                </div>
                <div class="col">
                    <div class="step <?php echo in_array($current_tab, ['loader', 'license', 'output']) ? 'active' : ''; ?>">
                        <span class="step-number">4</span> Generate Loader
                    </div>
                </div>
                <div class="col">
                    <div class="step <?php echo in_array($current_tab, ['license', 'output']) ? 'active' : ''; ?>">
                        <span class="step-number">5</span> Create License
                    </div>
                </div>
                <div class="col">
                    <div class="step <?php echo $current_tab === 'output' ? 'active' : ''; ?>">
                        <span class="step-number">6</span> Output
                    </div>
                </div>
            </div>
            
            <!-- Main Content Area -->
            <div class="main-content"><?php /* Main content will be here */ ?>