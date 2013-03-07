<?php
/**
 * 
 * Base Control is main for the 404, and index, some critical page
 * @author Linchew
 *
 */
class BaseController extends WebController{
	
	protected function action_index(){
		
		$base=new BaseModel();

		echo "<br/>";
		echo "<br/>";
		echo "a";
		
		$_userlist=$base->getUserList();
		print_r($_userlist);
		
		echo "<br/>";
		echo "<br/>";
		echo "b";
		
		$_user=$base->getUserById(1);		
		print_r($_user);
		
		$this->render("");
	}
	
	protected function action_404(){
		
	}
}