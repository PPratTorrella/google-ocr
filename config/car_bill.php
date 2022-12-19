<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Some config for the layout of the car bill label fields and their
    | corresponding target areas
    |--------------------------------------------------------------------------
    */

    'id' => [
        'target_area_params' => [
            'horMultiplier' => 4,
            'vertMultiplier' => 6,
            'pushLeft' => 0.2,
            'pushDown' => 0.2,
        ],
        'label_detection' => ['identificación', 'Número de identificación'],
        'cropping' => 'something',
        'min_chars' => 4,
    ],

];
