<?php

namespace Izvjestaji;

return array(
    'controllers' => array(
        'invokables' => array(
            'Izvjestaji\Controller\Index' => 'Izvjestaji\Controller\IndexController',
        	'Izvjestaji\Controller\Agencija' => 'Izvjestaji\Controller\AgencijaController',
        	'Izvjestaji\Controller\Schedule' => 'Izvjestaji\Controller\ScheduleController',
        	'Izvjestaji\Controller\Knjigovodstvo' => 'Izvjestaji\Controller\KnjigovodstvoController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'izvjestaji' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/izvjestaji',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Izvjestaji\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
		
        'template_path_stack' => array(
            'Izvjestaji' => __DIR__ . '/../view',
        ),
    ),
);
