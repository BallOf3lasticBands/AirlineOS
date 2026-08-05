<?php
function register_module_test_module()
{
    return [
        'settings' => [
            [
                'section' => 'appearance',
                'title' => 'Test Module',
                'fields' => [
                    [
                        'name' => 'text_color',
                        'type' => 'color',
                        'label' => 'Text color for Test module',
                        'default' => '#007bff'
                    ]
                ],
                'weight' => 10
            ]
        ]
    ];
}

function render_test_module_box()
{
    global $db_connect;
    // use the modules helper to get the setting (falls back to default)
    if (!function_exists('get_module_setting')) return '';
    $color = get_module_setting($db_connect, 'test_module', 'text_color', '#000000');
    $html = '<div style="padding:10px;margin-bottom:10px;border:1px solid #eee;">';
    $html .= '<strong style="color:' . htmlspecialchars($color) . ';">Test module</strong>';
    $html .= '</div>';
    return $html;
}
