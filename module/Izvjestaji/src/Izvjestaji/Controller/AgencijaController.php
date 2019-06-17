<?php

namespace Izvjestaji\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Doctrine\DBAL\Event\Listeners\OracleSessionInit;
use Doctrine\DBAL\DriverManager;
use Application\Entity\Polisa;

//forme
use Zend\Form\Element;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder as DoctrineAnnotationBuilder;
use Application\Entity\Polao3;
use Application\Controller\Plugin\LayoutScriptPlugin;

/**
 * AgencijaController
 *
 * @author
 *
 * @version
 *
 */
class AgencijaController extends AbstractActionController {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {

		return new ViewModel ();
	}

    /**
     * @return ViewModel
     */
    public function zakljucenePoliseAction()
	{

		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_crawler');
		$doctrineEventManager = $entityManager->getEventManager();
		$doctrineEventManager->addEventSubscriber(new OracleSessionInit(array(
				'NLS_DATE_FORMAT' 		=> 'YYYY-MM-DD HH24:MI:SS',
				'NLS_TIMESTAMP_FORMAT' 	=> 'YYYY-MM-DD HH24:MI:SS'
		)));

		$polisa = new Polisa();
		$form = $this->getForm($polisa, $entityManager, 'Izvještaj');

		$polao3 = new Polao3();
		$form3 = $this->getForm($polao3, $entityManager, 'Izvještaj');

		$request = $this->getRequest();
		if ($request->isPost()) {

			$datum_od = new \DateTime($request->getPost('datdokod'));
			$datum_od_excel = $datum_od->format('d.m.y');
			$datum_od = $datum_od->format('Y-M-d');
			//$datum_od = $datum_od->format('d.m.Y');

			$datum_do = new \DateTime($request->getPost('datdokdo'));
			$datum_do_excel = $datum_do->format('d.m.y');
			$datum_do = $datum_do->format('Y-M-d');
			//$datum_do = $datum_do->format('d.m.Y');

			if ($request->getPost('zonr')){
				$zona_rizika = $request->getPost('zonr');
			}


			$dql = "SELECT a, b FROM Application\Entity\Polisa a LEFT JOIN a.polao3 b WHERE a.vros=800 AND a.datdok BETWEEN :datum_od AND :datum_do ";
			if (isset($zona_rizika)) { $dql = $dql . " AND b.zonr = :zona_rizika"; }

			/*PRIVREMENO ZA BOSNA EX AGENCIJU */
			//$dql = $dql . " AND a.mbrzastup in (70117) ";
			/*PRIVREMENO ZA BOSNA EX AGENCIJU*/

			$dql = $dql . " ORDER BY a.datdok ASC";
			$query = $entityManager->createQuery($dql);
			$query->setParameter('datum_od', $datum_od);
			$query->setParameter('datum_do', $datum_do);
			$query->useResultCache(false);
			if (isset($zona_rizika)) {$query->setParameter('zona_rizika', $zona_rizika); }
			//echo $query->getSql();
			//$query->setMaxResults(30);
			$polise = $query->getResult();
			$this->zakljucenePolisekreirajExcelAction($polise, $datum_od_excel, $datum_do_excel, $zona_rizika);
			//return new ViewModel(array('polisa' => $polise));
		}
		else{
			$this->layout('layout/layoutForme.phtml');
			//$this->layout()->setVariable('inlineSkriptContent', $this->layoutScriptPlugin()->procitajScript('zakljucenePolise'));

			return new ViewModel(array('form' => $form, 'form3' => $form3));
		}
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

 		$form->remove('zampol');
 		$form->remove('jmbg');
 		$form->remove('nazivugov');
 		$form->remove('datpoc');
 		$form->remove('datist');
 		$form->remove('pol_brpol');

		$form->setHydrator(new DoctrineHydrator($entityManager, 'Application\Entity\Polisa'));

		$send = new Element('send');
		$send->setValue($action); // submit
		$send->setAttributes(array(
				'type' => 'submit'
		));
		$form->add($send);

		return $form;
	}

