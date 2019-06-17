<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
/*
 * koristi se konekcija iz doctrine.local.php posto se za komunikaciju sa bazom koristi modul doctrine orm
 */
return array(
    'db' => array(
        'driver' => 'OCI8',//ne gleda ovde konekciju nego u doctrine.local.php
/*        'connection_string' => '(DESCRIPTION= (ADDRESS_LIST= (ADDRESS=  (PROTOCOL=TCP) 
                                                                        (HOST=192.168.1.251)(PORT=1521)))
                                               (CONNECT_DATA=(SID=bobar)))',
	*/										   
		'connection_string' => '(DESCRIPTION= (ADDRESS_LIST= (ADDRESS=  (PROTOCOL=TCP) 
                                                                        (HOST=192.168.101.243)(PORT=1521)))
                                               (CONNECT_DATA=(SID=atos)))',
											   

    	//(HOST=192.168.101.253)(PORT=1521)))
        // 'hostname' => '192.168.101.203',
        // 'port' => '1521',
        //'database'=> 'bobar',
        
        'character_set' => 'AL32UTF8'
    ),
    
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
        )
    )
);
