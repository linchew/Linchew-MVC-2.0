<?php
class Request{
	
	public $get;
	public $post;
	public $file;
	
	public function __construct(){
		$this->get=array();
		$this->post=array();
		$this->file=array();
		
		//handle all the variable;
		$this->handleRequest();
	}
	
	public function isGET(){
		if(count($this->get)>0) return true;
		return false;
	}
	
	public function isPOST(){
		if(count($this->post)>0) return true;
		return false;
	}
	
	public function isFILE(){
		if(count($this->file)>0) return true;
		return false;;
	}
	
	public function add_from_router_request($request){
		foreach($request as $key=>$value){
			//if the GET already exist, then throw Exception
			if(isset($this->get[$key])){
				throw new Exception("", 110, $previous);
			}
			//store into Request->get
			$this->get[$key]=$value;
		}
	}
	
	private function handleRequest(){
		//handle GET
		foreach($_GET as $name=>$value){
			$this->get[$name]=$value;
		}
		
		//handle POST
		foreach($_POST as $name=>$value){
			$this->post[$name]=$value;	
		}
		
		//handle FILES
		foreach($_FILES as $name=>$value){
			$this->file[$name]=$value;	
		}
	}
}