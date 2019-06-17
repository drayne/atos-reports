<?php

namespace Izvjestaji\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

use Doctrine\DBAL\Event\Listeners\OracleSessionInit;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Query\ResultSetMapping;

class ScheduleController extends AbstractActionController {
	
	public function indexAction()
	{
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_crawler');
		$doctrineEventManager = $entityManager->getEventManager();
		$doctrineEventManager->addEventSubscriber(new OracleSessionInit(array(
				'NLS_DATE_FORMAT' 		=> 'YYYY-MM-DD HH24:MI:SS',
				'NLS_TIMESTAMP_FORMAT' 	=> 'YYYY-MM-DD HH24:MI:SS'
		)));
		/*datum u upitima treba postavljati u formatu 2016-05-10*/
// 		$sql = "SELECT OJ, POL_BRPOL, ORGJED.FILIJALA
// 				FROM POLISA, ORGJED				
// 				WHERE ORGJED.OJ_SIFRA = POLISA.OJ 
				
// 				AND POLISA.DATDOK>= SYSDATE-2

// 				ORDER BY DATDOK DESC
// 		";
		
		
		$sql = strtoupper (
"select
anlanl.pol_brpol POL_BRPOL, anlanl.komitent, polisa.datpoc,polisa.datist,POLISA.nazivugov,upiti.vrati_opstinu(polisa.pttmug) mjesto,ZASTUP.NAZIV NAZIV_ZASTUPNIKA,
				ZASTUP.ZAS_SIFRA,
decode(orgjed.filijala,null,0,orgjed.filijala) FILIJALA,
anlanl.konto,naziv_konta(anlanl.konto,anlanl.anl_radnja) konnaziv,
sum(dev_duguje-dev_potrazuje) IZNOS
from anlanl
join zastup on anlanl.jmbg=zastup.zas_sifra
left join orgjed on zastup.oj=orgjed.oj_sifra
join filijala on orgjed.filijala=filijala.fil_sifra
join polisa on anlanl.pol_brpol = polisa.pol_brpol
where
anl_vlasnik=1 and anl_radnja=2
and konto is not null and komitent is not null
	
                and polisa.datpoc between (sysdate-366) and (sysdate-90)
                and anlanl.datdok between (sysdate-420) and (sysdate+365)
				and anlanl.konto in (20110, 20120, 20130, 20140, 20170, 20190)
				having sum(anlanl.dev_duguje - anlanl.dev_potrazuje)<>0
				group by anlanl.pol_brpol, anlanl.komitent, anlanl.konto, naziv_konta(anlanl.konto,anlanl.anl_radnja), polisa.datpoc, polisa.datist, POLISA.nazivugov, ZASTUP.NAZIV,ZASTUP.ZAS_SIFRA, upiti.vrati_opstinu(polisa.pttmug),
				decode(orgjed.filijala,null,0,orgjed.filijala)
				order by anlanl.komitent asc, polisa.datpoc");

/**
 *                 and polisa.datpoc between (sysdate-1066) and (sysdate-90)
                and anlanl.datdok between (sysdate-1200) and (sysdate+365)
				and POLISA.DATIST<='2017-11-30'
 */

		// tanja sladjana., polise koje su istekle a postoji potrazivnje
//and POLISA.DATIST between '2014-12-01' and '2014-12-31'
//and anlanl.datdok between '1990-11-01' and sysdate+365
				
		
		
		//$sql = "SELECT p.pol_brpol, p.oj, p.adresa FROM POLISA P WHERE rownum<=5 ORDER BY P.DATDOK DESC ";
		//$sql = "SELECT distinct OJ FROM (SELECT p.pol_brpol, p.oj, p.adresa FROM POLISA P ORDER BY P.DATDOK DESC) WHERE rownum<=5 ";

// 		ResultSetMapping that describes how the results will be mapped.
// 		$rsm = new ResultSetMapping();
		
// 		$query = $entityManager->createNativeQuery($sql, $rsm);
// 		print_r($query->getResult());

		$conn = $entityManager->getConnection();
		$stmt = $conn->query($sql);
		
// 		while ($row = $stmt->fetch()) {
// 		    echo $row['ENAME'];
// 		}
		$rezultat = $stmt->fetchAll();
		
		$rezultat_bijeljina = $rezultat_zvornik = $rezultat_brcko = $rezultat_pale = $rezultat_trebinje = $rezultat_doboj = $rezultat_banja_luka = $rezultat_prijedor = $rezultat_sarajevo = array();
		
		foreach($rezultat as $r => $vrijednost) {
			if($vrijednost['FILIJALA'] == 1){
				$rezultat_bijeljina[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 2) {
				$rezultat_zvornik[] = $rezultat[$r];
			} 
			else if ($vrijednost['FILIJALA'] == 2) {
				$rezultat_zvornik[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 3) {
				$rezultat_pale[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 4) {
				$rezultat_trebinje[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 5) {
				$rezultat_brcko[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 6) {
				$rezultat_doboj[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 7) {
				$rezultat_banja_luka[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 8) {
				$rezultat_prijedor[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 10) {
				$rezultat_sarajevo[] = $rezultat[$r];
			}
		}

//  		print_r($rezultat_bijeljina);
//  		exit;
		
		ScheduleController::kreirajExcel(1, $rezultat_bijeljina);
		ScheduleController::kreirajExcel(2, $rezultat_zvornik);
		ScheduleController::kreirajExcel(3, $rezultat_pale);
		ScheduleController::kreirajExcel(4, $rezultat_trebinje);
		ScheduleController::kreirajExcel(5, $rezultat_brcko);
		ScheduleController::kreirajExcel(6, $rezultat_doboj);
		ScheduleController::kreirajExcel(7, $rezultat_banja_luka);
		ScheduleController::kreirajExcel(8, $rezultat_prijedor);
		ScheduleController::kreirajExcel(10, $rezultat_sarajevo);
		
		//return new ViewModel();
	}
	
	public function kreirajExcel($filijala, $rezultat)
	{
		//define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		
		date_default_timezone_set('Europe/London');
		
		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("public/excel-template/schedule_dospjela_potrazivanja_template.xls");
		
		
		$objPHPExcel->getProperties()
		->setCreator("Atos osiguranje")
		->setLastModifiedBy("Atos osiguranje")
		->setTitle("Pregled zakljucenih polisa")
		->setSubject("Pregled zakljucenih polisa")
		->setDescription("Pregled zakljucenih polisa")
		->setKeywords("Pregled zakljucenih polisa")
		->setCategory("Intranet izvjestaji");
		
		$objPHPExcel->getActiveSheet()->setCellValue('H1', (date('d.m.Y')));
		if ($filijala == 1){
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Bijeljina');
		}	else if ($filijala == 2) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Zvornik');
		}	else if ($filijala == 3) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Pale');
		}	else if ($filijala == 4) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Trebinje');
		}	else if ($filijala == 5) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Brcko');
		}	else if ($filijala == 6) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Doboj');
		}	else if ($filijala == 7) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Banja Luka');
		}	else if ($filijala == 8) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Prijedor');
		}	else if ($filijala == 10) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Sarajevo');
		}
		
		$baseRow = 5;
		$iznos_ukupno = 0;
		foreach($rezultat as $r => $rezultat) {
			$iznos_ukupno += $rezultat['IZNOS'];
			$row = $baseRow + $r;
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
			
			$objPHPExcel->getActiveSheet()
			->setCellValue('A'.$row, $rezultat['POL_BRPOL'])
			->setCellValue('B'.$row, $rezultat['KOMITENT'])
			->setCellValue('C'.$row, $rezultat['NAZIVUGOV'])
			->setCellValue('D'.$row, date('d.m.Y',strtotime($rezultat['DATPOC'])))
			->setCellValue('E'.$row, date('d.m.Y',strtotime($rezultat['DATIST'])))
			->setCellValue('F'.$row, $rezultat['MJESTO'])
			->setCellValue('G'.$row, $rezultat['NAZIV_ZASTUPNIKA'])
			->setCellValue('H'.$row, $rezultat['ZAS_SIFRA'])
			->setCellValue('I'.$row, $rezultat['KONNAZIV'])
			->setCellValue('J'.$row, $rezultat['IZNOS'])
			;		
		}
		//upis sume, jer u yandexu ne ispisuje kako treba
		$red_ukupno = $row +2;
		$objPHPExcel->getActiveSheet()->insertNewRowBefore($red_ukupno,1);
		$objPHPExcel->getActiveSheet()
								->setCellValue('I'.$red_ukupno, 'Ukupno: ')
								->setCellValue('J'.$red_ukupno, $iznos_ukupno);
								
		//upis sume, jer u yandexu ne ispisuje kako treba
		
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		
		$naziv_fajla = $filijala . '_schedule_dospjela_potrazivanja';

		
		//NE TREBA DA SKIDA NA RACUNAR KLIJENTA, NEGO DA KREIRA NA SERVERU EXCEL FAJL
		
		$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save('public/schedule/'.$naziv_fajla . '.xlsx');
		
	}
	
	public function potrazivanjaSumarnoAction()
	{
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_crawler');
		$doctrineEventManager = $entityManager->getEventManager();
		$doctrineEventManager->addEventSubscriber(new OracleSessionInit(array(
				'NLS_DATE_FORMAT' 		=> 'YYYY-MM-DD HH24:MI:SS',
				'NLS_TIMESTAMP_FORMAT' 	=> 'YYYY-MM-DD HH24:MI:SS'
		)));
		
		$sql = strtoupper (
"
select jedan.filijala, jedan.iznos jedan_iznos, tri.iznos tri_iznos
from 
(select
decode(orgjed.filijala,null,0,orgjed.filijala) FILIJALA,
sum(dev_duguje-dev_potrazuje) IZNOS

from anlanl
join zastup on anlanl.jmbg=zastup.zas_sifra
left join orgjed on zastup.oj=orgjed.oj_sifra
join filijala on orgjed.filijala=filijala.fil_sifra
join polisa on anlanl.pol_brpol = polisa.pol_brpol
where
anl_vlasnik=1 and anl_radnja=2
and konto is not null and komitent is not null
                and polisa.datprip between (sysdate-1095) and (sysdate-367)
                and anlanl.datdok between (sysdate-1195) and (sysdate+65)
                and anlanl.konto in (20110, 20120, 20130, 20140, 20170, 20190)
                having sum(anlanl.dev_duguje - anlanl.dev_potrazuje)<>0
                group by  decode(orgjed.filijala,null,0,orgjed.filijala)
) tri,

(select
decode(orgjed.filijala,null,0,orgjed.filijala) FILIJALA,
sum(dev_duguje-dev_potrazuje) IZNOS
from anlanl
join zastup on anlanl.jmbg=zastup.zas_sifra
left join orgjed on zastup.oj=orgjed.oj_sifra
join filijala on orgjed.filijala=filijala.fil_sifra
join polisa on anlanl.pol_brpol = polisa.pol_brpol
where
anl_vlasnik=1 and anl_radnja=2
and konto is not null and komitent is not null
and polisa.datprip between (sysdate-366) and (sysdate-90)
                and anlanl.datdok between (sysdate-420) and (sysdate+365)
                and anlanl.konto in (20110, 20120, 20130, 20140, 20170, 20190)
                having sum(anlanl.dev_duguje - anlanl.dev_potrazuje)<>0
                group by  decode(orgjed.filijala,null,0,orgjed.filijala)
) jedan                      
where tri.filijala = jedan.filijala                
order by jedan.filijala asc
				");
		$conn = $entityManager->getConnection();
		$stmt = $conn->query($sql);
		$rezultat = $stmt->fetchAll();
				
		ScheduleController::kreirajExcelPotrazivanjaSumarno($rezultat);
	}
	
	public function kreirajExcelPotrazivanjaSumarno($rezultat)
	{
		//define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	
		date_default_timezone_set('Europe/London');
	
		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("public/excel-template/schedule_dospjela_potrazivanja_sumarno_template.xls");
	
	
		$objPHPExcel->getProperties()
		->setCreator("Atos osiguranje")
		->setLastModifiedBy("Atos osiguranje")
		->setTitle("Pregled potrazivanja")
		->setSubject("Pregled potrazivanja")
		->setDescription("Pregled potrazivanja")
		->setKeywords("Pregled potrazivanja")
		->setCategory("Intranet izvjestaji");

	
		$baseRow = 5;
		$iznos_ukupno = 0;
		$jedan_iznos_ukupno=$tri_iznos_ukupno=0;
		foreach($rezultat as $r => $rezultat) {
			$jedan_iznos_ukupno += $rezultat['JEDAN_IZNOS'];
			$tri_iznos_ukupno 	+= $rezultat['TRI_IZNOS'];
			$row = $baseRow + $r;
			$nazivFilijale = ScheduleController::vratiNazivFilijale($rezultat['FILIJALA']);
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);			
			
			$objPHPExcel->getActiveSheet()
			->setCellValue('A'.$row, $nazivFilijale)
			->setCellValue('B'.$row, $rezultat['JEDAN_IZNOS'])
			->setCellValue('C'.$row, $rezultat['TRI_IZNOS'])
// 			->setCellValue('D'.$row, date('d.m.Y',strtotime($rezultat['DATPOC'])))
// 			->setCellValue('E'.$row, date('d.m.Y',strtotime($rezultat['DATIST'])))
// 			->setCellValue('F'.$row, $rezultat['MJESTO'])
// 			->setCellValue('G'.$row, $rezultat['NAZIV_ZASTUPNIKA'])
// 			->setCellValue('H'.$row, $rezultat['ZAS_SIFRA'])
// 			->setCellValue('I'.$row, $rezultat['KONNAZIV'])
// 			->setCellValue('J'.$row, $rezultat['IZNOS'])
			;
		}
		//upis sume, jer u yandexu ne ispisuje kako treba
		$red_ukupno = $row +2;
		$objPHPExcel->getActiveSheet()->insertNewRowBefore($red_ukupno,1);
		$objPHPExcel->getActiveSheet()
		->setCellValue('A'.$red_ukupno, 'Ukupno KM: ')
		->setCellValue('B'.$red_ukupno, $jedan_iznos_ukupno)
		->setCellValue('C'.$red_ukupno, $tri_iznos_ukupno);
	
		//upis sume, jer u yandexu ne ispisuje kako treba
	
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
	
		$naziv_fajla = 'schedule_dospjela_potrazivanja_sumarno';
	
	
		//NE TREBA DA SKIDA NA RACUNAR KLIJENTA, NEGO DA KREIRA NA SERVERU EXCEL FAJL
	
		$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save('public/schedule/'.$naziv_fajla . '.xlsx');
	
	}
	
	public function potrazivanjaTriGodineAction()
	{
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_crawler');
		$doctrineEventManager = $entityManager->getEventManager();
		$doctrineEventManager->addEventSubscriber(new OracleSessionInit(array(
				'NLS_DATE_FORMAT' 		=> 'YYYY-MM-DD HH24:MI:SS',
				'NLS_TIMESTAMP_FORMAT' 	=> 'YYYY-MM-DD HH24:MI:SS'
		)));
		
		$sql = strtoupper (
				"select
anlanl.pol_brpol POL_BRPOL, anlanl.komitent, polisa.datpoc,polisa.datist,POLISA.nazivugov,upiti.vrati_opstinu(polisa.pttmug) mjesto,ZASTUP.NAZIV NAZIV_ZASTUPNIKA,
				ZASTUP.ZAS_SIFRA,
decode(orgjed.filijala,null,0,orgjed.filijala) FILIJALA,
anlanl.konto,naziv_konta(anlanl.konto,anlanl.anl_radnja) konnaziv,
sum(dev_duguje-dev_potrazuje) IZNOS
from anlanl
join zastup on anlanl.jmbg=zastup.zas_sifra
left join orgjed on zastup.oj=orgjed.oj_sifra
join filijala on orgjed.filijala=filijala.fil_sifra
join polisa on anlanl.pol_brpol = polisa.pol_brpol
where
anl_vlasnik=1 and anl_radnja=2
and konto is not null and komitent is not null
				and polisa.datprip between (sysdate-1095) and (sysdate-367)
                and anlanl.datdok between (sysdate-1195) and (sysdate+65)
				and anlanl.konto in (20110, 20120, 20130, 20140, 20170, 20190)
				having sum(anlanl.dev_duguje - anlanl.dev_potrazuje)<>0
				group by anlanl.pol_brpol, anlanl.komitent, anlanl.konto, naziv_konta(anlanl.konto,anlanl.anl_radnja), polisa.datpoc, polisa.datist, POLISA.nazivugov, ZASTUP.NAZIV,ZASTUP.ZAS_SIFRA, upiti.vrati_opstinu(polisa.pttmug),
				decode(orgjed.filijala,null,0,orgjed.filijala)
				order by anlanl.komitent asc, polisa.datpoc");

		
		$conn = $entityManager->getConnection();
		$stmt = $conn->query($sql);
		$rezultat = $stmt->fetchAll();
		
		$rezultat_bijeljina = $rezultat_zvornik = $rezultat_brcko = $rezultat_pale = $rezultat_trebinje = $rezultat_doboj = $rezultat_banja_luka = $rezultat_prijedor = $rezultat_sarajevo = array();
		
		foreach($rezultat as $r => $vrijednost) {
			if($vrijednost['FILIJALA'] == 1){
				$rezultat_bijeljina[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 2) {
				$rezultat_zvornik[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 2) {
				$rezultat_zvornik[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 3) {
				$rezultat_pale[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 4) {
				$rezultat_trebinje[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 5) {
				$rezultat_brcko[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 6) {
				$rezultat_doboj[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 7) {
				$rezultat_banja_luka[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 8) {
				$rezultat_prijedor[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 10) {
				$rezultat_sarajevo[] = $rezultat[$r];
			}
		}
		
		//  		print_r($rezultat_bijeljina);
		//  		exit;
		
		ScheduleController::kreirajExcelTriGodine(1, $rezultat_bijeljina);
		ScheduleController::kreirajExcelTriGodine(2, $rezultat_zvornik);
		ScheduleController::kreirajExcelTriGodine(3, $rezultat_pale);
		ScheduleController::kreirajExcelTriGodine(4, $rezultat_trebinje);
		ScheduleController::kreirajExcelTriGodine(5, $rezultat_brcko);
		ScheduleController::kreirajExcelTriGodine(6, $rezultat_doboj);
		ScheduleController::kreirajExcelTriGodine(7, $rezultat_banja_luka);
		ScheduleController::kreirajExcelTriGodine(8, $rezultat_prijedor);
		ScheduleController::kreirajExcelTriGodine(10, $rezultat_sarajevo);
		
		$view = new ViewModel();
		$view->setTemplate('izvjestaji/schedule/potrazivanja-tri-godine.phtml');
		return $view;
	}
	
	public function kreirajExcelTriGodine($filijala, $rezultat)
	{
		//define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	
		date_default_timezone_set('Europe/London');
	
		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("public/excel-template/schedule_dospjela_potrazivanja_template.xls");
	
	
		$objPHPExcel->getProperties()
		->setCreator("Atos osiguranje")
		->setLastModifiedBy("Atos osiguranje")
		->setTitle("Pregled zakljucenih polisa")
		->setSubject("Pregled zakljucenih polisa")
		->setDescription("Pregled zakljucenih polisa")
		->setKeywords("Pregled zakljucenih polisa")
		->setCategory("Intranet izvjestaji");
	
		$objPHPExcel->getActiveSheet()->setCellValue('H1', (date('d.m.Y')));
		if ($filijala == 1){
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Bijeljina');
		}	else if ($filijala == 2) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Zvornik');
		}	else if ($filijala == 3) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Pale');
		}	else if ($filijala == 4) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Trebinje');
		}	else if ($filijala == 5) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Brcko');
		}	else if ($filijala == 6) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Doboj');
		}	else if ($filijala == 7) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Banja Luka');
		}	else if ($filijala == 8) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Prijedor');
		}	else if ($filijala == 10) {
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Filijala Sarajevo');
		}
	
		$baseRow = 5;
		$iznos_ukupno = 0;
		foreach($rezultat as $r => $rezultat) {
			$iznos_ukupno += $rezultat['IZNOS'];
			$row = $baseRow + $r;
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
				
			$objPHPExcel->getActiveSheet()
			->setCellValue('A'.$row, $rezultat['POL_BRPOL'])
			->setCellValue('B'.$row, $rezultat['KOMITENT'])
			->setCellValue('C'.$row, $rezultat['NAZIVUGOV'])
			->setCellValue('D'.$row, date('d.m.Y',strtotime($rezultat['DATPOC'])))
			->setCellValue('E'.$row, date('d.m.Y',strtotime($rezultat['DATIST'])))
			->setCellValue('F'.$row, $rezultat['MJESTO'])
			->setCellValue('G'.$row, $rezultat['NAZIV_ZASTUPNIKA'])
			->setCellValue('H'.$row, $rezultat['ZAS_SIFRA'])
			->setCellValue('I'.$row, $rezultat['KONNAZIV'])
			->setCellValue('J'.$row, $rezultat['IZNOS'])
			;
		}
		//upis sume, jer u yandexu ne ispisuje kako treba
		$red_ukupno = $row +2;
		$objPHPExcel->getActiveSheet()->insertNewRowBefore($red_ukupno,1);
		$objPHPExcel->getActiveSheet()
		->setCellValue('I'.$red_ukupno, 'Ukupno: ')
		->setCellValue('J'.$red_ukupno, $iznos_ukupno);
	
		//upis sume, jer u yandexu ne ispisuje kako treba
	
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
	
		$naziv_fajla = $filijala . '_schedule_dospjela_potrazivanja_3g';
	
	
		//NE TREBA DA SKIDA NA RACUNAR KLIJENTA, NEGO DA KREIRA NA SERVERU EXCEL FAJL
	
		$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save('public/schedule/'.$naziv_fajla . '.xlsx');
	
	}
	
	public function vratiNazivFilijale($sifraFilijale)
	{
		if ($sifraFilijale == 1){
			$nazivFilijale= 'Filijala Bijeljina';
		}	else if ($sifraFilijale == 2) {
			$nazivFilijale= 'Filijala Zvornik';
		}	else if ($sifraFilijale == 3) {
			$nazivFilijale= 'Filijala Pale';
		}	else if ($sifraFilijale == 4) {
			$nazivFilijale= 'Filijala Trebinje';
		}	else if ($sifraFilijale == 5) {
			$nazivFilijale= 'Filijala Brcko';
		}	else if ($sifraFilijale == 6) {
			$nazivFilijale= 'Filijala Doboj';
		}	else if ($sifraFilijale == 7) {
			$nazivFilijale= 'Filijala Banja Luka';
		}	else if ($sifraFilijale == 8) {
			$nazivFilijale= 'Filijala Prijedor';
		}	else if ($sifraFilijale == 10) {
			$nazivFilijale= 'Filijala Sarajevo';
		}
		return $nazivFilijale;
	}

	public function potrazivanjaAoAction()
	{
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_crawler');
		$doctrineEventManager = $entityManager->getEventManager();
		$doctrineEventManager->addEventSubscriber(new OracleSessionInit(array(
				'NLS_DATE_FORMAT' 		=> 'YYYY-MM-DD HH24:MI:SS',
				'NLS_TIMESTAMP_FORMAT' 	=> 'YYYY-MM-DD HH24:MI:SS'
		)));
	
		$sql = strtoupper (
				"
				select
                anlanl.pol_brpol POL_BRPOL, anlanl.komitent, 
                polisa.datpoc,polisa.datist,POLISA.nazivugov,upiti.vrati_opstinu(polisa.pttmug) mjesto,ZASTUP.NAZIV NAZIV_ZASTUPNIKA,
                                ZASTUP.ZAS_SIFRA,
                decode(orgjed.filijala,null,0,orgjed.filijala) FILIJALA,
                anlanl.konto,naziv_konta(anlanl.konto,anlanl.anl_radnja) konnaziv,
                sum(dev_duguje-dev_potrazuje) IZNOS,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between '2014-12-01' and (sysdate-7)) ukupan_broj_polisa,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between '2014-12-01' and (sysdate-7) and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=1)) 	ukupan_broj_polisa_bijeljina,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between '2014-12-01' and (sysdate-7) and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=2)) 	ukupan_broj_polisa_zvornik,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between '2014-12-01' and (sysdate-7) and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=3)) 	ukupan_broj_polisa_pale,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between '2014-12-01' and (sysdate-7) and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=4)) 	ukupan_broj_polisa_trebinje,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between '2014-12-01' and (sysdate-7) and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=5)) 	ukupan_broj_polisa_brcko,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between '2014-12-01' and (sysdate-7) and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=6)) 	ukupan_broj_polisa_doboj,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between '2014-12-01' and (sysdate-7) and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=7)) 	ukupan_broj_polisa_banjaluka,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between '2014-12-01' and (sysdate-7) and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=8)) 	ukupan_broj_polisa_prijedor,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between '2014-12-01' and (sysdate-7) and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=10)) 	ukupan_broj_polisa_sarajevo
                from anlanl
                join zastup on anlanl.jmbg=zastup.zas_sifra
                left join orgjed on zastup.oj=orgjed.oj_sifra
                join filijala on orgjed.filijala=filijala.fil_sifra
                join polisa on anlanl.pol_brpol = polisa.pol_brpol
                
                where
                anl_vlasnik=1 and anl_radnja=2
                and konto is not null and komitent is not null
				--and polisa.datpoc between '2015-01-01' and (sysdate-7)
				and polisa.datprip between '2014-12-01' and (sysdate-7)
				
                and anlanl.datdok between '2014-10-01' and sysdate
                and anlanl.konto in (20100)
                having sum(anlanl.dev_duguje - anlanl.dev_potrazuje)<>0
                group by anlanl.pol_brpol, anlanl.komitent, anlanl.konto, naziv_konta(anlanl.konto,anlanl.anl_radnja), polisa.datpoc, polisa.datist, POLISA.nazivugov, ZASTUP.NAZIV,ZASTUP.ZAS_SIFRA, upiti.vrati_opstinu(polisa.pttmug),
                decode(orgjed.filijala,null,0,orgjed.filijala)
                order by filijala, naziv_zastupnika, polisa.datpoc
					");

		$conn = $entityManager->getConnection();
		$stmt = $conn->query($sql);
		$rezultat = $stmt->fetchAll();
		
		$rezultat_bijeljina = $rezultat_zvornik = $rezultat_brcko = $rezultat_pale = $rezultat_trebinje = $rezultat_doboj = $rezultat_banja_luka = $rezultat_prijedor = $rezultat_sarajevo = array();
		
		foreach($rezultat as $r => $vrijednost) {
			if($vrijednost['FILIJALA'] == 1){
				$rezultat_bijeljina[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 2) {
				$rezultat_zvornik[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 2) {
				$rezultat_zvornik[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 3) {
				$rezultat_pale[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 4) {
				$rezultat_trebinje[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 5) {
				$rezultat_brcko[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 6) {
				$rezultat_doboj[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 7) {
				$rezultat_banja_luka[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 8) {
				$rezultat_prijedor[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 10) {
				$rezultat_sarajevo[] = $rezultat[$r];
			}
		}
		
		//fajl koji objedinjuje sve
		ScheduleController::kreirajExcelPotrazivanjaAo(0, $rezultat);
		//pojedinacno za direktore pripadajuce filijale
		if (!empty($rezultat_bijeljina)) 		{ ScheduleController::kreirajExcelPotrazivanjaAo(1, $rezultat_bijeljina); }
		if (!empty($rezultat_zvornik)) 			{ ScheduleController::kreirajExcelPotrazivanjaAo(2, $rezultat_zvornik); }
		if (!empty($rezultat_pale)) 			{ ScheduleController::kreirajExcelPotrazivanjaAo(3, $rezultat_pale); }
		if (!empty($rezultat_trebinje)) 		{ ScheduleController::kreirajExcelPotrazivanjaAo(4, $rezultat_trebinje); }
		if (!empty($rezultat_brcko)) 			{ ScheduleController::kreirajExcelPotrazivanjaAo(5, $rezultat_brcko); }
		if (!empty($rezultat_doboj)) 			{ ScheduleController::kreirajExcelPotrazivanjaAo(6, $rezultat_doboj); }
		if (!empty($rezultat_banja_luka)) 		{ ScheduleController::kreirajExcelPotrazivanjaAo(7, $rezultat_banja_luka); }
		if (!empty($rezultat_prijedor)) 		{ ScheduleController::kreirajExcelPotrazivanjaAo(8, $rezultat_prijedor); }
		if (!empty($rezultat_sarajevo)) 		{ ScheduleController::kreirajExcelPotrazivanjaAo(10, $rezultat_sarajevo); }
		
	}

	public function kreirajExcelPotrazivanjaAo($filijala, $rezultat)
	{
		//define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	
		date_default_timezone_set('Europe/London');
	
		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("public/excel-template/schedule_ao_potrazivanja_template.xls");
	
	
		$objPHPExcel->getProperties()
		->setCreator("Atos osiguranje")
		->setLastModifiedBy("Atos osiguranje")
		->setTitle("Pregled potrazivanja")
		->setSubject("Pregled potrazivanja")
		->setDescription("Pregled potrazivanja")
		->setKeywords("Pregled potrazivanja")
		->setCategory("Intranet izvjestaji");
	
		
		//$objPHPExcel->getActiveSheet()->setCellValue('H1', (date('d.m.Y', strtotime('-1000 days'))));
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '01.01.2015');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', (date('d.m.Y')));
				
		$baseRow = 5;
		$iznos_ukupno = 0;
		$iznos_ukupno=0;
		$ukupan_broj_polisa=0;
		
		$kolona = 'UKUPAN_BROJ_POLISA';
		if ($filijala == 1) { $kolona = 'UKUPAN_BROJ_POLISA_BIJELJINA'; }
		if ($filijala == 2) { $kolona = 'UKUPAN_BROJ_POLISA_ZVORNIK'; }
		if ($filijala == 3) { $kolona = 'UKUPAN_BROJ_POLISA_PALE'; }
		if ($filijala == 4) { $kolona = 'UKUPAN_BROJ_POLISA_TREBINJE'; }
		if ($filijala == 5) { $kolona = 'UKUPAN_BROJ_POLISA_BRCKO'; }
		if ($filijala == 6) { $kolona = 'UKUPAN_BROJ_POLISA_DOBOJ'; }
		if ($filijala == 7) { $kolona = 'UKUPAN_BROJ_POLISA_BANJALUKA'; }
		if ($filijala == 8) { $kolona = 'UKUPAN_BROJ_POLISA_PRIJEDOR'; }
		if ($filijala == 10) { $kolona = 'UKUPAN_BROJ_POLISA_SARAJEVO'; }
		
		foreach($rezultat as $r => $rezultat) { 
			
			$ukupan_broj_polisa = $rezultat[$kolona];
			$iznos_ukupno += $rezultat['IZNOS'];
			$row = $baseRow + $r;
			$nazivFilijale = ScheduleController::vratiNazivFilijale($rezultat['FILIJALA']);
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
				
			$objPHPExcel->getActiveSheet()
			->setCellValue('A'.$row, $rezultat['POL_BRPOL'])
			->setCellValue('B'.$row, $rezultat['NAZIVUGOV'])
			->setCellValue('C'.$row, date('d.m.Y',strtotime($rezultat['DATPOC'])))
			->setCellValue('D'.$row, date('d.m.Y',strtotime($rezultat['DATIST'])))
			->setCellValue('E'.$row, $rezultat['MJESTO'])
			->setCellValue('F'.$row, $nazivFilijale)
			->setCellValue('G'.$row, $rezultat['NAZIV_ZASTUPNIKA'])
			->setCellValue('H'.$row, $rezultat['ZAS_SIFRA'])
			->setCellValue('I'.$row, $rezultat['IZNOS'])
			//->setCellValue('H2', $rezultat['UKUPAN_BROJ_POLISA'])
			;
		}
		$objPHPExcel->getActiveSheet()->setCellValue('H2', $ukupan_broj_polisa);
		
		//upis sume, jer u yandexu ne ispisuje kako treba
		$red_ukupno = $row +2;
		$objPHPExcel->getActiveSheet()->insertNewRowBefore($red_ukupno,1);
		$objPHPExcel->getActiveSheet()
		->setCellValue('A'.$red_ukupno, 'Ukupno KM: ')
		->setCellValue('I'.$red_ukupno, $iznos_ukupno);
	
		//upis sume, jer u yandexu ne ispisuje kako treba
	
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		
		if ($filijala == 0) { $filijala = 'sve'; }
		
		$naziv_fajla = $filijala . '_schedule_ao_potrazivanja';
	
	
		//NE TREBA DA SKIDA NA RACUNAR KLIJENTA, NEGO DA KREIRA NA SERVERU EXCEL FAJL
	
		$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save('public/schedule/'.$naziv_fajla . '.xlsx');
		
		
	}
	
	public function potrazivanjaAoPetnaestAction()
	{
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_crawler');
		$doctrineEventManager = $entityManager->getEventManager();
		$doctrineEventManager->addEventSubscriber(new OracleSessionInit(array(
				'NLS_DATE_FORMAT' 		=> 'YYYY-MM-DD HH24:MI:SS',
				'NLS_TIMESTAMP_FORMAT' 	=> 'YYYY-MM-DD HH24:MI:SS'
		)));
	
		$sql = strtoupper (
				"
				select
                anlanl.pol_brpol POL_BRPOL, anlanl.komitent,
                polisa.datpoc,polisa.datist,POLISA.nazivugov,upiti.vrati_opstinu(polisa.pttmug) mjesto,ZASTUP.NAZIV NAZIV_ZASTUPNIKA,
                                ZASTUP.ZAS_SIFRA,
                decode(orgjed.filijala,null,0,orgjed.filijala) FILIJALA,
                anlanl.konto,naziv_konta(anlanl.konto,anlanl.anl_radnja) konnaziv,
                sum(dev_duguje-dev_potrazuje) IZNOS,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between (sysdate-15) and sysdate) ukupan_broj_polisa,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between (sysdate-15) and sysdate and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=1)) 	ukupan_broj_polisa_bijeljina,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between (sysdate-15) and sysdate and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=2)) 	ukupan_broj_polisa_zvornik,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between (sysdate-15) and sysdate and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=3)) 	ukupan_broj_polisa_pale,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between (sysdate-15) and sysdate and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=4)) 	ukupan_broj_polisa_trebinje,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between (sysdate-15) and sysdate and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=5)) 	ukupan_broj_polisa_brcko,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between (sysdate-15) and sysdate and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=6)) 	ukupan_broj_polisa_doboj,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between (sysdate-15) and sysdate and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=7)) 	ukupan_broj_polisa_banjaluka,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between (sysdate-15) and sysdate and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=8)) 	ukupan_broj_polisa_prijedor,
                (select count(*) from polisa p where  p.vros = 800 and  p.datprip between (sysdate-15) and sysdate and P.OJ in (select O.OJ_SIFRA from orgjed o where O.FILIJALA=10)) 	ukupan_broj_polisa_sarajevo
                from anlanl
                join zastup on anlanl.jmbg=zastup.zas_sifra
                left join orgjed on zastup.oj=orgjed.oj_sifra
                join filijala on orgjed.filijala=filijala.fil_sifra
                join polisa on anlanl.pol_brpol = polisa.pol_brpol
	
                where
                anl_vlasnik=1 and anl_radnja=2
                and konto is not null and komitent is not null
				and polisa.datprip between (sysdate-15) and (sysdate-7)
	
                and anlanl.datdok between (sysdate-60) and sysdate
                and anlanl.konto in (20100)
                having sum(anlanl.dev_duguje - anlanl.dev_potrazuje)<>0
                group by anlanl.pol_brpol, anlanl.komitent, anlanl.konto, naziv_konta(anlanl.konto,anlanl.anl_radnja), polisa.datpoc, polisa.datist, POLISA.nazivugov, ZASTUP.NAZIV,ZASTUP.ZAS_SIFRA, upiti.vrati_opstinu(polisa.pttmug),
                decode(orgjed.filijala,null,0,orgjed.filijala)
                order by filijala, naziv_zastupnika, polisa.datpoc
					");
	
		$conn = $entityManager->getConnection();
		$stmt = $conn->query($sql);
		$rezultat = $stmt->fetchAll();
	
		$rezultat_bijeljina = $rezultat_zvornik = $rezultat_brcko = $rezultat_pale = $rezultat_trebinje = $rezultat_doboj = $rezultat_banja_luka = $rezultat_prijedor = $rezultat_sarajevo = array();
	
		foreach($rezultat as $r => $vrijednost) {
			if($vrijednost['FILIJALA'] == 1){
				$rezultat_bijeljina[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 2) {
				$rezultat_zvornik[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 2) {
				$rezultat_zvornik[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 3) {
				$rezultat_pale[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 4) {
				$rezultat_trebinje[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 5) {
				$rezultat_brcko[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 6) {
				$rezultat_doboj[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 7) {
				$rezultat_banja_luka[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 8) {
				$rezultat_prijedor[] = $rezultat[$r];
			}
			else if ($vrijednost['FILIJALA'] == 10) {
				$rezultat_sarajevo[] = $rezultat[$r];
			}
		}
	
		//fajl koji objedinjuje sve
		ScheduleController::kreirajExcelPotrazivanjaAoPetnaest(0, $rezultat);
		//pojedinacno za direktore pripadajuce filijale
		if (!empty($rezultat_bijeljina)) 		{ ScheduleController::kreirajExcelPotrazivanjaAoPetnaest(1, $rezultat_bijeljina); }
		if (!empty($rezultat_zvornik)) 			{ ScheduleController::kreirajExcelPotrazivanjaAoPetnaest(2, $rezultat_zvornik); }
		if (!empty($rezultat_pale)) 			{ ScheduleController::kreirajExcelPotrazivanjaAoPetnaest(3, $rezultat_pale); }
		if (!empty($rezultat_trebinje)) 		{ ScheduleController::kreirajExcelPotrazivanjaAoPetnaest(4, $rezultat_trebinje); }
		if (!empty($rezultat_brcko)) 			{ ScheduleController::kreirajExcelPotrazivanjaAoPetnaest(5, $rezultat_brcko); }
		if (!empty($rezultat_doboj)) 			{ ScheduleController::kreirajExcelPotrazivanjaAoPetnaest(6, $rezultat_doboj); }
		if (!empty($rezultat_banja_luka)) 		{ ScheduleController::kreirajExcelPotrazivanjaAoPetnaest(7, $rezultat_banja_luka); }
		if (!empty($rezultat_prijedor)) 		{ ScheduleController::kreirajExcelPotrazivanjaAoPetnaest(8, $rezultat_prijedor); }
		if (!empty($rezultat_sarajevo)) 		{ ScheduleController::kreirajExcelPotrazivanjaAoPetnaest(10, $rezultat_sarajevo); }
	
	}	
	
	public function kreirajExcelPotrazivanjaAoPetnaest($filijala, $rezultat)
	{
		//define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	
		date_default_timezone_set('Europe/London');
	
		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("public/excel-template/schedule_ao_potrazivanja_template.xls");
	
	
		$objPHPExcel->getProperties()
		->setCreator("Atos osiguranje")
		->setLastModifiedBy("Atos osiguranje")
		->setTitle("Pregled potrazivanja")
		->setSubject("Pregled potrazivanja")
		->setDescription("Pregled potrazivanja")
		->setKeywords("Pregled potrazivanja")
		->setCategory("Intranet izvjestaji");
	
	
		$objPHPExcel->getActiveSheet()->setCellValue('H1', (date('d.m.Y', strtotime('-15 days'))));
		$objPHPExcel->getActiveSheet()->setCellValue('I1', (date('d.m.Y')));
	
		$baseRow = 5;
		$iznos_ukupno = 0;
		$iznos_ukupno=0;
		$ukupan_broj_polisa=0;
	
		$kolona = 'UKUPAN_BROJ_POLISA';
		if ($filijala == 1) { $kolona = 'UKUPAN_BROJ_POLISA_BIJELJINA'; }
		if ($filijala == 2) { $kolona = 'UKUPAN_BROJ_POLISA_ZVORNIK'; }
		if ($filijala == 3) { $kolona = 'UKUPAN_BROJ_POLISA_PALE'; }
		if ($filijala == 4) { $kolona = 'UKUPAN_BROJ_POLISA_TREBINJE'; }
		if ($filijala == 5) { $kolona = 'UKUPAN_BROJ_POLISA_BRCKO'; }
		if ($filijala == 6) { $kolona = 'UKUPAN_BROJ_POLISA_DOBOJ'; }
		if ($filijala == 7) { $kolona = 'UKUPAN_BROJ_POLISA_BANJALUKA'; }
		if ($filijala == 8) { $kolona = 'UKUPAN_BROJ_POLISA_PRIJEDOR'; }
		if ($filijala == 10) { $kolona = 'UKUPAN_BROJ_POLISA_SARAJEVO'; }
	
		foreach($rezultat as $r => $rezultat) {
				
			$ukupan_broj_polisa = $rezultat[$kolona];
			$iznos_ukupno += $rezultat['IZNOS'];
			$row = $baseRow + $r;
			$nazivFilijale = ScheduleController::vratiNazivFilijale($rezultat['FILIJALA']);
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
	
			$objPHPExcel->getActiveSheet()
			->setCellValue('A'.$row, $rezultat['POL_BRPOL'])
			->setCellValue('B'.$row, $rezultat['NAZIVUGOV'])
			->setCellValue('C'.$row, date('d.m.Y',strtotime($rezultat['DATPOC'])))
			->setCellValue('D'.$row, date('d.m.Y',strtotime($rezultat['DATIST'])))
			->setCellValue('E'.$row, $rezultat['MJESTO'])
			->setCellValue('F'.$row, $nazivFilijale)
			->setCellValue('G'.$row, $rezultat['NAZIV_ZASTUPNIKA'])
			->setCellValue('H'.$row, $rezultat['ZAS_SIFRA'])
			->setCellValue('I'.$row, $rezultat['IZNOS'])
			//->setCellValue('H2', $rezultat['UKUPAN_BROJ_POLISA'])
			;
		}
		$objPHPExcel->getActiveSheet()->setCellValue('H2', $ukupan_broj_polisa);
	
		//upis sume, jer u yandexu ne ispisuje kako treba
		$red_ukupno = $row +2;
		$objPHPExcel->getActiveSheet()->insertNewRowBefore($red_ukupno,1);
		$objPHPExcel->getActiveSheet()
		->setCellValue('A'.$red_ukupno, 'Ukupno KM: ')
		->setCellValue('I'.$red_ukupno, $iznos_ukupno);
	
		//upis sume, jer u yandexu ne ispisuje kako treba
	
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
	
		if ($filijala == 0) { $filijala = 'sve'; }
	
		$naziv_fajla = $filijala . '_schedule_ao_15_potrazivanja';
	
	
		//NE TREBA DA SKIDA NA RACUNAR KLIJENTA, NEGO DA KREIRA NA SERVERU EXCEL FAJL
	
		$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save('public/schedule/'.$naziv_fajla . '.xlsx');
	
	
	}
	
}
?>
