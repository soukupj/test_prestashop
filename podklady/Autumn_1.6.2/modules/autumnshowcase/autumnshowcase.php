<?php
/* Autumn Theme - Showcase Module - 2012 - Sercan YEMEN - twitter.com/sercan */
    
if (!defined('_PS_VERSION_'))
	exit;

class AutumnShowcase extends Module{
    
    private $_output = '';
    
    function __construct(){
        $this->name = 'autumnshowcase';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'Sercan YEMEN';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Autumn Theme - Showcase Module');
        $this->description = $this->l('Homepage featured/specials/new products showcases.');
    }
    

/*-------------------------------------------------------------*/
/*  INSTALL THE MODULE
/*-------------------------------------------------------------*/
    
    public function install(){
        if (parent::install() && $this->registerHook('displayHome')){
            $response = Configuration::updateValue('AUTUMN_SHOWCASE_DISPLAYFEATURED', 1);
            $response &= Configuration::updateValue('AUTUMN_SHOWCASE_FEATUREDPRICES', 1);
            $response &= Configuration::updateValue('AUTUMN_SHOWCASE_FEATUREDCART', 0);
            //$response = Configuration::updateValue('AUTUMN_SHOWCASE_FEATUREDCOUNT', '');
            $response &= Configuration::updateValue('AUTUMN_SHOWCASE_DISPLAYNEW', 1);
            $response &= Configuration::updateValue('AUTUMN_SHOWCASE_NEWPRICES', 1);
            $response &= Configuration::updateValue('AUTUMN_SHOWCASE_PRICESCART', 0);
            //$response &= Configuration::updateValue('AUTUMN_SHOWCASE_NEWCOUNT', '');
            $response &= Configuration::updateValue('AUTUMN_SHOWCASE_DISPLAYSPECIAL', 1);
            $response &= Configuration::updateValue('AUTUMN_SHOWCASE_SPECIALPRICES', 1);
            $response &= Configuration::updateValue('AUTUMN_SHOWCASE_SPECIALCART', 0);
            //$response &= Configuration::updateValue('AUTUMN_SHOWCASE_SPECIALCOUNT', '');
            $response &= Configuration::updateValue('AUTUMN_SHOWCASE_AUTOSCROLL', 0);
            $response &= Configuration::updateValue('AUTUMN_SHOWCASE_INTERVAL', 1000);
            return $response;
        }
        return false;
    }
    
    
/*-------------------------------------------------------------*/
/*  UNINSTALL THE MODULE
/*-------------------------------------------------------------*/    
    
