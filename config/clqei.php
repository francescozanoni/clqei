<?php

return [

    'students' => [

        'identification_number' => [
            'pattern' => env('CLQEI_STUDENT_IDENTIFICATION_NUMBER_PATTERN', '^\d{8}$'),
        ],

        'email' => [
            'pattern' => env('CLQEI_STUDENT_EMAIL_PATTERN', '^[\w\.\-]+@example\.com$'),
        ],

    ],

    'educational_institution' => [

        'url' => env('CLQEI_EDUCATIONAL_INSTITUTION_URL', ''),

    ],

    'stages' => [

        'max_weeks' => 18,

    ],
    
    'viewers_can_view_compilation_details' => env('CLQEI_VIEWERS_CAN_VIEW_COMPILATION_DETAILS', true),

];
