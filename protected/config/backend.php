<?php

return CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'), array(
        'components' => array(

            'urlManager' => array(
                'urlFormat' => 'path',
                'showScriptName' => false,
                'rules' => array(
                    'backend' => 'site/login',
                    'backend/<_c>' => '<_c>',
                    'backend/<_c>/<_a>' => '<_c>/<_a>',

                    'backend/admin'=>'admin',
                    'backend/admin/<_c>'=>'admin/<_c>',
                    'backend/admin/<_c>/<_a>'=>'admin/<_c>/<_a>',
                ),
            ),
        )

    )
);
