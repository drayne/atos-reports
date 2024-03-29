<?php

return array(
     'navigation' => array(
         'default' => array(
             array(
                 'label' => 'Home',
                 'route' => 'home',
             ),/*
			 array(
                 'label' => 'Login',
                 'route' => 'login', 
				 'controller' => 'Index',
				 'action'     => 'login',
				 'resource'	  => 'CsnUser\Controller\Index',
				 'privilege'  => 'login',
             ),
             array(
                 'label' => 'Registration',
                 'route' => 'registration', 
				 'controller' => 'Registration',
				 'action'     => 'index',
				 'resource'	  => 'CsnUser\Controller\Registration',
				 'privilege'  => 'index',
				 'title'	  => 'Registration Form'
             ),
             array(
                 'label' => 'Forgotten Password',
                 'route' => 'forgotten-password', 
				 'controller' => 'Registration',
				 'action'     => 'forgotten-password',
				 'resource'	  => 'CsnUser\Controller\Registration',
				 'privilege'  => 'forgotten-password'
             ),
             array(
                 'label' => 'Change Password',
                 'route' => 'changePassword', 
				 'controller' => 'Registration',
				 'action'     => 'change-password',
				 'resource'	  => 'CsnUser\Controller\Registration',
				 'privilege'  => 'changePassword'
             ),
             array(
                 'label' => 'Logout',
                 'route' => 'logout', 
				 'controller' => 'Index',
				 'action'     => 'logout',
				 'resource'	  => 'CsnUser\Controller\Index',
				 'privilege'  => 'logout'
             ),
			array(
				'label' => 'Zend',
				'uri'   => 'http://framework.zend.com/',
				'resource' => 'Zend',
				'privilege'	=>	'uri'
			),*/
		),
     		'izvjestaji' => array(
     		
     				'page-1'  =>  array(
     						'label'		=> 'Pregled zaključenih polisa',
     						'route'		=> 'izvjestaji/default',
     						'controller' => 'agencija',
     						'action' =>'zakljucene-polise',
     						//'params'	=>	array('action' => 'add'),
     						'resource'	=> 'Izvjestaji\Controller\Agencija',
     						'privilege'	=>	'zakljucene-polise',
     				),
      				'page-2' =>   array(
      						'label'		=> 'Pregled premije po zastupnicima',
      						'route'		=> 'izvjestaji/default',
      						'controller' => 'knjigovodstvo',
      						'action' =>'pregled-premije',
      						//'params'	=>	array('action' => 'add'),
      						'resource'	=> 'Izvjestaji\Controller\Knjigovodstvo',
      						'privilege'	=>	'member',
      				),
     		
     		),
     ),
     'service_manager' => array(
         'factories' => array(
             'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
         	 'izvjestaji' => 'CsnNavigation\Navigation\Service\IzvjestajiNavigationFactory',
         ),
     ),
);