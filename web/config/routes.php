<?php
/*
 * *Sepcial charactor & mapping
 *
 * *System mapping
 * (:ACTION|action) => means any charactors for :ACTION will be derect into $action variable in $Router->action
 *
 * *Other mapping
 * (:DAY|day) 		=> mapping from URL :DAY map into $day => apear into $Request->get[day]
 * (:MONTH|month)	=> mapping from URL :MONTH map into $month => apear into $Request->get[month]
 * 
 * Some rules are providing below
 * (:ACTION)	=> any charactor, system use!
 * (:DAY)		=> 2 digits
 * (:MONTH)		=> 2 digits
 * (:YEAR)		=> 4 digits
 * (:NUMERIC)	=> any digits
 * (:ANY)		=> any charactor
 * (:DOUBLE)	=> digits in DOUBLE form
*/
return array(
	'root'  											=> 	'Base/index',  // The default route
	'404'   											=> 	'Base/404',    // The main 404 route

	'auth/(:ACTION|action)'								=> 	'Auth',
	'main'												=>	'Reserve/show_today_list',
	'main/today'										=>	'Reserve/show_today_list',
	'main/(:DAY|day)/(:MONTH|month)/(:YEAR|year)'		=>	'Reserve/show_day_list',
	'reserve'											=>	'Reserve/reserve',
	'reserve/(:NUMERIC|id)'								=>	'Reserve/show_reservation',
	'print/(:DAY|day)/(:MONTH|month)/(:YEAR|year)'		=>	'Reserve/print_day_list'
);


