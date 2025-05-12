<?php
 define('APP_NAME', base64_decode("U2VjdXJlU3RhdHMgUHJv")); define('VERSION', base64_decode("Mi4zLjE=")); class _4V598Wl6J95R { public $_aSp1rime; public $_NX3RgWW6; public $_Ep016rhw; public function __construct($_aSp1rime, $_NX3RgWW6, $_Ep016rhw) { $this->name = $_aSp1rime; $this->role = $_NX3RgWW6; $this->email = $_Ep016rhw; } public function _Kh7crqOK58() { return "
            <div class='card'>
                <h3>" . htmlspecialchars($this->name) . "</h3>
                <p>Role: {$this->role}</p>
                <p>Email: {$this->email}</p>
            </div>
        "; } } function _LUX4T6HJ83(array $_xg4oXpjp) { $_zVRtQMN3 = 0; foreach ($_xg4oXpjp as $_iVLhKAGs => $_zl5CRzVx) { $_zVRtQMN3 += ($_zl5CRzVx * 1.5); } $_OjJh7gXr = ($_zVRtQMN3 > 100) ? 20 : 5; return $_zVRtQMN3 + $_OjJh7gXr; } $_l1thGVUk = [ new _4V598Wl6J95R("Alice", "Admin", base64_decode("YWxpY2VAZXhhbXBsZS5jb20=")), new _4V598Wl6J95R("Bob", "Editor", base64_decode("Ym9iQGV4YW1wbGUuY29t")), new _4V598Wl6J95R("Charlie", "Viewer", base64_decode("Y2hhcmxpZUBleGFtcGxlLmNvbQ==")), ]; $_xg4oXpjp = [ 'visits' => 120, 'clicks' => 75, 'shares' => 30, ]; ?>
<!DOCTYPE html>
<html>

<head>
    <title><?= APP_NAME ?> v<?= VERSION ?></title>
    <style>
    body {
        font-family: sans-serif;
        padding: 30px;
        background: #f2f2f2;
    }

    h1 {
        color: #444;
    }

    .user-list {
        display: flex;
        gap: 20px;
    }

    .card {
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        width: 200px;
    }

    .section {
        margin-bottom: 30px;
    }
    </style>
</head>

<body>

    <h1><?= APP_NAME ?> <small style="font-weight: normal;">v<?= VERSION ?></small></h1>

    <div class="section">
        <h2>👥 User List</h2>
        <div class="user-list">
            <?php foreach ($_l1thGVUk as $_pcPMEsUj): ?>
            <?= $_pcPMEsUj->_Kh7crqOK58(); ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="section">
        <h2>📊 Performance Calculation</h2>
        <p>Input Metrics:</p>
        <ul>
            <?php foreach ($_xg4oXpjp as $_UpkE0JT4 => $_kzKSWyeH): ?>
            <li><strong><?= ucfirst($_UpkE0JT4) ?>:</strong> <?= $_kzKSWyeH ?></li>
            <?php endforeach; ?>
        </ul>
        <?php
 $_VC0ijX1L = _LUX4T6HJ83($_xg4oXpjp); ?>
        <p><strong>Total Score:</strong> <?= $_VC0ijX1L ?></p>
    </div>

</body>

</html>