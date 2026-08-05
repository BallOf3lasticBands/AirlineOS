<?php
require_once __DIR__ . '/../../includes/config.php';
$require_admin = true;
require_once __DIR__ . '/../../includes/auth.php';

$page_title = 'Modules';
require_once __DIR__ . '/../../includes/header.php';

// handle activate/deactivate
if (isset($_GET['action']) && isset($_GET['slug'])) {
    $action = $_GET['action'];
    $slug = $_GET['slug'];
    if ($action === 'activate') {
        activate_module($db_connect, $slug);
        // reload modules registrations
        modules_init($db_connect);
        header('Location: modules.php');
        exit();
    } elseif ($action === 'deactivate') {
        deactivate_module($db_connect, $slug);
        modules_init($db_connect);
        header('Location: modules.php');
        exit();
    }
}

// handle settings POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['module_slug'])) {
    $slug = $_POST['module_slug'];
    foreach ($_POST as $k => $v) {
        if ($k === 'module_slug') continue;
        set_module_setting($db_connect, $slug, $k, $v);
    }
    header('Location: modules.php');
    exit();
}

$modules = modules_list_filesystem();
?>
<div class="row">
    <div class="col-md-12">
        <h2>Installed Modules</h2>
        <table class="table">
            <thead>
                <tr><th>Slug</th><th>Name</th><th>Version</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php foreach ($modules as $m):
                    $slug = $m['id'];
                    $active = is_module_active($db_connect, $slug);
                ?>
                    <tr>
                        <td><?= htmlspecialchars($slug) ?></td>
                        <td><?= htmlspecialchars($m['name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($m['version'] ?? '') ?></td>
                        <td>
                            <?php if ($active): ?>
                                <a href="?action=deactivate&slug=<?= urlencode($slug) ?>" class="btn btn-warning">Deactivate</a>
                            <?php else: ?>
                                <a href="?action=activate&slug=<?= urlencode($slug) ?>" class="btn btn-success">Activate</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        // show settings for active modules
        foreach ($modules as $m) {
            $slug = $m['id'];
            if (!is_module_active($db_connect, $slug)) continue;
            // include registration if not already
            $reg = modules_get_registration($slug);
            if (!$reg) continue;
            if (!empty($reg['settings']) && is_array($reg['settings'])) {
                foreach ($reg['settings'] as $section) {
                    echo '<h3>' . htmlspecialchars($section['title'] ?? ($m['name'] ?? $slug)) . '</h3>';
                    echo '<form method="post">';
                    echo '<input type="hidden" name="module_slug" value="' . htmlspecialchars($slug) . '" />';
                    if (!empty($section['fields']) && is_array($section['fields'])) {
                        foreach ($section['fields'] as $field) {
                            $fname = $field['name'];
                            $label = $field['label'] ?? $fname;
                            $type = $field['type'] ?? 'text';
                            $val = get_module_setting($db_connect, $slug, $fname, $field['default'] ?? '');
                            echo '<div class="form-group">';
                            echo '<label>' . htmlspecialchars($label) . '</label>';
                            if ($type === 'color') {
                                echo '<input class="form-control" type="color" name="' . htmlspecialchars($fname) . '" value="' . htmlspecialchars($val) . '" />';
                            } else {
                                echo '<input class="form-control" type="text" name="' . htmlspecialchars($fname) . '" value="' . htmlspecialchars($val) . '" />';
                            }
                            echo '</div>';
                        }
                    }
                    echo '<button class="btn btn-primary" type="submit">Save</button>';
                    echo '</form>';
                }
            }
        }

        ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php';
