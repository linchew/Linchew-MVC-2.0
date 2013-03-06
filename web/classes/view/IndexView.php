<?php

class IndexView extends WebView
{
	var $base_path="IndexView_base";
	
	protected function init(){
		
	}
	
	public function fetch()
	{
		$args = func_get_args();
		$_template = $args[0];
		
        // Start Fetching web contain and put into buffer       
        $html = "";        
        ob_start();
        include WEBPATH_TEMPLATE . $this->base_path. '/Html.head.tpl.php';
        include WEBPATH_TEMPLATE . $this->base_path. '/Html.body.header.tpl.php';
        include WEBPATH_TEMPLATE . $this->base_path. '/Html.body.body.tpl.php';
        
        //check if the file exist
        if($_template!="" && file_exists(WEBPATH_TEMPLATE.$this->base_path.$_template)){
        	include WEBPATH_TEMPLATE.$this->base_path.$_template;
        }
        
        include WEBPATH_TEMPLATE . $this->base_path. '/Html.body.footer.tpl.php';
        $html = ob_get_contents();
        ob_end_clean();
        
        // Return html
        return $html;
    }

	public function render()
    {
    	$args = func_get_args();
    	$template_filename = $args[0];
    	
    	//set header
    	//---
    		header("Cache-Control: no-cache");
    		header('Content-Type: text/html; charset=utf-8');
	    //---
    	
    	echo $this->fetch($template_filename);
    }
}