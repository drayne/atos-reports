<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
/*
 * plugin za stampu inline skripti unutar layout fajlova
 */
class LayoutScriptPlugin extends AbstractPlugin
{
	public function procitajScript($nazivStranice)
	{
		switch ($nazivStranice) {
			case 'zakljucenePolise': //ipak odradjeno sa klasicnim datepickerom pa se ne koristi. ostavljeno radi primjera
				return "
						<script>
						//Date range picker
						 $('.input-daterange-datepicker').daterangepicker({
						     buttonClasses: ['btn', 'btn-sm'],
						     applyClass: 'btn-default',
						     cancelClass: 'btn-primary'
						 });
						
						
						 $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
						
						 $('#reportrange').daterangepicker({
						     format: 'DD.MM.YYYY',
						     startDate: moment().subtract(29, 'days'),
						     endDate: moment(),
						     minDate: '01.01.2016',
						     maxDate: '12.31.2016',
						     dateLimit: {
						         days: 60
						     },
						     showDropdowns: true,
						     showWeekNumbers: true,
						     timePicker: false,
						     timePickerIncrement: 1,
						     timePicker12Hour: true,
						     ranges: {
						         'Today': [moment(), moment()],
						         'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						         'Last 7 Days': [moment().subtract(6, 'days'), moment()],
						         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						         'This Month': [moment().startOf('month'), moment().endOf('month')],
						         'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
						     },
						     opens: 'left',
						     drops: 'down',
						     buttonClasses: ['btn', 'btn-sm'],
						     applyClass: 'btn-success',
						     cancelClass: 'btn-default',
						     separator: ' to ',
						     locale: {
						         applyLabel: 'Submit',
						         cancelLabel: 'Cancel',
						         fromLabel: 'From',
						         toLabel: 'To',
						         customRangeLabel: 'Custom',
						         daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
						         monthNames: ['Januar', 'Februar', 'Marct', 'April', 'Maj', 'Jun', 'Jul', 'Avgust', 'Septembar', 'Oktobar', 'Novembar', 'Decembar'],
						         firstDay: 1
						     }
						 }, function (start, end, label) {
						     console.log(start.toISOString(), end.toISOString(), label);
						     $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
						 });
						
						 </script>";
				break;
			
			default: return '';
			break;
		}
	}
	
}

?>