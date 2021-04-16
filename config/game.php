<?php

/*
 * Copyright (c) 2020. WOSHIZHAZHA120 & GDCN Team
 */

return [
    'perPage' => 10,
    'customSongIdOffset' => 5000000,
    'weeklyIdOffset' => 100000,
    'reward' => [
        'small' => [
            'orbs' => [
                'min' => 200,
                'max' => 400
            ],
            'diamonds' => [
                'min' => 2,
                'max' => 10
            ],
            'shards' => [
                'min' => 1,
                'max' => 6
            ],
            'keys' => [
                'min' => 1,
                'max' => 6
            ],
            'wait' => 3600
        ],
        'big' => [
            'orbs' => [
                'min' => 2000,
                'max' => 4000
            ],
            'diamonds' => [
                'min' => 20,
                'max' => 100
            ],
            'shards' => [
                'min' => 1,
                'max' => 6
            ],
            'keys' => [
                'min' => 1,
                'max' => 6
            ],
            'wait' => 14400
        ]
    ],
    'challenge' => [
        'orbs' => [
            'collect' => [
                'min' => 200,
                'max' => 2000
            ],
            'names' => [
                'orbs! orbs! orbs!'
            ],
            'proportion' => [100, 4] // this mean every 100 orbs give 4 diamonds
        ],
        'coins' => [
            'collect' => [
                'min' => 2,
                'max' => 10
            ],
            'names' => [
                'coins! coins! coins!'
            ],
            'proportion' => [2, 4]
        ],
        'stars' => [
            'collect' => [
                'min' => 3,
                'max' => 10
            ],
            'names' => [
                'stars! stars! stars!'
            ],
            'proportion' => [2, 4]
        ],
        'mode' => 'round'
    ],
    'storage' => [
        'saveData' => [
            [
                'disk' => 'oss',
                'path' => 'gdcn/saveData',
                'async' => true // this will run as dispatch
            ]
        ],
        'levels' => [
            [
                'disk' => 'oss',
                'path' => 'gdcn/levels',
                'async' => true
            ]
        ]
    ],
    'spamWords' => [
        'fuck', // comment will mark as spam when content contained 'fuck'
        'shit'
    ],
    'default_permissions' => [
        'command-song-level'
    ],
    'feature' => [
        'command' => [
            'account_comment' => [
                'enabled' => true,
                'prefix' => '!'
            ],
            'level_comment' => [
                'enabled' => true,
                'prefix' => '!'
            ]
        ],
        'auto_rate' => [
            'rate' => [
                'enabled' => true,
                'mod_only' => false,
                'least_suggest' => 10
            ],
            'demon' => [
                'enabled' => true,
                'mod_only' => false,
                'least_suggest' => 10
            ],
            'suggest' => [
                'enabled' => true,
                'least_suggest' => 10
            ]
        ]
    ]
];
