<?php
/**
 * 
 * Base Control is main for the 404, and index, some critical page
 * @author Linchew
 *
 */
class BaseController extends WebController{
	
	protected function action_index(){
		$this->render("");
	}
	
	protected function action_404(){
		
	}
}