    public function uninstall(){
        if (parent::uninstall()){
            $response = Configuration::deleteByName('AUTUMN_SHOWCASE_DISPLAYFEATURED');
            $response &= Configuration::deleteByName('AUTUMN_SHOWCASE_FEATUREDPRICES');
            $response &= Configuration::deleteByName('AUTUMN_SHOWCASE_FEATUREDCART');
            //$response = Configuration::deleteByName('AUTUMN_SHOWCASE_FEATUREDCOUNT');
            $response &= Configuration::deleteByName('AUTUMN_SHOWCASE_DISPLAYNEW');
            $response &= Configuration::deleteByName('AUTUMN_SHOWCASE_NEWPRICES');
            $response &= Configuration::deleteByName('AUTUMN_SHOWCASE_NEWCART');
            //$response &= Configuration::deleteByName('AUTUMN_SHOWCASE_NEWCOUNT');
            $response &= Configuration::deleteByName('AUTUMN_SHOWCASE_DISPLAYSPECIAL');
            $response &= Configuration::deleteByName('AUTUMN_SHOWCASE_SPECIALPRICES');
            $response &= Configuration::deleteByName('AUTUMN_SHOWCASE_SPECIALCART');
            //$response &= Configuration::deleteByName('AUTUMN_SHOWCASE_SPECIALCOUNT');
            $response &= Configuration::deleteByName('AUTUMN_SHOWCASE_AUTOSCROLL');
            $response &= Configuration::deleteByName('AUTUMN_SHOWCASE_INTERVAL');
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
		if (Tools::isSubmit('submitAutumnShowcase'))
		{
                    
                    if(Tools::isSubmit('featured')){
                        Configuration::updateValue('AUTUMN_SHOWCASE_DISPLAYFEATURED', 1);
                    }else{
                        Configuration::updateValue('AUTUMN_SHOWCASE_DISPLAYFEATURED', 0);
                    }
                    
                    if(Tools::isSubmit('featured_prices')){
                        Configuration::updateValue('AUTUMN_SHOWCASE_FEATUREDPRICES', 1);
                    }else{
                        Configuration::updateValue('AUTUMN_SHOWCASE_FEATUREDPRICES', 0);
                    }
                    
                    if(Tools::isSubmit('featured_cart')){
                        Configuration::updateValue('AUTUMN_SHOWCASE_FEATUREDCART', 1);
                    }else{
                        Configuration::updateValue('AUTUMN_SHOWCASE_FEATUREDCART', 0);
                    }
                    
                    
                    
                    
                    if(Tools::isSubmit('new')){
                        Configuration::updateValue('AUTUMN_SHOWCASE_DISPLAYNEW', 1);
                    }else{
                        Configuration::updateValue('AUTUMN_SHOWCASE_DISPLAYNEW', 0);
                    }
                    
                    if(Tools::isSubmit('new_prices')){
                        Configuration::updateValue('AUTUMN_SHOWCASE_NEWPRICES', 1);
                    }else{
                        Configuration::updateValue('AUTUMN_SHOWCASE_NEWPRICES', 0);
                    }
                    
                    if(Tools::isSubmit('new_cart')){
                        Configuration::updateValue('AUTUMN_SHOWCASE_NEWCART', 1);
                    }else{
                        Configuration::updateValue('AUTUMN_SHOWCASE_NEWCART', 0);
                    }
                    
                    
                    
                    
                    if(Tools::isSubmit('special')){
                        Configuration::updateValue('AUTUMN_SHOWCASE_DISPLAYSPECIAL', 1);
                    }else{
                        Configuration::updateValue('AUTUMN_SHOWCASE_DISPLAYSPECIAL', 0);
                    }
                    
                    if(Tools::isSubmit('special_prices')){
                        Configuration::updateValue('AUTUMN_SHOWCASE_SPECIALPRICES', 1);
                    }else{
                        Configuration::updateValue('AUTUMN_SHOWCASE_SPECIALPRICES', 0);
                    }
                    
                    if(Tools::isSubmit('special_cart')){
                        Configuration::updateValue('AUTUMN_SHOWCASE_SPECIALCART', 1);
                    }else{
                        Configuration::updateValue('AUTUMN_SHOWCASE_SPECIALCART', 0);
                    }
                    
                    
                    if(Tools::isSubmit('autoscroll')){
                        Configuration::updateValue('AUTUMN_SHOWCASE_AUTOSCROLL', 1);
                    }else{
                        Configuration::updateValue('AUTUMN_SHOWCASE_AUTOSCROLL', 0);
                    }
                    
                    if(Tools::isSubmit('autoscroll_interval')){
                        if (Validate::isInt(Tools::getValue('autoscroll_interval'))){
                             Configuration::updateValue('AUTUMN_SHOWCASE_INTERVAL', Tools::getValue('autoscroll_interval'));
                        }else{
                            $errors[]=$this->l('Interval must be numeric value');
                        }
                    }
                    
                    
                    if (isset($errors) && sizeof($errors)){
                        $this->_output .= $this->displayError(implode('<br />', $errors));
                    }
                    else{
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
                    
			<fieldset><legend><img src="'.$this->_path.'/assets/img/photo.png" alt="" title="" />'.$this->l('Featured Products').'</legend>
				
                                <div>
                                        <label style="padding:0 0.5em 0 0;">'.$this->l('Show Featured Products').'</label>
                                        <input type="checkbox" name="featured" '.(Configuration::get('AUTUMN_SHOWCASE_DISPLAYFEATURED') ? "checked" : "").'/>
				</div>
                                <br />
                                <div>
                                        <label style="padding:0 0.5em 0 0;">'.$this->l('Show Prices').'</label>
                                        <input type="checkbox" name="featured_prices" '.(Configuration::get('AUTUMN_SHOWCASE_FEATUREDPRICES') ? "checked" : "").'/>
				</div>
                                <br />
                                <div>
                                        <label style="padding:0 0.5em 0 0;">'.$this->l('Show \'Add to Cart\' Button').'</label>
                                        <input type="checkbox" name="featured_cart" '.(Configuration::get('AUTUMN_SHOWCASE_FEATUREDCART') ? "checked" : "").'/>
				</div>
                                
                        </fieldset>
                        
                        <br />
                        
                        <fieldset><legend><img src="'.$this->_path.'/assets/img/photo.png" alt="" title="" />'.$this->l('New Products').'</legend>
                            
                                <div>
                                        <label style="padding:0 0.5em 0 0;">'.$this->l('Show New Products').'</label>
                                        <input type="checkbox" name="new" '.(Configuration::get('AUTUMN_SHOWCASE_DISPLAYNEW') ? "checked" : "").'/>
				</div>
                                <br />
                                <div>
                                        <label style="padding:0 0.5em 0 0;">'.$this->l('Show Prices').'</label>
                                        <input type="checkbox" name="new_prices" '.(Configuration::get('AUTUMN_SHOWCASE_NEWPRICES') ? "checked" : "").'/>
				</div>
                                <br />
                                <div>
                                        <label style="padding:0 0.5em 0 0;">'.$this->l('Show \'Add to Cart\' Button').'</label>
                                        <input type="checkbox" name="new_cart" '.(Configuration::get('AUTUMN_SHOWCASE_NEWCART') ? "checked" : "").'/>
				</div>
                                
                        </fieldset>
                        
                        <br />
                        
			<fieldset><legend><img src="'.$this->_path.'/assets/img/photo.png" alt="" title="" />'.$this->l('Special Products').'</legend>
                                
                                <div>
                                        <label style="padding:0 0.5em 0 0;">'.$this->l('Show Special Products').'</label>
                                        <input type="checkbox" name="special" '.(Configuration::get('AUTUMN_SHOWCASE_DISPLAYSPECIAL') ? "checked" : "").'/>
				</div>          
                                <br />
                                <div>
                                        <label style="padding:0 0.5em 0 0;">'.$this->l('Show Prices').'</label>
                                        <input type="checkbox" name="special_prices" '.(Configuration::get('AUTUMN_SHOWCASE_SPECIALPRICES') ? "checked" : "").'/>
				</div>
                                <br />
                                <div>
                                        <label style="padding:0 0.5em 0 0;">'.$this->l('Show \'Add to Cart\' Button').'</label>
                                        <input type="checkbox" name="special_cart" '.(Configuration::get('AUTUMN_SHOWCASE_SPECIALCART') ? "checked" : "").'/>
				</div>
                                
			</fieldset>
                        
                        <br />
                        
			<fieldset><legend>'.$this->l('Common Options').'</legend>
                                
                                <div>
                                        <label style="padding:0 0.5em 0 0;">'.$this->l('Autoscroll').'</label>
                                        <input type="checkbox" name="autoscroll" '.(Configuration::get('AUTUMN_SHOWCASE_AUTOSCROLL') ? "checked" : "").'/>
				</div>          
                                <br />
                                <div>
                                        <label style="padding:0 0.5em 0 0;">'.$this->l('Autoscroll Interval (milisecond)').'</label>
                                        <input type="text" name="autoscroll_interval" value="'.(Configuration::get('AUTUMN_SHOWCASE_INTERVAL')).'"/>
				</div>
                                                                
			</fieldset>
                        <br />
                        
                        <fieldset>
                            <input type="submit" name="submitAutumnShowcase" value="'.$this->l('Save').'" class="button" />
                        </fieldset>

		</form>';
		return $this->_output;
	}
        

        
/*-------------------------------------------------------------*/
/*  PREPARE FOR HOOK
/*-------------------------------------------------------------*/          

