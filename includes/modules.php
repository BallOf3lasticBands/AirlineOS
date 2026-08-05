<?php
define('MODULES_DIR', dirname(__DIR__) . '/modules');

global $ACTIVE_MODULES, $MODULE_REGISTRATIONS;
$ACTIVE_MODULES = [];
$MODULE_REGISTRATIONS = [];

function modules_init($db_connect)
{
    global $ACTIVE_MODULES, $MODULE_REGISTRATIONS;

    // ensure tables exist
    $sql1 = "CREATE TABLE IF NOT EXISTS modules (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(191) UNIQUE NOT NULL,
        name VARCHAR(191),
        version VARCHAR(50),
        active TINYINT(1) DEFAULT 0,
        installed_at DATETIME DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    mysqli_query($db_connect, $sql1);

    $sql2 = "CREATE TABLE IF NOT EXISTS module_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        module_slug VARCHAR(191) NOT NULL,
        name VARCHAR(191) NOT NULL,
        value TEXT,
        UNIQUE KEY module_setting_unique (module_slug, name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    mysqli_query($db_connect, $sql2);

    // find modules on filesystem
    $dirs = [];
    if (is_dir(MODULES_DIR)) {
        foreach (scandir(MODULES_DIR) as $f) {
            if ($f === '.' || $f === '..') continue;
            if (is_dir(MODULES_DIR . '/' . $f)) $dirs[] = MODULES_DIR . '/' . $f;
        }
    }

    // active modules in DB
    $active = [];
    $res = mysqli_query($db_connect, "SELECT slug FROM modules WHERE active=1");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) $active[] = $row['slug'];
        mysqli_free_result($res);
    }

    foreach ($dirs as $dir) {
        $manifest_path = $dir . '/module.json';
        if (!file_exists($manifest_path)) continue;
        $manifest = json_decode(file_get_contents($manifest_path), true);
        $slug = $manifest['id'] ?? basename($dir);
        if (!in_array($slug, $active)) continue;
        $entry = $dir . '/' . ($manifest['entry'] ?? 'ModuleMain.php');
        if (file_exists($entry)) {
            include_once $entry;
            $fn = 'register_module_' . $slug;
            if (function_exists($fn)) {
                $reg = $fn();
                $MODULE_REGISTRATIONS[$slug] = $reg;
                $ACTIVE_MODULES[] = $slug;
            }
        }
    }
}

function modules_list_filesystem()
{
    $list = [];
    if (!is_dir(MODULES_DIR)) return $list;
    foreach (scandir(MODULES_DIR) as $f) {
        if ($f === '.' || $f === '..') continue;
        $dir = MODULES_DIR . '/' . $f;
        if (!is_dir($dir)) continue;
        $manifest = $dir . '/module.json';
        if (!file_exists($manifest)) continue;
        $m = json_decode(file_get_contents($manifest), true) ?: [];
        $m['__dir'] = $dir;
        $m['id'] = $m['id'] ?? $f;
        $list[] = $m;
    }
    return $list;
}

function find_module_dir($slug)
{
    $all = modules_list_filesystem();
    foreach ($all as $m) {
        if (($m['id'] ?? '') === $slug) return $m['__dir'];
    }
    return null;
}

function is_module_active($db_connect, $slug)
{
    $slug_esc = mysqli_real_escape_string($db_connect, $slug);
    $res = mysqli_query($db_connect, "SELECT active FROM modules WHERE slug='" . $slug_esc . "' LIMIT 1");
    if ($res && $row = mysqli_fetch_assoc($res)) {
        mysqli_free_result($res);
        return (bool)$row['active'];
    }
    return false;
}

