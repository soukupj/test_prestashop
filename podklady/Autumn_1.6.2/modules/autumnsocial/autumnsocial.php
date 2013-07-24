<?php
/* Autumn Theme - Social Links Module - 2012 - Sercan YEMEN - twitter.com/sercan */
    
if (!defined('_PS_VERSION_'))
	exit;

class AutumnSocial extends Module{
    
    private $_output = '';
    
    function __construct(){
        $this->name = 'autumnsocial';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'Sercan YEMEN';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Autumn Theme - Social Links Module');
        $this->description = $this->l('Displays social network links.');
    }
    

/*-------------------------------------------------------------*/
/*  INSTALL THE MODULE
/*-------------------------------------------------------------*/
    
    public function install(){
        if (parent::install() && $this->registerHook('displayHeader')){
            $response = Configuration::updateValue('AUTUMN_SOCIAL_MAIL', '');
            $response &= Configuration::updateValue('AUTUMN_SOCIAL_FACEBOOK', '');
            $response &= Configuration::updateValue('AUTUMN_SOCIAL_TWITTER', '');
            $response &= Configuration::updateValue('AUTUMN_SOCIAL_GOOGLE', '');
            $response &= Configuration::updateValue('AUTUMN_SOCIAL_PINTEREST', '');
            $response &= Configuration::updateValue('AUTUMN_SOCIAL_SKYPE', '');
            $response &= $this->createTables();
            return $response;
        }
        return false;
    }
    
    
/*-------------------------------------------------------------*/
/*  UNINSTALL THE MODULE
/*-------------------------------------------------------------*/    
    
    public function uninstall(){
        if (parent::uninstall()){
            $response = Configuration::deleteByName('AUTUMN_SOCIAL_MAIL');
            $response &= Configuration::deleteByName('AUTUMN_SOCIAL_FACEBOOK');
            $response &= Configuration::deleteByName('AUTUMN_SOCIAL_TWITTER');
            $response &= Configuration::deleteByName('AUTUMN_SOCIAL_GOOGLE');
            $response &= Configuration::deleteByName('AUTUMN_SOCIAL_PINTEREST');
            $response &= Configuration::deleteByName('AUTUMN_SOCIAL_SKYPE');
            $response &= $this->deleteTables();
            return $response;
        }
        return false;
    }    
    
    
/*-------------------------------------------------------------*/
/*  CREATE THE TABLES
/*-------------------------------------------------------------*/  
    