        private function _getFeaturedProducts(){
            $category = new Category(Context::getContext()->shop->getCategory(), (int)Context::getContext()->language->id);
            $number_of_products = 10; //(int)(Configuration::get('AUTUMN_SHOWCASE_FEATUREDCOUNT'));
            $featured_products = $category->getProducts((int)Context::getContext()->language->id, 1, $number_of_products);
            
            $this->smarty->assign(array(
                'featured_products' => $featured_products,
                'featured_prices' => Configuration::get('AUTUMN_SHOWCASE_FEATUREDPRICES'),
                'featured_cart' => Configuration::get('AUTUMN_SHOWCASE_FEATUREDCART'),
                'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
		'homeSize' => Image::getSize(ImageType::getFormatedName('home'))
            ));
                        
        }
            
        
        private function _getNewProducts(){
            $number_of_products = 10; //(int)(Configuration::get('AUTUMN_SHOWCASE_NEWCOUNT'));
            $new_products = Product::getNewProducts((int)Context::getContext()->language->id, 0, $number_of_products);
            
            $this->smarty->assign(array(
                'new_products' => $new_products,
                'new_prices' => Configuration::get('AUTUMN_SHOWCASE_NEWPRICES'),
                'new_cart' => Configuration::get('AUTUMN_SHOWCASE_NEWCART'),
                'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
                'homeSize' => Image::getSize(ImageType::getFormatedName('home'))
            ));
        }
        
        
        private function _getSpecialProducts(){
            $number_of_products = 10; //(int)(Configuration::get('AUTUMN_SHOWCASE_SPECIALCOUNT'));
            $special_products = Product::getPricesDrop((int)Context::getContext()->language->id, 0, $number_of_products);
            
            $this->smarty->assign(array(
                'special_products' => $special_products,
                'special_prices' => Configuration::get('AUTUMN_SHOWCASE_SPECIALPRICES'),
                'special_cart' => Configuration::get('AUTUMN_SHOWCASE_SPECIALCART'),
                'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
                'homeSize' => Image::getSize(ImageType::getFormatedName('home'))
            ));

        }

        
        
/*-------------------------------------------------------------*/
/*  PREPARE FOR HOOK
/*-------------------------------------------------------------*/          

