<?php

/**
 * 
 */
class Payroll
{
	
	private $year;
	function __construct()
	{
		$stdin = fopen('php://stdin', 'r');
		echo "Please enter year:";
		$this->year = fgets($stdin);

	}

	function calculate() {
		$data[] = array('Month', 'Salary Date', 'Bonus Date');
		$month = 1;		
		$year = (int) $this->year;
		$weekend = array(0, 6); // 0-Sat, 6-Sun.
		while ($month <= 12) {
			$date = cal_days_in_month(CAL_GREGORIAN, $month, $year);
			$date = $year.'-'.$month.'-'.$date;
			$bonusDate = $year.'-'.$month.'-15';
			$day = date('w', strtotime($date));
			$bonusDay = date('w', strtotime($bonusDate));
			if( in_array($day, $weekend) ){
				$date = $this->getWeekDate($date, $day, 'Salary');				
			}
			if( in_array($bonusDay, $weekend) ){
				$bonusDate = $this->getWeekDate($bonusDate, $bonusDay, 'Bonus');				
			}
			
			$data[] = array(date('M', strtotime($date)), $date, $bonusDate);
			$month++;
		}
		$this->downloadData($data);
	}

	function getWeekDate($date, $day, $type) {
		if ( $day == 0 && $type == 'Salary' ) {
			$date = date('Y-m-d', strtotime("-2 day", strtotime($date)));
		} elseif ( $day == 6 && $type == 'Salary' ) {
			$date = date('Y-m-d', strtotime("-1 day", strtotime($date)));
		} elseif ( $day == 0 && $type == 'Bonus' ) {
			$date = date('Y-m-d', strtotime("+3 day", strtotime($date)));
		} elseif ( $day == 6 && $type == 'Bonus' ) {
			$date = date('Y-m-d', strtotime("+4 day", strtotime($date)));
		}
		return $date;
	}

	function downloadData($list){
			$file = fopen("Payroll.csv","w");

			foreach ($list as $line) {
			  fputcsv($file, $line);
			}

		fclose($file);
	}
}

$obj = New Payroll();
$obj->calculate();

?>