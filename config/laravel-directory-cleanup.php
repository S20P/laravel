<?php

return [


    'directories' => [

        /**
         * Here you can specify which directories need to be cleanup. All files older than
         * the specified amount of minutes will be deleted.
         */

        /*
        'path/to/a/directory' => [
            'deleteAllOlderThanMinutes' => 60 * 24
        ],
        */
        /*
        'uploads/' => [
            'deleteAllOlderThanMinutes' => 60 * 24
        ],
        */
         'public/uploads/' => [
             'deleteAllOlderThanMinutes' => 60 * 60 * 24 * 1
         ],


    ],
];
