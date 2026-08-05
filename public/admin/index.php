<?php
require_once __DIR__ . '/../../includes/config.php';
$require_admin = true; // protect this page for admin users only
require_once __DIR__ . '/../../includes/auth.php';

$query = 'SELECT * FROM customers LIMIT 200';
$result = mysqli_query($db_connect, $query);

$page_title = 'Administration';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Settings</h2>
        </div>
        <div class="alert alert-info">Only signed-in admins can see this page.</div>

<div class="tab">
  <button class="tablinks" onclick="openTab(event, 'general')" id="defaultOpen">General</button>
  <button class="tablinks" onclick="openTab(event, 'style')">Appearance</button>
  <button class="tablinks" onclick="openTab(event, 'modules')">Modules</button>
  <button class="tablinks" onclick="openTab(event, 'system')">System</button>
</div>

<div id="general" class="tabcontent">
  <p>No settings yet</p>
</div>

<div id="style" class="tabcontent">
  <p>No settings yet</p>
</div>

<div id="modules" class="tabcontent">
  <a href="./modules.php">Module management</a>
</div>

<div id="system" class="tabcontent">
  <p>No settings yet</p>
</div>
</div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php';
