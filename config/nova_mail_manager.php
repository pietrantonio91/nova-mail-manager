<?php

return [
    'table_name' => 'email_templates',
    'tinymce' => [
        'init' => [
            'content_css' => null,
            'skin_url' => null,
            'content_css_dark' => null,
            'skin_url_dark' => null,
            'menubar' => true,
            'autoresize_bottom_margin' => 40,
            'branding' => false,
            'image_caption' => true,
            'paste_as_text' => true,
            'autosave_interval' => '20s',
            'autosave_retention' => '30m',
            'browser_spellcheck' => true,
            'contextmenu' => true,
            'templates' => [
                [
                    'title' => 'Custom Button',
                    'description' => 'Custom button template',
                    'content' => '<div><a href="#" style="color: #FFFFFF; background-color: #087D68; display: inline-block; text-decoration: none; font-weight: bold; padding: 12px 16px; border-radius: 8px;">Custom Button</a></div>'
                ],
            ]
        ],
        'path_absolute' => '/',
        'use_lfm' => true,
        'lfm_url' => 'laravel-filemanager',
        'plugins' => [
            'advlist',
            'anchor',
            'autolink',
            'autosave',
            'fullscreen',
            'lists',
            'link',
            'image',
            'media',
            'table',
            'code',
            'wordcount',
            'autoresize',
            'template'
        ],
        'toolbar' => [
            'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image | bullist numlist outdent indent | link code template table',
        ],
        'apiKey' => env('TINYMCE_API_KEY', ''),
    ]
];