    protected function createTables(){
           
            $response = (bool)Db::getInstance()->execute('
                    CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'social_links` (
                            `id_link` int(10) unsigned NOT NULL AUTO_INCREMENT,
                            `name` varchar(255) NOT NULL DEFAULT \'\',
                            `link` varchar(255) NOT NULL DEFAULT \'\',
                            `id_shop` int(10) unsigned NOT NULL,
                            PRIMARY KEY (`id_link`)
                    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');

            return $response;
            
    }


/*-------------------------------------------------------------*/
/*  DELETE THE TABLES
/*-------------------------------------------------------------*/  
    
	protected function deleteTables(){
            return Db::getInstance()->execute('
                    DROP TABLE IF EXISTS `'._DB_PREFIX_.'social_links`;
            ');
	}
    
    
        
    
/*-------------------------------------------------------------*/
/*  MODUL INITIALIZE & FORM SUBMIT CHECKs
/*-------------------------------------------------------------*/    
    
        
    public function getContent(){
        
		$this->_output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitAutumnSocial')){
                    
                    if (Tools::isSubmit('mail') && Tools::getValue('mail') != "" ){
                            if (Validate::isEmail(Tools::getValue('mail'))){
                                Configuration::updateValue('AUTUMN_SOCIAL_MAIL', Tools::getValue('mail'));
                            }
                            else{
                                $errors[] = $this->l('Invalid E-Mail Address');
                            }
                        }
                        
                        if(Tools::isSubmit('facebook')){
                            Configuration::updateValue('AUTUMN_SOCIAL_FACEBOOK', Tools::getValue('facebook'));
                        }
                        
                        if(Tools::isSubmit('twitter')){
                            Configuration::updateValue('AUTUMN_SOCIAL_TWITTER', Tools::getValue('twitter'));
                        }
                        
                        if(Tools::isSubmit('google')){
                            Configuration::updateValue('AUTUMN_SOCIAL_GOOGLE', Tools::getValue('google'));
                        }
                        
                        if(Tools::isSubmit('pinterest')){
                            Configuration::updateValue('AUTUMN_SOCIAL_PINTEREST', Tools::getValue('pinterest'));
                        }
                        
                        if(Tools::isSubmit('skype')){
                            Configuration::updateValue('AUTUMN_SOCIAL_SKYPE', Tools::getValue('skype'));
                        }
                        
                        if (isset($errors) && sizeof($errors)){
                            $this->_output .= $this->displayError(implode('<br />', $errors));
                        }
                        else{
                            $this->_output .= $this->displayConfirmation($this->l('Configuration Saved!'));
                        }
                        
		}
                
                
                elseif (Tools::isSubmit('saveLink')){
                    $errors = array();
                    
                    if (Tools::getValue('name') == ""){
                        $errors[] = $this->displayError($this->l('Please fill the "Name" field.'));
                    }
                    if (Tools::getValue('link') == ""){
                        $errors[] = $this->displayError($this->l('Please fill the "Link" field.'));
                    }
            
                    if (count($errors)){
                        $this->_output .= implode('<br />', $errors);
                        return $this->_output.$this->displayAddLinkForm();
                    }
                    
                    $this->_output .= $this->_saveLink();
                    return $this->_output.$this->displayForm();
                }
                
                
                elseif (Tools::isSubmit('updateLink')){
                    $errors = array();
                    
                    if (Tools::getValue('name') == ""){
                        $errors[] = $this->displayError($this->l('Please fill the "Name" field.'));
                    }
                    if (Tools::getValue('link') == ""){
                        $errors[] = $this->displayError($this->l('Please fill the "Link" field.'));
                    }
                    
                    if (count($errors)){
                        $this->_output .= implode('<br />', $errors);
                        if (Tools::isSubmit('editLink')){
                            return $this->_output.$this->displayAddLinkForm(Tools::getValue('editLink'));
                        }else{
                            return $this->_output.$this->displayAddLinkForm();
                        }
                    }
                    
                    $this->_output .= $this->_updateLink(Tools::getValue('editLink'), Tools::getValue('name'), Tools::getValue('link'));
                    return $this->_output.$this->displayForm();
                }
                
                elseif (Tools::isSubmit('delLink')){
                    
                    $this->_output .= $this->_deleteLink(Tools::getValue('delLink'));
                    return $this->_output.$this->displayForm();
                }
                
                elseif (Tools::isSubmit('addLink')){
                    return $this->_output.$this->displayAddLinkForm();
                }
                elseif (Tools::isSubmit('editLink')){
                    return $this->_output.$this->displayAddLinkForm(Tools::getValue('editLink'));
                }
                
                
		return $this->_output.$this->displayForm();
	}


/*-------------------------------------------------------------*/
/*  DISPLAY CONFIGURATION FORM
/*-------------------------------------------------------------*/    
                
	public function displayForm()
	{            
                $this->_output = '
                    
                <style>
                li.custom-social-link{
                    padding:5px 10px;
                    background:#ffffff;
                    border:none;

                    box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                    -moz-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                    -webkit-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;

                    float:left;
                    clear:both;
                    display:block;
                    margin:10px 10px 10px 0;
                }

                a.addLink{
                    display:block;
                    float:left;
                    clear:both;
                    padding:5px 10px;
                    background:#ffffff;
                    border:none;

                    box-shadow: 0 0 0 1.5px rgba(0,0,0,0.25) , 0 1.5px 1.5px 0 rgba(0,0,0,0.25), 0 2px 1.5px 0 rgba(0, 0, 0, 0.15) ;
                    -moz-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.25) , 0 1.5px 1.5px 0 rgba(0,0,0,0.25), 0 2px 1.5px 0 rgba(0, 0, 0, 0.15) ;
                    -webkit-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.25) , 0 1.5px 1.5px 0 rgba(0,0,0,0.25), 0 2px 1.5px 0 rgba(0, 0, 0, 0.15) ;

                    margin:10px 10px 10px 0;
                }
                </style>
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset><legend><img src="'.$this->_path.'/img/world.png" alt="" title="" />'.$this->l('Pre-defined Social Links').'</legend>
				
