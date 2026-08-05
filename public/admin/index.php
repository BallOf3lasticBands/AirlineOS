<?php
require_once __DIR__ . '/../../includes/config.php';
$require_admin = true; // protect this page for admin users only
require_once __DIR__ . '/../../includes/auth.php';

$general_query = 'SELECT * FROM settingsindex WHERE category="general" LIMIT 200';
$general_result = mysqli_query($db_connect, $general_query);

$style_query = 'SELECT * FROM settingsindex WHERE category="style" LIMIT 200';
$style_result = mysqli_query($db_connect, $style_query);

$module_query = 'SELECT * FROM settingsindex WHERE category="modules" LIMIT 200';
$module_result = mysqli_query($db_connect, $module_query);

$system_query = 'SELECT * FROM settingsindex WHERE category="system" LIMIT 200';
$system_result = mysqli_query($db_connect, $system_query);

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
    <?php if ($general_result && $general_result->num_rows > 0): ?>
    <?php while ($general_data = mysqli_fetch_assoc($general_result)): ?>
        <p>cialchars($general_data['settingurl']) ?>"><?= htmlspecialchars($general_data['settingname']) ?></a></p>
    <?php endwhile; ?>
    <?php else: ?>
        <p>No settings yet</p>
    <?php endif; ?>
</div>

<div id="style" class="tabcontent">
    <?php if ($style_result && $style_result->num_rows > 0): ?>
    <?php while ($style_data = mysqli_fetch_assoc($style_result)): ?>
        <p><a href="<?= htmlspecialchars($style_data['settingurl']) ?>"><?= htmlspecialchars($style_data['settingname']) ?></a></p>
    <?php endwhile; ?>
    <?php else: ?>
        <p>No settings yet</p>
    <?php endif; ?>
</div>

<div id="modules" class="tabcontent">
    <?php if ($module_result && $module_result->num_rows > 0): ?>
    <?php while ($modules_data = mysqli_fetch_assoc($module_result)): ?>
        <p><a href="<?= htmlspecialchars($modules_data['settingurl']) ?>"><?= htmlspecialchars($modules_data['settingname']) ?></a></p>
    <?php endwhile; ?>
    <?php else: ?>
        <p>No settings yet</p>
    <?php endif; ?>
</div>

<div id="system" class="tabcontent">
    <?php if ($system_result && $system_result->num_rows > 0): ?>
    <?php while ($system_data = mysqli_fetch_assoc($system_result)): ?>
        <p><a href="<?= htmlspecialchars($system_data['settingurl']) ?>"><?= htmlspecialchars($system_data['settingname']) ?></a></p>
    <?php endwhile; ?>
    <?php else: ?>
        <p>No settings yet</p>
    <?php endif; ?>
</div>
</div>
</div>
<?php if ($result) { mysqli_free_result($result); } ?>
<?php require_once __DIR__ . '/../../includes/footer.php';
