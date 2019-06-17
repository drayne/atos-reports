<?php
/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 */

/*ORM DEFAULT 2 koriste automatski izvjestaji*/

return array (
		'doctrine' => array (
				'connection' => array (
						'orm_default' => array (
								'driverClass' => 'Doctrine\DBAL\Driver\OCI8\Driver',
								'params' => array (
										//'host' => '192.168.101.253',
										//'host' => '192.168.1.251',
										'host' => '192.168.101.243',
										'port' => '1521',
										//'servicename' => 'bobar',
										'servicename' => 'atos',
                    
                    /*'connection_string' => '(DESCRIPTION= (ADDRESS_LIST= (ADDRESS=  (PROTOCOL=TCP) 
                                                                        (HOST=192.168.101.203)(PORT=1521)))
                                               (CONNECT_DATA=(SID=bobar)))',*/
        // 'hostname' => '192.168.101.203',
        // 'port' => '1521',
        // 'database'=> 'bobar',
        
                    					'charset' => 'AL32UTF8',
										'user' => 'bobar',
										'password' => 'bobar' 
										//'password' => 'Bobar' 
								)
								// konekcija za tep
								// 'user' => 'tep',
								// 'password' => 'tep',
								
								 
						) ,
						'orm_default2' => array (
								'driverClass' => 'Doctrine\DBAL\Driver\OCI8\Driver',
								'params' => array (
										'host' => '192.168.101.243',
//										'host' => '192.168.1.251',
										'port' => '1521',
										'servicename' => 'atos',
										'dbname'=>'bobar',
						
										/*'connection_string' => '(DESCRIPTION= (ADDRESS_LIST= (ADDRESS=  (PROTOCOL=TCP)
										 (HOST=192.168.101.203)(PORT=1521)))
												(CONNECT_DATA=(SID=bobar)))',*/
						// 'hostname' => '192.168.101.203',
						// 'port' => '1521',
						// 'database'=> 'bobar',
						
						'charset' => 'AL32UTF8',
										'user' => 'bobar',
						'password' => 'bobar'
								)
						// konekcija za tep
										// 'user' => 'tep',
						// 'password' => 'tep',
						
											
								)						
				)
				,
				'configuration' => array (
						'orm_crawler' => array (
								'metadata_cache' => 'array',
								'query_cache' => 'array',
								'result_cache' => 'array',
								'hydration_cache' => 'array',
								'driver' => 'orm_crawler',
								'generate_proxies' => true,
								'proxy_dir' => 'data/DoctrineORMModule/Proxy',
								'proxy_namespace' => 'DoctrineORMModule\Proxy',
								'filters' => array () 
						)
						 
				),
				'entitymanager' => array (
						'orm_default' => array (
								'connection' => 'orm_default',
								'configuration' => 'orm_default' 
						),
						// This is the alternative config
						'orm_crawler' => array (
								'connection' => 'orm_default2',
								'configuration' => 'orm_crawler'
						) 
				) // druga konekcija sa istim parametrima

				 
		)
		 
);