                                <div class="margin-form">
                                    <p class="clear">'.$this->l('Do not include web site addresses (www.facebook.com etc.) in social links. If you want to disable one of the social links just leave it empty.').'</p>
                                </div>


                                <label>'.$this->l('Mail').'</label>
				<div class="margin-form">
					<input type="text" name="mail" value="'.Tools::safeOutput(Configuration::get('AUTUMN_SOCIAL_MAIL')).'" />
					<p class="clear">'.$this->l('Email address').'</p>
				</div>
                                
                                <label>'.$this->l('Facebook').'</label>
				<div class="margin-form">
					<input type="text" name="facebook" value="'.Tools::safeOutput(Configuration::get('AUTUMN_SOCIAL_FACEBOOK')).'" />
					<p class="clear">'.$this->l('facebook.com/[value]').'</p>
				</div>
                                
                                
                                <label>'.$this->l('Twitter').'</label>
				<div class="margin-form">
					<input type="text" name="twitter" value="'.Tools::safeOutput(Configuration::get('AUTUMN_SOCIAL_TWITTER')).'" />
					<p class="clear">'.$this->l('twitter.com/[value]').'</p>
				</div>
                                
                                
                                <label>'.$this->l('Google').'</label>
				<div class="margin-form">
					<input type="text" name="google" value="'.Tools::safeOutput(Configuration::get('AUTUMN_SOCIAL_GOOGLE')).'" />
					<p class="clear">'.$this->l('plus.google.com/u/0/[value]').'</p>
				</div>
                                

                                <label>'.$this->l('Pinterest').'</label>
				<div class="margin-form">
					<input type="text" name="pinterest" value="'.Tools::safeOutput(Configuration::get('AUTUMN_SOCIAL_PINTEREST')).'" />
					<p class="clear">'.$this->l('pinterest.com/[value]').'</p>
				</div>
                                

                                <label>'.$this->l('Skype').'</label>
				<div class="margin-form">
					<input type="text" name="skype" value="'.Tools::safeOutput(Configuration::get('AUTUMN_SOCIAL_SKYPE')).'" />
					<p class="clear">'.$this->l('Skype Username').'</p>
				</div>
                                                                
                                
				<center><input type="submit" name="submitAutumnSocial" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
                        
                        <br />
                        
                        <fieldset><legend><img src="'.$this->_path.'/img/world.png" alt="" title="" />'.$this->l('Custom Social Links').'</legend>                        
                        
                        
                       <div class="margin-form">
                                    <p class="clear">'.$this->l('Currently there is not any upload functionality for custom icons. You need to do it manually. You can find a template file (PSD file) for the icons in the zip file
                                        that you downloaded from themeforest. Just create your own icon, save it as a *.png file (to keep the transparency) and put into "thems/autumn/img/autumn/custom_social_icons/" folder.
                                        Icon files and links must have the same name. I.E. If you created a custom social link named "linkedin" you must save your icon file as "linkedin.png". Social link names does not appear
                                        anywhere on the website so you do not have to be careful about them.').'</p>
                                </div>';
                        
                
                        $links = $this->_getLinks($this->context->shop->id);
                        if ($links){
                            $this->_output .= ' <div class="margin-form"><ul>';
                                foreach ($links as $link){
                                    
                                    $this->_output .= '<li class="custom-social-link">';
                                    $this->_output .= $link['name'];
                                    $this->_output .= '</b></a>';
                                    $this->_output .= ' - ';
                                    $this->_output .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&editLink='.$link['id_link'].'"><img src="'.$this->_path.'/img/pencil.png" alt="" title="'.$this->l('Edit link').'" /></a>';
                                    $this->_output .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&delLink='.$link['id_link'].'" onclick="return confirm(\''.$this->l('Are you sure you want to delete this link?').'\')"><img src="'.$this->_path.'/img/del.png" alt="" title="'.$this->l('Delete link').'" /></a>';
                                    $this->_output .= '</li>';
                                    
                                }
                             $this->_output .= '</ul></div>';
                        }
			 
                        $this->_output .= '
                                <br />
                                <div class="margin-form">
					<a class="addLink" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&addLink"><b>'.$this->l('Add Custom Social Link').'</b></a>
				</div>
                                                                
                         </fieldset>   
		</form>';
		return $this->_output;
	}
        

/*-------------------------------------------------------------*/
/*  DISPLAY CUSTOM LINK ADD FORM
/*-------------------------------------------------------------*/    
                
