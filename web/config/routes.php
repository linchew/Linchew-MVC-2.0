<?php

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


