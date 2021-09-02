<?php

/*
 * Copyright (c) 2020. WOSHIZHAZHA120 & GDCN Team
 */

return [
    'perPage' => 10,
    'customSongIdOffset' => 5000000,
    'weeklyIdOffset' => 100000,
    'defaultPermissionGroupID' => 1,
    'creator_points_count' => [
        'rated' => 1,
        'featured' => 2,
        'epic' => 4
    ],
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
    'spamWords' => [
        'fuck', // comment will mark as spam when content contained 'fuck'
        'shit'
    ],
    'feature' => [
        'command' => [
            'account_comment' => [
                'enabled' => true
            ],
            'level_comment' => [
                'enabled' => true
            ]
        ],
        'auto_rate' => [
            'rate' => [
                'enabled' => true,
                'least_suggest' => 10
            ],
            'demon' => [
                'enabled' => true,
                'least_suggest' => 10
            ]
        ]
    ]
];
