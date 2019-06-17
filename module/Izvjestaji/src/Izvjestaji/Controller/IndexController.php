<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Izvjestaji for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Izvjestaji\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{

	public function indexAction()
	{
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
        
        date_default_timezone_set('Europe/London');
           
        
        
        //echo date('H:i:s') , " Load from Excel5 template" , EOL;
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load("public/excel-template/30template.xls");
        
        
        //echo date('H:i:s') , " Add new data to the template" , EOL;
        $data = array(
	        array('title'	=> 'Excel for dummies',
	            'price'		=> 17.99,
	            'quantity'	=> 2
        ),
            array('title'		=> 'PHP for dummies',
                'price'		=> 15.99,
                'quantity'	=> 1
            ),
            array('title'		=> 'Inside OOP',
                'price'		=> 12.95,
                'quantity'	=> 1
            )
        );
        
        $objPHPExcel->getActiveSheet()->setCellValue('D1', \PHPExcel_Shared_Date::PHPToExcel(time()));
        
        $baseRow = 5;
        foreach($data as $r => $dataRow) {
            $row = $baseRow + $r;
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
        
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r+1)
            ->setCellValue('B'.$row, $dataRow['title'])
            ->setCellValue('C'.$row, $dataRow['price'])
            ->setCellValue('D'.$row, $dataRow['quantity'])
            ->setCellValue('E'.$row, '=C'.$row.'*D'.$row);
        }
        $objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
        
        /*
        echo date('H:i:s') , " Write to Excel5 format" , EOL;
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save(str_replace('.php', '.xls', __FILE__));
        echo date('H:i:s') , " File written to " , str_replace('.php', '.xls', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
        
        
        // Echo memory peak usage
        echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;
        
        // Echo done
        echo date('H:i:s') , " Done writing file" , EOL;
        echo 'File has been created in ' , getcwd() , EOL;
        */
       // Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="01simple.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		
		$objWriter->save('php://output');
		exit;
         

	}
	
	public function indexxAction()
    {
    	if (PHP_SAPI == 'cli')
    		die('This example should only be run from a Web Browser');
    	
    	// Create new PHPExcel object
    	$objPHPExcel = new \PHPExcel();
    	
    	// Set document properties
    	$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
    	->setLastModifiedBy("Maarten Balliauw")
    	->setTitle("Office 2007 XLSX Test Document")
    	->setSubject("Office 2007 XLSX Test Document")
    	->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    	->setKeywords("office 2007 openxml php")
    	->setCategory("Test result file");
    	
    	
    	// Add some data
    	$objPHPExcel->setActiveSheetIndex(0)
    	->setCellValue('A1', 'Hello')
    	->setCellValue('B2', 'world!')
    	->setCellValue('C1', 'Hello')
    	->setCellValue('D2', 'world!');
    	
    	// Miscellaneous glyphs, UTF-8
    	$objPHPExcel->setActiveSheetIndex(0)
    	->setCellValue('A4', 'Miscellaneous glyphs')
    	->setCellValue('A5', 'vaj');
    	
    	// Rename worksheet
    	$objPHPExcel->getActiveSheet()->setTitle('Simple');
    	
    	
    	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    	$objPHPExcel->setActiveSheetIndex(0);
    	
    	
    	// Redirect output to a client’s web browser (Excel5)
    	header('Content-Type: application/vnd.ms-excel');
    	header('Content-Disposition: attachment;filename="01simple.xls"');
    	header('Cache-Control: max-age=0');
    	// If you're serving to IE 9, then the following may be needed
    	header('Cache-Control: max-age=1');
    	
    	// If you're serving to IE over SSL, then the following may be needed
    	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    	header ('Pragma: public'); // HTTP/1.0
    	
    	$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    	$objWriter->save('php://output');
    	exit;
    	
    	return array();
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /index/index/foo
        return array();
    }
}
