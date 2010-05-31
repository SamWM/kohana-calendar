<?php defined('SYSPATH') or die('No direct script access.');
class Calendar {
	private $supplied_date;

	public function __construct($date = null) {
		$this->supplied_date = Date::today_if_null($date);
	}

	public function render($day_render = null, $caption_render = null) {
		if($day_render == null)
		{
			$day_render = create_function('$day', 'return date(\'d\', $day);');
		}
		if($caption_render == null)
		{
			$caption_render = create_function('$date', 'return date(\'F Y\', $date);');
		}

		$output = '';
		$output.= '<table class="calendar"><caption>'.call_user_func($caption_render, $this->supplied_date).'</caption><thead><th>';
		$output.= implode('</th><th>', Date::week_days($this->supplied_date));
		$output.= '</th></thead><tbody>';

		$start_of_month = Date::start_of_month($this->supplied_date);
		
		$end_of_month = Date::end_of_month($this->supplied_date);

		$firstday = Date::start_of_week($start_of_month);
		$lastday = Date::end_of_week($end_of_month);

		$day = $firstday;
		$class = '';
		$day_counter = 1;
		while( $day < $lastday ) {
			$class = 'd'.date('j', $day).' m'.date('n', $day).' y'.date('Y', $day).' w'.date('W', $day);

			if($day < $start_of_month) $class.= ' lastmonth';
			else if($day > $end_of_month) $class.=' nextmonth';
			else $class.= ' thismonth';
			if(date('j n Y', time()) == date('j n Y', $day)) $class.= ' today';
			if($day_counter % 7 == 1) {
				$output.= '<tr>';
			}
			$output.= '<td class="'.$class.'">'.call_user_func($day_render, $day).'</td>';
			if($day_counter % 7 == 0) {
				$output.= '</tr>';
			}
			$day = $day + Date::DAY;
			$day_counter++;
		}

		$output.= '</tbody></table>';

		return $output;
	}
}
?>