	public function displayAddLinkForm($id_link = NULL){
            
            //BLANK FORM
            if ($id_link == NULL){
                
                $this->_output = '
                    <form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
                        <fieldset><legend><img src="'.$this->_path.'/img/world.png" alt="" title="" />'.$this->l('Add Custom Link').'</legend>
                            
                            <label>'.$this->l('Name').'</label>
                            <div class="margin-form">
                                <input type="text" name="name" id="name" />
                                <p class="clear">'.$this->l('eg. youtube').'</p>
                            </div>
                            
                            <label>'.$this->l('Link').'</label>
                            <div class="margin-form">
                                <input type="text" name="link" id="link" />
                                <p class="clear">'.$this->l('eg. http://www.youtube.com/user/USERNAME').'</p>
                            </div>

                            <div class="margin-form" style="clear:both;float:left;margin-right:15px;margin-top:20px;">
                               <input type="submit" name="saveLink" id="saveLink" class="button" value="'.$this->l('Save').'">
                            </div>

                            <div class="margin-form" style="margin-top:23px;"><a style="font-weight:bold;text-decoration:underline;" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'">Cancel</a></div>

                        </fieldset>
                    </form>

                ';
                
                return $this->_output;
            
            }

            //EDIT FORM
            else{
                if ($this->_isLinkAvailable($id_link)){
                    $link = $this->_getLinks($this->context->shop->id, $id_link);
                    
                    $this->_output = '
                    <form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
                        <fieldset><legend><img src="'.$this->_path.'/img/world.png" alt="" title="" />'.$this->l('Edit Custom Link').'</legend>
                            
                            <label>'.$this->l('Name').'</label>
                            <div class="margin-form">
                                <input type="text" name="name" id="name" value="'.$link[0]['name'].'" />
                                <p class="clear">'.$this->l('eg. youtube').'</p>
                            </div>
                            
                            <label>'.$this->l('Link').'</label>
                            <div class="margin-form">
                                <input type="text" name="link" id="link" value="'.$link[0]['link'].'" />
                                <p class="clear">'.$this->l('eg. http://www.youtube.com/user/USERNAME').'</p>
                            </div>

                            <div class="margin-form" style="clear:both;float:left;margin-right:15px;margin-top:20px;">
                               <input type="submit" name="updateLink" id="updateLink" class="button" value="'.$this->l('Save').'">
                            </div>

                            <div class="margin-form" style="margin-top:23px;"><a style="font-weight:bold;text-decoration:underline;" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'">Cancel</a></div>

                        </fieldset>
                    </form>

                ';
                
                return $this->_output;
                    
                    
                }
            }
            
            
               
        }


        
/*-------------------------------------------------------------*/
/*  GET LINKS TO THE DB
/*-------------------------------------------------------------*/          

        private function _getLinks($id_shop, $id_link = false){
            $sql = 'SELECT * FROM `'._DB_PREFIX_.'social_links`
                    WHERE `id_shop` = '.$id_shop;
            
            if ($id_link){
                $sql .= ' AND `id_link` = '.$id_link;
            }
            
            $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
	
            if ($response){
                return $response;
            }
            else{
                return NULL;
            }
        }
        
/*-------------------------------------------------------------*/
/*  SAVE THE LINK TO THE DB
/*-------------------------------------------------------------*/          

        private function _saveLink(){
                        
            $name = Tools::getValue('name');
            $link = Tools::getValue('link');
            
            //ADD
            $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                        INSERT INTO `'._DB_PREFIX_.'social_links`
                        (`name`,`link`, `id_shop`)
                        VALUES ("'.$name.'","'.$link.'",'.$this->context->shop->id.')
                        ');
            
            if ($response){
                return $this->displayConfirmation($this->l("Link Saved!"));
            }else{
                return  $this->displayError($this->l("An error occured while adding the link to the DB"));
            }
            
        }
        
        
/*-------------------------------------------------------------*/
/*  UPDATE THE LINK
/*-------------------------------------------------------------*/          

