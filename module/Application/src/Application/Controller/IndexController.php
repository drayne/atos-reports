<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Polisa;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder as DoctrineAnnotationBuilder;


use DoctrineORMModule\Options\DBALConfiguration;
use Doctrine;
use Doctrine\DBAL\Event\Listeners\OracleSessionInit;
use Doctrine\DBAL\DriverManager;
use Application\Entity\Polao3;
use CsnUser\Entity\User;


class IndexController extends AbstractActionController
{
    public function kreiranjeAction() {
        //$entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                      
        $entityManager->getConfiguration()->setMetadataDriverImpl(
        		new \Doctrine\ORM\Mapping\Driver\DatabaseDriver(
        				$entityManager->getConnection()->getSchemaManager()
        		)
        );
        
        $platform = $entityManager->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');
        $platform->registerDoctrineTypeMapping('blob', 'string');
        $platform->registerDoctrineTypeMapping('set', 'string');
        
        $cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($entityManager);
        $metadata = $cmf->getAllMetadata();

        
        $cme = new \Doctrine\ORM\Tools\Export\ClassMetadataExporter();
        $exporter = $cme->getExporter('yml', '/Application/src/');
        $exporter->setMetadata($metadata);
        $exporter->export();
        
        /*
        $user = new \Application\Entity\User();
        $user->setName('Paul Underwood');

        $entityManager->persist($user);
        $entityManager->flush();

        echo $user->getId();

        return new ViewModel();*/
    }
    
    public function indexAction()
    {
    	
//     	$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	
//     	$doctrineEventManager = $entityManager->getEventManager();
    	

//     	$doctrineEventManager->addEventSubscriber(new OracleSessionInit(array(
//     			'NLS_DATE_FORMAT' => 'YYYY-MM-DD HH24:MI:SS',
//     			'NLS_TIMESTAMP_FORMAT' => 'YYYY-MM-DD HH24:MI:SS'
//     	)));
    	
    	
  
// 		//$dql = "SELECT a FROM Application\Entity\Polisa a WHERE a.vros=800 ORDER BY a.datdok DESC";
//     	$dql = "SELECT a FROM CsnUser\Entity\User a WHERE a.korisnik = 'vedran'";
// 		$query = $entityManager->createQuery($dql);
// 		$query->setMaxResults(30);
// 				$categories = $query->getResult();	
		
// 		$this->layout('layout/layoutIntranet.phtml');				
// 		return new ViewModel(array('zaposleni' => $categories));
//        echo file_get_contents('http://google.com');
		return new ViewModel();

    	//print_r(array_keys($entityManager->getMetadataFactory()->getMetadataFor('Application\Entity\Polisa')->reflFields));
    }
}
