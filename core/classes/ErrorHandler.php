<?php
class ErrorHandler{
	
	private $error_code="";
	private $error_type="";
	private $msg_content="";
	private $msg_title="";
	
	public function showError($code, $message=""){

		//data mapping
		$this->msg_content=$message;
		$this->error_code=$code;				

		//match with error code
		switch ($code)
		{
			case $code<200:
				$this->error_type="ERROR_SYS";
				//10* => System
				if($code==100) $this->msg_title="System Error";
				if($code==101) $this->msg_title="Invalid Path";
				if($code==103) $this->msg_title="SQL Error";
				//11* => Request
				if($code==110) $this->msg_title="Request Error";
				
				break;
			case $code<300:
				$this->error_type="ERROR_AUTH";				
				if($code==200) $this->msg_title="Auth Error";				
				//21* => Login
				if($code==210) $this->msg_title="Login Error";
				//22* => Registration
				if($code==220) $this->msg_title="Register Error";
				
				break;
			case $code<400:
				$this->error_type="ERROR_INPUT";
				if($code==300) $this->msg_title="Insertion Error";
				
				break;
			case $code<500:
				$this->error_type="ERROR_OUTPUT";
				if($code==400) $this->msg_title="Output Data Error";
								
				break;
			default:
		}
		
		//check input type to output
		if(Input::is_ajax()){
			$this->json_output();
		}else{
			$this->html_output();
		}
	}
	
	public function html_output(){
		Router::redirectTo404(
			$this->error_code, 
			$this->error_type, 
			$this->msg_title.":".$this->msg_content);
	}
	
	public function json_output(){
		respJASON::showError(
			$this->error_code, 
			$this->error_type, 
			$this->msg_title.":".$this->msg_content);
	}
	
	/**
	 * 
	 * redirect to error page
	 * @param $url
	 */
	public function redirectTo($code,$type,$message)
	{
		header("Location: 404.php?code=$code&type=$type&msg=".urlencode($message));
		exit();
	}
}