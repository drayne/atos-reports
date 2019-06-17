<?php

namespace Izvjestaji\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Doctrine\DBAL\Event\Listeners\OracleSessionInit;
use Doctrine\DBAL\DriverManager;
use Application\Entity\Anlanl;

//forme
use Zend\Form\Element;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder as DoctrineAnnotationBuilder;
use Application\Entity\Polao3;
use Application\Controller\Plugin\LayoutScriptPlugin;
/**
 * KnjigovodstvoController
 *
 * @author
 *
 * @version
 *
 */
class KnjigovodstvoController extends AbstractActionController {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated KnjigovodstvoController::indexAction() default action
		return new ViewModel ();
	}
	
	public function potrazivanjaAction()
	{
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_crawler');
		$doctrineEventManager = $entityManager->getEventManager();
		$doctrineEventManager->addEventSubscriber(new OracleSessionInit(array(
				'NLS_DATE_FORMAT' 		=> 'YYYY-MM-DD HH24:MI:SS',
				'NLS_TIMESTAMP_FORMAT' 	=> 'YYYY-MM-DD HH24:MI:SS'
		)));
		return new ViewModel();
	}

	public function pregledPremijeAction()
    {
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_crawler');
        $doctrineEventManager = $entityManager->getEventManager();
        $doctrineEventManager->addEventSubscriber(new OracleSessionInit(array(
            'NLS_DATE_FORMAT' 		=> 'YYYY-MM-DD HH24:MI:SS',
            'NLS_TIMESTAMP_FORMAT' 	=> 'YYYY-MM-DD HH24:MI:SS'
        )));

        $anlanl = new Anlanl();
        $form = $this->getForm($anlanl, $entityManager, 'Izvještaj');

        $request = $this->getRequest();
        if ($request->isPost()){

            $datum_od = new \DateTime($request->getPost('datdokod'));
            $datum_od_excel = $datum_od->format('d.m.y');
            $datum_od = "'" . $datum_od->format('Y-m-d') . "'";
            //$datum_od = $datum_od->format('d.m.Y');

            $datum_do = new \DateTime($request->getPost('datdokdo'));
            $datum_do_excel = $datum_do->format('d.m.y');
            $datum_do = "'" . $datum_do->format('Y-m-d') . "'";
            //$datum_do = $datum_do->format('d.m.Y');

//            $dql = "SELECT a, b FROM Application\Entity\Anlanl a LEFT JOIN a.rj b WHERE a.datdok BETWEEN :datum_od AND :datum_do ";

            $sql = strtoupper(
            "
            
             select  FILIJALA.FIL_SIFRA filijala, ORGJED.naziv orgjed, zastup.NAZIV, 

            sum(
            case when anlanl.konto = 611200 then  nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto = 611201 then  nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto = 611202 then  nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto = 611203 then  nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto = 611204 then  nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto = 611205 then  nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto = 611300 then  nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto = 611301 then  nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto = 611302 then  nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end 
            ) ao,

            sum(
            case when anlanl.konto between 610010 and 610012 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end + 
            case when anlanl.konto between 610021 and 610023 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 610030 and 610032 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 610101 and 610103 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 611010 and 611012 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 611100 and 611102 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 612001 and 612003 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 612100 and 612102 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 612200 and 612202 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 612300 and 612302 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 612400 and 612402 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 612500 and 612502 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 612600 and 612602 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 612700 and 612702 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 612800 and 612802 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end +
            case when anlanl.konto between 612900 and 612902 then nvl(anlanl.dev_potrazuje, 0) - nvl(anlanl.dev_duguje, 0) else 0 end 
                                                                  
            ) ostali                                              

            from anlanl, ZASTUP, orgjed, filijala
            where anlanl.datdok between $datum_od and $datum_do
            and anlanl.jmbg = zastup.ZAS_SIFRA
            and ORGJED.OJ_SIFRA = ANLANL.RJ
            and FILIJALA.FIL_SIFRA = ORGJED.FILIJALA
            and anlanl.anl_vsdok not  in 309
            and filijala.fil_sifra != 9
            group by  zastup.naziv, FILIJALA.FIL_SIFRA, ORGJED.naziv
            order by filijala.FIL_SIFRA asc, ORGJED.naziv asc, zastup.naziv asc                     
                    ");
            $conn = $entityManager->getConnection();
            $stmt = $conn->query($sql);

            $anlanl= $stmt->fetchAll();

            $this->pregledPremijekreirajExcelAction($anlanl, $datum_od_excel, $datum_do_excel);
            /* dql
            $query = $entityManager->createQuery($dql);
            $query->setParameter('datum_od', $datum_od);
            $query->setParameter('datum_do', $datum_do);
            $query->useResultCache(false);

            $anlanl = $query->getResult();
            $this->pregledPremijekreirajExcelAction($anlanl, $datum_od_excel, $datum_do_excel);
            */
//            return new ViewModel(array('anlanl' => $anlanl));

        } else{

            $this->layout('layout/layoutForme.phtml');
            return new ViewModel(array('form' => $form));
        }


//        return new ViewModel();
    }


    public function getForm($polisa, $entityManager, $action) {
        $builder = new DoctrineAnnotationBuilder($entityManager);
        $form = $builder->createForm($polisa);

        //!!!!!! Start !!!!! Added to make the association tables work with select
        foreach ($form->getElements() as $element) {
            if (method_exists($element, 'getProxy')) {
                $proxy = $element->getProxy();
                if (method_exists($proxy, 'setObjectManager')) {
                    $proxy->setObjectManager($entityManager);
                }
            }
        }

//        $form->remove('zampol');
//        $form->remove('jmbg');
//        $form->remove('nazivugov');
//        $form->remove('datpoc');
//        $form->remove('datist');
//        $form->remove('pol_brpol');

        $form->setHydrator(new DoctrineHydrator($entityManager, 'Application\Entity\Anlanl'));

        $send = new Element('send');
        $send->setValue($action); // submit
        $send->setAttributes(array(
            'type' => 'submit'
        ));
        $form->add($send);

        return $form;
    }

    public function pregledPremijekreirajExcelAction($anlanl, $datum_od, $datum_do)
    {
        define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

        date_default_timezone_set('Europe/London');

//        print_r($anlanl); exit;

        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load("public/excel-template/pregled_premije_po_zastupnicima.xls");


        $objPHPExcel->getProperties()
            ->setCreator("Atos osiguranje")
            ->setLastModifiedBy("Atos osiguranje")
            ->setTitle("Pregled zakljucenih polisa")
            ->setSubject("Pregled zakljucenih polisa")
            ->setDescription("Pregled zakljucenih polisa")
            ->setKeywords("Pregled zakljucenih polisa")
            ->setCategory("Intranet izvjestaji");


        $objPHPExcel->getActiveSheet()->setCellValue('D2', \PHPExcel_Shared_Date::PHPToExcel(time()));
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('A2', 'Datum od: ' . $datum_od, \PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('B2', 'Datum do: ' . $datum_do, \PHPExcel_Cell_DataType::TYPE_STRING);


        $row = $baseRow = 5;
        $prethodni_red_filijala = 1; //pomocna za poredjenje kada se pravi novi sheet, inicijalna filijala
        $prethodni_red_poslovnica = '';
        $sheet = 0;
        foreach($anlanl as $r => $anlanl) {

            if($anlanl['FILIJALA'] != $prethodni_red_filijala) //novi sheet
            {
                $sheet = $sheet+1;
                $objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1); //prvo na postojecem obrisemo prazan red
                $objPHPExcel->setActiveSheetIndex($sheet); //idemo na novi sheet
                $row = $baseRow;
                $prethodni_red_poslovnica = '';
            }

            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);

            //jedan red samo sa nazivom poslovnice
            if($anlanl['ORGJED'] != $prethodni_red_poslovnica)
            {
                $style = array('font' => array('size' => 12,'bold' => true));
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->applyFromArray($style);

                $objPHPExcel->getActiveSheet()
                    ->setCellValue('A'.$row, $anlanl['ORGJED']);

                $row++;
                $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);

            }
            $style = array('font' => array('size' => 11,'bold' => false));
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->applyFromArray($style);

            $objPHPExcel->getActiveSheet()
               // ->setCellValue('A'.$row, $anlanl['ORGJED'])
                ->setCellValue('A'.$row, $anlanl['NAZIV'])
                ->setCellValue('B'.$row, $anlanl['AO'])
                ->setCellValue('C'.$row, $anlanl['OSTALI'])
                ->setCellValue('D'.$row, $anlanl['AO']+$anlanl['OSTALI']);

            $prethodni_red_filijala = $anlanl['FILIJALA']; //za sledeci red
            $prethodni_red_poslovnica = $anlanl['ORGJED'];
            $row++;

            //->setCellValue('Z'.$row, '=C'.$row.'*D'.$row);
        }
// 		foreach($data as $r => $dataRow) {
// 			$row = $baseRow + $r;
// 			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);

// 			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r+1)
// 			->setCellValue('B'.$row, $dataRow['title'])
// 			->setCellValue('C'.$row, $dataRow['price'])
// 			->setCellValue('D'.$row, $dataRow['quantity'])
// 			->setCellValue('E'.$row, '=C'.$row.'*D'.$row);
// 		}
        $objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);

        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment;filename="pregled_premije.xlsx"');
        header('Cache-Control: max-age=0');

        header('Cache-Control: max-age=1');


        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $objWriter->save('php://output');
        exit;
    }
}