	public function zakljucenePolisekreirajExcelAction($polise, $datum_od, $datum_do, $zona_rizika = null)
	{
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

		date_default_timezone_set('Europe/London');

		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("public/excel-template/pregled_zakljucenih_polisa_template.xls");


		$objPHPExcel->getProperties()
		->setCreator("Atos osiguranje")
		->setLastModifiedBy("Atos osiguranje")
		->setTitle("Pregled zakljucenih polisa")
		->setSubject("Pregled zakljucenih polisa")
		->setDescription("Pregled zakljucenih polisa")
		->setKeywords("Pregled zakljucenih polisa")
		->setCategory("Intranet izvjestaji");


		$objPHPExcel->getActiveSheet()->setCellValue('G1', \PHPExcel_Shared_Date::PHPToExcel(time()));
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('A2', 'Datum od: ' . $datum_od, \PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('B2', 'Datum do: ' . $datum_do, \PHPExcel_Cell_DataType::TYPE_STRING);

		if ($zona_rizika!=null){ //kada je null ne radi...
			$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Zona rizika: ' .$zona_rizika);
		}

		$baseRow = 5;
		foreach($polise as $r => $polisa) {

			$malusIznos = $malusProcenat = $bonusIznos = $bonusProcenat = 0;


			if ($polisa->getPolao3()->getIznosbonmal()>0){
				$malusIznos = $polisa->getPolao3()->getIznosbonmal();
				$malusProcenat = $polisa->getPolao3()->getBonusmalus()/100; //jer phpexcel procente mnozi sa 100
			}
			else{
				$bonusIznos = $polisa->getPolao3()->getIznosbonmal();
				$bonusProcenat = $polisa->getPolao3()->getBonusmalus()/100;
			}

			if (is_null($polisa->getPolao3()->getIzn_doplatka())){
				$iznos_doplatka = 0;
			}

			if (is_null($polisa->getPolao3()->getIzn_popusta())){
				$iznos_popusta = 0;
			}

			//amela: porebno da u izjvestaju pise 5 za federaciju
			if ($polisa->getPolao3()->getZonr()->getZr_sifra() == 3) {
				$zonr = 5;
			} else {
				$zonr=$polisa->getPolao3()->getZonr()->getZr_sifra();
			}


			$row = $baseRow + $r;
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);

			$objPHPExcel->getActiveSheet()
			->setCellValue('A'.$row, '51')
			->setCellValue('B'.$row, 'Atos osiguranje a.d.')
			->setCellValue('C'.$row, $polisa->getPol_brpol())
			->setCellValue('D'.$row, $polisa->getBm_prenos_polisa())
			->setCellValue('E'.$row, $polisa->getDatdok()->format('d.m.Y'))
			->setCellValue('F'.$row, $polisa->getDatpoc()->format('d.m.Y'))
			->setCellValue('G'.$row, $polisa->getDatist()->format('d.m.Y'))
			->setCellValue('H'.$row, $polisa->getNazivugov())
			->setCellValueExplicit('I'.$row, (string)$polisa->getJmbg(), \PHPExcel_Cell_DataType::TYPE_STRING)
			->setCellValue('J'.$row, $polisa->getPolao3()->getOsnpremao())
			->setCellValue('K'.$row, $polisa->getPolao3()->getUk_prem_ao())
			->setCellValue('L'.$row, $bonusIznos)
			->setCellValue('M'.$row, $bonusProcenat)
			->setCellValue('N'.$row, $malusIznos)
			->setCellValue('O'.$row, $malusProcenat)
			->setCellValue('P'.$row, $iznos_doplatka)
			->setCellValue('Q'.$row, $iznos_popusta)
			->setCellValue('R'.$row, $polisa->getPolao2()->getBrojsasije())
			->setCellValue('S'.$row, $polisa->getPolao2()->getRegbroj())
			->setCellValue('T'.$row, $polisa->getPolao2()->getSnagakw() . ' kW')
			->setCellValue('U'.$row, $zonr)
			->setCellValueExplicit('V'.$row, (string)('0'.$polisa->getPolao3()->getTargrupa() . '-0' . $polisa->getPolao3()->getTarpodgrupa()), \PHPExcel_Cell_DataType::TYPE_STRING);



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
		header('Content-Disposition: attachment;filename="pregled_zakljucenih_polisa.xlsx"');
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
