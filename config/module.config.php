<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'SystemBootFix' => 'SystemBootFix\Controller\SystemBootFixController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'SystemBootFix' => __DIR__ . '/../view',
        ),
    ),
);
