<?php
// TEMPORARY QA sandbox registry (deleted by tests/e2e/qa-core.mjs)
return [
    '_group' => ['label' => 'ZZ QA', 'icon' => 'bug_report', 'order' => 999],
    'sandbox' => [
        'label' => 'QA sandbox',
        'url' => '/admin/zz-qa-preview',
        'sections' => [
            'main' => ['label' => 'Main', 'fields' => [
                'text'   => ['type' => 'text', 'label' => 'Text', 'default' => 'Hello'],
                'area'   => ['type' => 'textarea', 'label' => 'Area', 'default' => "line1\nline2"],
                'list'   => ['type' => 'list', 'label' => 'List', 'default' => ['a', 'b']],
                'rich'   => ['type' => 'richtext', 'label' => 'Rich', 'default' => '<p>Rich <strong>default</strong></p>'],
                'html'   => ['type' => 'html', 'label' => 'Html', 'default' => '<p>Html default</p>'],
                'url'    => ['type' => 'url', 'label' => 'Url', 'default' => 'https://example.org/'],
                'img'    => ['type' => 'image', 'label' => 'Img', 'default' => ''],
                'file'   => ['type' => 'file', 'label' => 'File', 'default' => ''],
                'num'    => ['type' => 'number', 'label' => 'Num', 'default' => 5],
                'date'   => ['type' => 'date', 'label' => 'Date', 'default' => '2026-01-01'],
                'bool'   => ['type' => 'boolean', 'label' => 'Bool', 'default' => true],
                'sel'    => ['type' => 'select', 'label' => 'Sel', 'options' => ['x' => 'X', 'y' => 'Y'], 'default' => 'x'],
                'icon'   => ['type' => 'icon', 'label' => 'Icon', 'default' => 'shield'],
            ]],
            'rep' => ['label' => 'Repeaters', 'toggle' => true, 'fields' => [
                'rows' => ['type' => 'repeater', 'label' => 'Rows', 'item_label' => 'title', 'add_label' => 'Add row',
                    'default' => [
                        ['title' => 'R1', 'body' => '<p>b1</p>', 'img' => '', 'kids' => [['name' => 'k1'], ['name' => 'k2']]],
                        ['title' => 'R2', 'body' => '<p>b2</p>', 'img' => '', 'kids' => []],
                    ],
                    'fields' => [
                        'title' => ['type' => 'text', 'label' => 'Title'],
                        'body'  => ['type' => 'richtext', 'label' => 'Body'],
                        'img'   => ['type' => 'image', 'label' => 'Img'],
                        'kids'  => ['type' => 'repeater', 'label' => 'Kids', 'item_label' => 'name', 'fields' => [
                            'name' => ['type' => 'text', 'label' => 'Name'],
                        ]],
                    ]],
            ]],
        ],
    ],
];
