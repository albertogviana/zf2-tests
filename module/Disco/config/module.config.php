<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Disco\Controller\Disco' => 'Disco\Controller\DiscoController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'disco' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/disco[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Disco\Controller\Disco',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'disco' => __DIR__ . '/../view',
        ),
    ),
);
