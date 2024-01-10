<?php

return [
    'id' => 'blog-post-list',
    'name' => __('edulia.blog_list'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id' => 'blog_count',
            'type' => 'number',
            'value' => 4,
            'class' => '',
            'label_title' => __('edulia.how_many_blog')
        ],
        [
            'id' => 'blog_data_sorting',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.blog_sorting'),
            'label_desc' => __('edulia.which_order_blog'),
            'options' => [
                'asc' => 'Ascending',
                'desc' => 'Descending',
                'randomly' => 'Randomly',
            ],
            'default' => 'asc',
            'value' => 'asc',
        ],
        [
            'id' => 'blog_read_more_text',
            'type' => 'text',
            'value' => '+ Read More',
            'class' => '',
            'label_title' => __('edulia.read_more_button_text')
        ],
        [
            'id' => 'blog_search_placeholder',
            'type' => 'text',
            'value' => 'Search ...',
            'class' => '',
            'label_title' => __('edulia.search_placeholder')
        ],
        [
            'id' => 'blog_search',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.blog_search'),
            'label_desc' => __('edulia.blog_want_to_be_hide_or_show'),
            'options' => [
                '0' => 'Hide',
                '1' => 'Show',
            ],
            'default' => '1',
            'value' => '1',
        ]
    ]
];