function activate_module($db_connect, $slug)
{
    $slug_esc = mysqli_real_escape_string($db_connect, $slug);
    $now = date('Y-m-d H:i:s');
    // insert or update
    $exists = mysqli_query($db_connect, "SELECT id FROM modules WHERE slug='" . $slug_esc . "' LIMIT 1");
    if ($exists && mysqli_num_rows($exists) > 0) {
        mysqli_query($db_connect, "UPDATE modules SET active=1, installed_at='" . $now . "' WHERE slug='" . $slug_esc . "'");
        mysqli_free_result($exists);
    } else {
        // try to read manifest for name/version
        $dir = find_module_dir($slug);
        $manifest = [];
        if ($dir && file_exists($dir . '/module.json')) $manifest = json_decode(file_get_contents($dir . '/module.json'), true) ?: [];
        $name = mysqli_real_escape_string($db_connect, $manifest['name'] ?? $slug);
        $version = mysqli_real_escape_string($db_connect, $manifest['version'] ?? '');
        mysqli_query($db_connect, "INSERT INTO modules (slug,name,version,active,installed_at) VALUES ('" . $slug_esc . "','" . $name . "','" . $version . "',1,'" . $now . "')");
    }

    // if module provides defaults, set them
    $dir = find_module_dir($slug);
    if ($dir) {
        $manifest = json_decode(file_get_contents($dir . '/module.json'), true) ?: [];
        $entry = $dir . '/' . ($manifest['entry'] ?? 'ModuleMain.php');
        if (file_exists($entry)) {
            include_once $entry;
            $fn = 'register_module_' . $slug;
            if (function_exists($fn)) {
                $reg = $fn();
                if (!empty($reg['settings']) && is_array($reg['settings'])) {
                    foreach ($reg['settings'] as $section) {
                        if (empty($section['fields']) || !is_array($section['fields'])) continue;
                        foreach ($section['fields'] as $field) {
                            $name = $field['name'] ?? null;
                            $default = $field['default'] ?? null;
                            if ($name !== null) {
                                // insert if not exists
                                $slug_e = mysqli_real_escape_string($db_connect, $slug);
                                $name_e = mysqli_real_escape_string($db_connect, $name);
                                $res = mysqli_query($db_connect, "SELECT id FROM module_settings WHERE module_slug='" . $slug_e . "' AND name='" . $name_e . "' LIMIT 1");
                                if ($res && mysqli_num_rows($res) == 0) {
                                    $value_e = mysqli_real_escape_string($db_connect, (string)$default);
                                    mysqli_query($db_connect, "INSERT INTO module_settings (module_slug,name,value) VALUES ('" . $slug_e . "','" . $name_e . "','" . $value_e . "')");
                                }
                                if ($res) mysqli_free_result($res);
                            }
                        }
                    }
                }
            }
        }
    }
}

function deactivate_module($db_connect, $slug)
{
    $slug_esc = mysqli_real_escape_string($db_connect, $slug);
    mysqli_query($db_connect, "UPDATE modules SET active=0 WHERE slug='" . $slug_esc . "'");
}

function get_module_setting($db_connect, $slug, $name, $default = null)
{
    $slug_e = mysqli_real_escape_string($db_connect, $slug);
    $name_e = mysqli_real_escape_string($db_connect, $name);
    $res = mysqli_query($db_connect, "SELECT value FROM module_settings WHERE module_slug='" . $slug_e . "' AND name='" . $name_e . "' LIMIT 1");
    if ($res && $row = mysqli_fetch_assoc($res)) {
        mysqli_free_result($res);
        return $row['value'];
    }
    return $default;
}

function set_module_setting($db_connect, $slug, $name, $value)
{
    $slug_e = mysqli_real_escape_string($db_connect, $slug);
    $name_e = mysqli_real_escape_string($db_connect, $name);
    $value_e = mysqli_real_escape_string($db_connect, $value);
    $res = mysqli_query($db_connect, "SELECT id FROM module_settings WHERE module_slug='" . $slug_e . "' AND name='" . $name_e . "' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        mysqli_query($db_connect, "UPDATE module_settings SET value='" . $value_e . "' WHERE module_slug='" . $slug_e . "' AND name='" . $name_e . "'");
        mysqli_free_result($res);
    } else {
        mysqli_query($db_connect, "INSERT INTO module_settings (module_slug,name,value) VALUES ('" . $slug_e . "','" . $name_e . "','" . $value_e . "')");
    }
}

function modules_get_registration($slug)
{
    global $MODULE_REGISTRATIONS;
    return $MODULE_REGISTRATIONS[$slug] ?? null;
}

?>