        private function _prepHook($params){
            $showcases = array(
                'featured' => Configuration::get('AUTUMN_SHOWCASE_DISPLAYFEATURED'),
                'new' => Configuration::get('AUTUMN_SHOWCASE_DISPLAYNEW'),
                'special' => Configuration::get('AUTUMN_SHOWCASE_DISPLAYSPECIAL'),
                'autoscroll' => Configuration::get('AUTUMN_SHOWCASE_AUTOSCROLL'),
                'autoscrollInterval' => Configuration::get('AUTUMN_SHOWCASE_INTERVAL')
            );
            
            $this->smarty->assign('showcases', $showcases);
            
            
            if(Configuration::get('AUTUMN_SHOWCASE_DISPLAYFEATURED')){
                $this->_getFeaturedProducts();
            }
            
            if(Configuration::get('AUTUMN_SHOWCASE_DISPLAYNEW')){
                $this->_getNewProducts();
            }
            
            if(Configuration::get('AUTUMN_SHOWCASE_DISPLAYSPECIAL')){
                $this->_getSpecialProducts();
            }
            
            $this->context->controller->addCSS(($this->_path).'assets/autumnshowcase.css', 'all');
            $this->context->controller->addJqueryPlugin('autumnshowcase', $this->_path.'assets/');
            
            return true;
        }
        
        
/*-------------------------------------------------------------*/
/*  HOOK (displayHeader)
/*-------------------------------------------------------------*/
        
        public function hookDisplayHome ($params){
            $this->_prepHook($params);
            return $this->display(__FILE__, 'autumnshowcase.tpl');
        }
        
}