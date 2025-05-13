<?php

// === Konfigurasi Program ===
define('APP_NAME', 'SecureStats Pro');
define('VERSION', '2.3.1');

class User {
    public $name;
    public $role;
    public $email;

    public function __construct($name, $role, $email) {
        $this->name = $name;
        $this->role = $role;
        $this->email = $email;
    }

    public function displayCard() {
        return "
            <div class='card'>
                <h3>" . htmlspecialchars($this->name) . "</h3>
                <p>Role: {$this->role}</p>
                <p>Email: {$this->email}</p>
            </div>
        ";
    }
}

function calculatePerformance(array $metrics) {
    $score = 0;
    foreach ($metrics as $key => $value) {
        $score += ($value * 1.5);
    }
    $bonus = ($score > 100) ? 20 : 5;
    return $score + $bonus;
}

$users = [
    new User("Alice", "Admin", "alice@example.com"),
    new User("Bob", "Editor", "bob@example.com"),
    new User("Charlie", "Viewer", "charlie@example.com"),
];

$metrics = [
    'visits' => 120,
    'clicks' => 75,
    'shares' => 30,
];

?>
<!DOCTYPE html>
<html>
<head>
    <title><?= APP_NAME ?> v<?= VERSION ?></title>
    <style>
        body { font-family: sans-serif; padding: 30px; background: #f2f2f2; }
        h1 { color: #444; }
        .user-list { display: flex; gap: 20px; }
        .card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            width: 200px;
        }
        .section { margin-bottom: 30px; }
    </style>
</head>
<body>

<h1><?= APP_NAME ?> <small style="font-weight: normal;">v<?= VERSION ?></small></h1>

<div class="section">
    <h2>👥 User List</h2>
    <div class="user-list">
        <?php foreach ($users as $user): ?>
            <?= $user->displayCard(); ?>
        <?php endforeach; ?>
    </div>
</div>

<div class="section">
    <h2>📊 Performance Calculation</h2>
    <p>Input Metrics:</p>
    <ul>
        <?php foreach ($metrics as $k => $v): ?>
            <li><strong><?= ucfirst($k) ?>:</strong> <?= $v ?></li>
        <?php endforeach; ?>
    </ul>
    <?php
    $result = calculatePerformance($metrics);
    ?>
    <p><strong>Total Score:</strong> <?= $result ?></p>
</div>

</body>
</html>