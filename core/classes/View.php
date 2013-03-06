<?php
/**
 * 
 * Class View is the basic structure of all the View in MVC Structure.
 * @author Linchew
 *
 */
abstract class View
{
	//General Variable
	protected $vars = array ();
    protected $scripts=array();
    protected $styles=array();
    protected $_scripts=array();
    protected $_styles=array();    
    
    //Abstract Function	
    protected abstract function fetch();
    protected abstract function render();
    protected abstract function init();
    
    //__contruct
    public function __construct(){
    	$this->init();
    }
    
	public function setVar($tpl_var, $value = null)
    {
        if (is_array($tpl_var))
        {
            foreach ($tpl_var as $key => $val)
            {
                if ($key != '')
                {
                    $this->vars[$key] = $val;
                }
            }
        }
        else
        {
            if ($tpl_var != '')
            {
                $this->vars[$tpl_var] = $value;
            }
        }
    }
    
	public function __get($tpl_var)
    {
        return isset($this->vars[$tpl_var]) ? $this->vars[$tpl_var] : NULL;
    }

    public function __set($tpl_var, $value){
    	if ($tpl_var != "")
        {
        	$this->vars[$tpl_var] = $value;
        }
    }
}