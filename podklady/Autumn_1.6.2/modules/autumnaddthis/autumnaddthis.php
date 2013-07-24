<?php
/* Autumn Theme - AddThis Module - 2012 - Sercan YEMEN - twitter.com/sercan */
    
if (!defined('_PS_VERSION_'))
	exit;

class AutumnAddthis extends Module{
    
    private $_output = '';
    
    function __construct(){
        $this->name = 'autumnaddthis';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'Sercan YEMEN';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Autumn Theme - AddThis Module');
        $this->description = $this->l('Displays AddThis social sharing plugin in product pages.');
    }
    

/*-------------------------------------------------------------*/
/*  INSTALL THE MODULE
/*-------------------------------------------------------------*/
    
    public function install(){
        if (parent::install() && $this->registerHook('displayHeader')){
            $response = Configuration::updateValue('AUTUMN_ADD_THIS_PUBID', '');
            return $response;
        }
        return false;
    }
    
    
/*-------------------------------------------------------------*/
/*  UNINSTALL THE MODULE
/*-------------------------------------------------------------*/    
    
    public function uninstall(){
        if (parent::uninstall()){
            $response = Configuration::deleteByName('AUTUMN_ADD_THIS_PUBID');
            return $response;
        }
        return false;
    }    
    
    
    
/*-------------------------------------------------------------*/
/*  MODUL INITIALIZE & FORM SUBMIT CHECKs
/*-------------------------------------------------------------*/    
    
        
    public function getContent()
	{
		$this->_output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitAutumnAddthis'))
		{
                    
                    if (Tools::isSubmit('pubid') && Tools::getValue('pubid') != "" ){
                        Configuration::updateValue('AUTUMN_ADD_THIS_PUBID', Tools::getValue('pubid'));
                    }else{
                        $errors[] = $this->l('An error occured. Please check your ProfileID and try again.');
                    }                 
                                                
                    if (isset($errors) && sizeof($errors)){
                        $this->_output .= $this->displayError(implode('<br />', $errors));
                    }else{
                        $this->_output .= $this->displayConfirmation($this->l('Configuration Saved!'));
                    }
                        
		}
		return $this->_output.$this->displayForm();
	}


/*-------------------------------------------------------------*/
/*  DISPLAY CONFIGURATION FORM
/*-------------------------------------------------------------*/    
                
	public function displayForm()
	{            
                $this->_output = '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset><legend><img src="'.$this->_path.'/logo.gif" alt="" title="" />'.$this->l('AddThis').'</legend>
				
                                <div class="margin-form">
                                    <p class="clear">'.$this->l('You can get your ProfileID from addthis.com').'</p>
                                </div>


                                <label>'.$this->l('ProfileID').'</label>
				<div class="margin-form">
					<input type="text" name="pubid" value="'.Tools::safeOutput(Configuration::get('AUTUMN_ADD_THIS_PUBID')).'" />
					<p class="clear">'.$this->l('Profile ID - Format: xx-xxxxxxxxxxxxxxxx').'</p>
				</div>
                                
				<center><input type="submit" name="submitAutumnAddthis" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>';
		return $this->_output;
	}
        
        
/*-------------------------------------------------------------*/
/*  PREPARE FOR HOOK
/*-------------------------------------------------------------*/          

        private function _prepHook($params){
                       
            $pubid = Configuration::get('AUTUMN_ADD_THIS_PUBID');
            
            $this->smarty->assign('pubid', $pubid);
            $addThisRender = $this->display(__FILE__, 'autumnaddthis.tpl');
            
            $this->smarty->assignGlobal('addThisRender', $addThisRender);
            
        }
        
        
/*-------------------------------------------------------------*/
/*  HOOK (displayHeader)
/*-------------------------------------------------------------*/
        
        public function hookDisplayHeader ($params){
            $this->_prepHook($params);            
        }
        
}