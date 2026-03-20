<?php

return [


    'driver' => env('SCOUT_DRIVER', 'collection'),


    'prefix' => env('SCOUT_PREFIX', ''),



    'queue' => env('SCOUT_QUEUE', false),



    'after_commit' => false,



    'chunk' => [
        'searchable' => 500,
        'unsearchable' => 500,
    ],



    'soft_delete' => false,



    'identify' => env('SCOUT_IDENTIFY', false),



    'algolia' => [
        'id' => env('ALGOLIA_APP_ID', ''),
        'secret' => env('ALGOLIA_SECRET', ''),
        'index-settings' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Meilisearch Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Meilisearch settings. Meilisearch is an open
    | source search engine with minimal configuration. Below, you can state
    | the host and key information for your own Meilisearch installation.
    |
    | See: https://www.meilisearch.com/docs/learn/configuration/instance_options#all-instance-options
    |
    */

    'meilisearch' => [
        'host' => env('MEILISEARCH_HOST', 'http://localhost:7700'),
        'key' => env('MEILISEARCH_KEY', null),

      
        'index-settings' => [
            \App\Models\News::class => [
   
                'searchableAttributes' => [
                    'title',       // Ưu tiên 1: Trùng tiêu đề là top 1
                    'short',       // Ưu tiên 2: Trùng mô tả ngắn
                    'description', // Ưu tiên 3: Nằm tuốt trong nội dung thì xếp sau
                ],
                // (Tùy chọn) Luật xếp hạng mặc định của Meilisearch, cứ để nguyên
                'filterableAttributes' => [
                    'cat_id',      
                ],
                'rankingRules' => [
                    'words',      // Ưu tiên bài chứa nhiều từ khóa nhất
                    'typo',       // Ưu tiên bài đúng chính tả hơn bài sai chính tả
                    'proximity',  // Khoảng cách các từ (VD: gõ "Chính Nhân" thì bài có chữ "Chính Nhân" đứng cạnh nhau xếp trên bài có chữ "Chính" và "Nhân" nằm cách xa nhau)
                    'attribute',  // Ưu tiên theo thứ tự searchableAttributes ở trên
                    'sort',
                    'exactness',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Typesense Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Typesense settings. Typesense is an open
    | source search engine using minimal configuration. Below, you will
    | state the host, key, and schema configuration for the instance.
    |
    */

    'typesense' => [
        'client-settings' => [
            'api_key' => env('TYPESENSE_API_KEY', 'xyz'),
            'nodes' => [
                [
                    'host' => env('TYPESENSE_HOST', 'localhost'),
                    'port' => env('TYPESENSE_PORT', '8108'),
                    'path' => env('TYPESENSE_PATH', ''),
                    'protocol' => env('TYPESENSE_PROTOCOL', 'http'),
                ],
            ],
            'nearest_node' => [
                'host' => env('TYPESENSE_HOST', 'localhost'),
                'port' => env('TYPESENSE_PORT', '8108'),
                'path' => env('TYPESENSE_PATH', ''),
                'protocol' => env('TYPESENSE_PROTOCOL', 'http'),
            ],
            'connection_timeout_seconds' => env('TYPESENSE_CONNECTION_TIMEOUT_SECONDS', 2),
            'healthcheck_interval_seconds' => env('TYPESENSE_HEALTHCHECK_INTERVAL_SECONDS', 30),
            'num_retries' => env('TYPESENSE_NUM_RETRIES', 3),
            'retry_interval_seconds' => env('TYPESENSE_RETRY_INTERVAL_SECONDS', 1),
        ],
        // 'max_total_results' => env('TYPESENSE_MAX_TOTAL_RESULTS', 1000),
        'model-settings' => [
            // User::class => [
            //     'collection-schema' => [
            //         'fields' => [
            //             [
            //                 'name' => 'id',
            //                 'type' => 'string',
            //             ],
            //             [
            //                 'name' => 'name',
            //                 'type' => 'string',
            //             ],
            //             [
            //                 'name' => 'created_at',
            //                 'type' => 'int64',
            //             ],
            //         ],
            //         'default_sorting_field' => 'created_at',
            //     ],
            //     'search-parameters' => [
            //         'query_by' => 'name'
            //     ],
            // ],
        ],
        'import_action' => env('TYPESENSE_IMPORT_ACTION', 'upsert'),
    ],

];
