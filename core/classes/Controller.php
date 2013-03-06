<?php
/**
 * 
 * Controller is the base abstract function for all Other Controller, 
 * WebController always inherit Controller.
 * 
 * @author Linchew
 *
 */
abstract class Controller{
	//General Variable
	protected $action 	= "action_index";
	protected $Request;
	
	//Abstract Function
	protected abstract function action_index();
	protected abstract function init();
	
	//_contruct()
	function __construct($Request){
		//init Variables
		$this->Request=$Request;
		
		//Execute self-definition init()
    	$this->init();
	}

	//public final function
	public final function run($action="")
	{
		if($action==""){
			$this->{$this->action}();
		}else{
			//setup action_base+action = "action_"+$action
			$actionBase="action_";
			$action=$actionBase.$action;
			
			if (method_exists($this, $action)){
				$this->{$action}();
			}else{
				$this->showError(101);
			}
		}
	}
	
	public final function showError($code,$msg=""){
		throw new Exception($msg, $code);
	}
}