        private function _updateLink($id_link, $name, $link){
            $errors = array();
            
            if ($this->_isLinkAvailable($id_link)){
            
                if (!$name){
                    $errors[] = $this->displayError($this->l('Fill the "Name" field.'));
                }
                if (!$link){
                    $errors[] = $this->displayError($this->l('Fill the "Link" field.'));
                }

                if (count($errors)){
                    return implode('<br />', $errors);
                }

                $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                            UPDATE '._DB_PREFIX_.'social_links
                            SET `name` = "'.$name.'",
                            `link` = "'.$link.'"
                            WHERE `id_link` = '.$id_link
                            );

                if ($response){
                    return $this->displayConfirmation($this->l("Link Updated!"));
                }else{
                    return $this->displayError($this->l("An error occured while updating the link to the DB"));
                }
                
            }else{
                $errors[] = $this->displayError($this->l('Invalid Link ID!'));
                return $errors;
            }
        }
        
/*-------------------------------------------------------------*/
/*  DELETE THE LINK FROM THE DB
/*-------------------------------------------------------------*/          

        private function _deleteLink($id_link){
            if ($this->_isLinkAvailable($id_link)){
                 $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                        DELETE FROM '._DB_PREFIX_.'social_links
                        WHERE `id_link` = '.$id_link.'
                       ');

               if ($response){
                   return $this->displayConfirmation($this->l("Link Deleted!"));
               }else{
                   return $this->displayError($this->l("An error occured while deleting the link to the DB"));
               }   
            }else{
                $errors[] = $this->displayError($this->l('Invalid Link ID!'));
                return $errors;
            }
            
        }
        
        
/*-------------------------------------------------------------*/
/*  CHECK THE LINK IF ITS IN THE DB
/*-------------------------------------------------------------*/          

        private function _isLinkAvailable($id_link){          
            $response = 'SELECT `id_link`
                        FROM `'._DB_PREFIX_.'social_links`
                        WHERE `id_link` = '.$id_link;
            $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($response);
            return ($row);
        }

        
        
/*-------------------------------------------------------------*/
/*  PREPARE FOR HOOK
/*-------------------------------------------------------------*/          

        private function _prepHook($params){
            
            $socialLinks = array();
            
            if (Configuration::get('AUTUMN_SOCIAL_MAIL') != ""){
                $mail = 'mailto:'.Configuration::get('AUTUMN_SOCIAL_MAIL');
                $socialLinks['mail'] = $mail;
            }
            
            if (Configuration::get('AUTUMN_SOCIAL_FACEBOOK') != ""){
                $facebook = 'http://facebook.com/'.Configuration::get('AUTUMN_SOCIAL_FACEBOOK');
                $socialLinks['facebook'] = $facebook;
            }
            
            if (Configuration::get('AUTUMN_SOCIAL_TWITTER') != ""){
                $twitter = 'https://twitter.com/'.Configuration::get('AUTUMN_SOCIAL_TWITTER');
                $socialLinks['twitter'] = $twitter;
            }
            
            if (Configuration::get('AUTUMN_SOCIAL_GOOGLE') != ""){
                $google = 'https://plus.google.com/u/0/'.Configuration::get('AUTUMN_SOCIAL_GOOGLE');
                $socialLinks['google'] = $google;
            }
            
            if (Configuration::get('AUTUMN_SOCIAL_PINTEREST') != ""){
                $pinterest = 'http://pinterest.com/'.Configuration::get('AUTUMN_SOCIAL_PINTEREST');
                $socialLinks['pinterest'] = $pinterest;
            }
                        
            if (Configuration::get('AUTUMN_SOCIAL_SKYPE') != ""){
                $skype = 'skype:add?'.Configuration::get('AUTUMN_SOCIAL_SKYPE');
                $socialLinks['skype'] = $skype;
            }
            
            $this->smarty->assignGlobal('socialLinks', $socialLinks);
            
            if ($customlinks = $this->_getLinks($this->context->shop->id)){
                $this->smarty->assignGlobal('customSocialLinks', $customlinks);
            }
            
            $this->context->controller->addCSS(($this->_path).'autumnsocial.css');
            
            return true;
        }
        
        
/*-------------------------------------------------------------*/
/*  HOOK (displayHeader)
/*-------------------------------------------------------------*/
        
        public function hookDisplayHeader ($params){
            $this->_prepHook($params);            
        }
        
}