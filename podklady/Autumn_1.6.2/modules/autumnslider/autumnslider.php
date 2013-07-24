<?php

if (!defined('_PS_VERSION_'))
  exit;

class AutumnSlider extends Module{
    
    private $_output = "";

/*-------------------------------------------------------------*/
/*  CONSTRUCT THE MODULE
/*-------------------------------------------------------------*/
    
    public function __construct(){
        $this->name = 'autumnslider';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'Sercan YEMEN';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Autumn Theme - Image Slider');
        $this->description = $this->l('Image Slider with multilanguage and multishop support');
               
    }

    
/*-------------------------------------------------------------*/
/*  INSTALL THE MODULE
/*-------------------------------------------------------------*/
    
    public function install(){
        if (parent::install() && $this->registerHook('displayHeader')){
            $response = $this->createTables();
            $response &= Configuration::updateValue('AUTUMN_SLIDER_EFFECT', 'random');
            $response &= Configuration::updateValue('AUTUMN_SLIDER_ANI_SPEED', '600');
            $response &= Configuration::updateValue('AUTUMN_SLIDER_PAUSE_TIME', '7000');            
            return $response;
        }
        return false;
    }
    
    
/*-------------------------------------------------------------*/
/*  UNINSTALL THE MODULE
/*-------------------------------------------------------------*/    
    
    public function uninstall(){
        if (parent::uninstall()){
            $response = $this->deleteTables();
            $response &= Configuration::deleteByName('AUTUMN_SLIDER_EFFECT');
            $response &= Configuration::deleteByName('AUTUMN_SLIDER_ANI_SPEED');
            $response &= Configuration::deleteByName('AUTUMN_SLIDER_PAUSE_TIME');
            return $response;
        }
        return false;
    }

    
/*-------------------------------------------------------------*/
/*  CREATE DATABASE TABLES
/*-------------------------------------------------------------*/    
    
    protected function createTables(){
        $response = Db::getInstance()->execute('
                    CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'autumn_slider`
                    (
                        `slide_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                        `shop_id` int(10) unsigned NOT NULL,
                        `lang_id` int(10) unsigned NOT NULL,
                        `is_active` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
                        `position` int(10)  unsigned NOT NULL,
                        `url` varchar(255) NOT NULL DEFAULT \'\',
                        `caption` varchar(255) NOT NULL DEFAULT \'\',
                        `image` varchar(255) NOT NULL,
                        PRIMARY KEY (`slide_id`)
                    )
                    ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
                    ');
                
        return $response;
    }

    
/*-------------------------------------------------------------*/
/*  DELETE DATABASE TABLES
/*-------------------------------------------------------------*/    
    
    protected function deleteTables(){
        $response = Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'autumn_slider`');
                
        return $response;
    }
    
    
/*-------------------------------------------------------------*/
/*  MODUL INITIALIZE & FORM SUBMIT CHECKs
/*-------------------------------------------------------------*/    
    
    public function getContent(){
        $this->_output = "<h2>Autumn Theme - Slider Configuration</h2>";
        $this->_output .= '<br /><br />';
        
        if (Tools::isSubmit('saveConfig')){
            //Save basic configuration
            $this->_output .= $this->_validateAndSaveConfig();
            $this->_displayConfigForm();
        }
        
        elseif (Tools::isSubmit('saveSlide')){
            $response = $this->_validateAndSaveSlide();
            /* default => $response=NULL
             * it's NOT NULL only if there's an error
             * you can print the $response to see errors
             */
            if ($response){
                $this->_output .= $response;
                $this->_displaySlideAddForm();
            }
            else{
                $this->_output .= $this->displayConfirmation($this->l('Slide saved!'));
                $this->_displayConfigForm();
            }
        }
        
        elseif (Tools::isSubmit('addSlide')){
            $this->_output .= $this->_displaySlideAddForm();
        }
        
        elseif (Tools::isSubmit('changeStat')){
            $this->_output .= $this->_validateAndChangeSlideStat();
            $this->_displayConfigForm();
        }
                
        elseif (Tools::isSubmit('deleteSlide')){
            $this->_output .= $this->_checkAndDeleteSlide();
            $this->_displayConfigForm();
        }
        
        elseif (Tools::isSubmit('movedown')){
            $this->_output .= $this->_moveDown();
            $this->_displayConfigForm();
        }
        
        elseif (Tools::isSubmit('moveup')){
            $this->_output .= $this->_moveUp();
            $this->_displayConfigForm();
        }
        
        else{
            $this->_displayConfigForm();
            }
        
        return $this->_output;
        
    }

   
/*-------------------------------------------------------------*/
/*  MODULE CONFIG PAGE - BASIC CONFIG & SLIDE LIST
/*-------------------------------------------------------------*/   
    
    private function _displayConfigForm(){
        
        // BASIC CONFIG FORM
        
        $sliderEffects = array("sliceDown","sliceDownLeft","sliceUp","sliceUpLeft","sliceUpDown","sliceUpDownLeft","fold","fade","random","slideInRight","slideInLeft","boxRandom","boxRain","boxRainReverse","boxRainGrow","boxRainGrowReverse");
        
        $this->_output .= '<fieldset><legend>';
        $this->_output .= '<img src="'._PS_BASE_URL_.__PS_BASE_URI__.'img/t/AdminPreferences.gif" />'.$this->l('Basic Configuration');
        $this->_output .= '</legend>';
        
        $this->_output .= '<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">';
        
        $this->_output .= '<label>'.$this->l('Slider Effect').':</label>';
        $this->_output .= '<div class="margin-form">';
        $this->_output .= '<select name="AUTUMN_SLIDER_EFFECT">';
                           
                          $selected = Configuration::get('AUTUMN_SLIDER_EFFECT');
                          foreach($sliderEffects as $sliderEffect){
                            $this->_output .= '<option';
                                if ($sliderEffect == $selected){
                                    $this->_output .= ' selected="selected "';
                                }
                                    $this->_output .= '>' . $sliderEffect . '</option>';
                          }    
                                
        $this->_output .= '</select>';
        $this->_output .= '</div>';
        
        $this->_output .= '<label>'.$this->l('Animation Speed').':</label>';
        $this->_output .= '<div class="margin-form">';
        $this->_output .= '<input type="text" name="AUTUMN_SLIDER_ANI_SPEED" value="'.Configuration::get('AUTUMN_SLIDER_ANI_SPEED').'"> ms';
        $this->_output .= '</div>';
        
        $this->_output .= '<label>'.$this->l('Pause Time').':</label>';
        $this->_output .= '<div class="margin-form">';
        $this->_output .= '<input type="text" name="AUTUMN_SLIDER_PAUSE_TIME" value="'.Configuration::get('AUTUMN_SLIDER_PAUSE_TIME').'"> ms';
        $this->_output .= '</div>';
        
        $this->_output .= '<div class="margin-form">';
        $this->_output .= '<input type="submit" class="button" name="saveConfig" value="'.$this->l('Save').'">';
        $this->_output .= '</div>';
        
        $this->_output .= '</form></fieldset>';
        
        $this->_output .= '<br /><br />';
        
        
        
        // SLIDE LIST
        
        //check if uri has "lang" value, if not set it to the prestashop default language
        if (Tools::isSubmit('lang')){
            $lang_id = (int)Tools::getValue('lang');
        }
        else{
            $lang_id = (int)Configuration::get('PS_LANG_DEFAULT');
        }
        
        //get all languages
        $languages = Language::getLanguages(false);
                
        //get the current shop id
        $context = Context::getContext();
        $shop_id = $context->shop->id;
        
        $this->_output .= '<fieldset><legend>';
        $this->_output .= '<img src="'._PS_BASE_URL_.__PS_BASE_URI__.'img/t/AdminImages.gif" />'.$this->l('Slides');
        $this->_output .= '</legend>';
                
        //$this->_output .= '<label style="float:left;margin-top:4px;">'.$this->l('Languages:').'</label>';
        //$this->_output .= '<div class="margin-form">';
        $this->_output .= '<table class="table tableDnD cms"><thead><tr>';
            
            //print language links
            foreach ($languages as $language) {
                if ($lang_id == $language['id_lang']){
                        $active = 'color:#bf4242";';
                }
                else{
                        $active = '';
                }
                
                
                $flag = '<div class="displayed_flag"><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'/img/l/'.$language['id_lang'].'.jpg" class="pointer" id="language_'.$language['id_lang'].'" alt="" /></div>';
                
                $this->_output .= '<th>';
                $this->_output .= $flag;
                $this->_output .= '<a style="text-decoration:none;'.$active.'" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&lang='.$language['id_lang'].'">';
                $this->_output .= $language['name'];
                $this->_output .= '</a>';
                $this->_output .= '&nbsp;&nbsp;';
                $this->_output .= '<a style="text-decoration:none;'.$active.'" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&addSlide&lang='.$language['id_lang'].'">';
                $this->_output .= '<img src="'._PS_BASE_URL_.__PS_BASE_URI__.'img/admin/add.gif" title="'.$this->l('Add New Slide').'"/>';
                $this->_output .= '</a>';
                
                $this->_output .= '</th>';
            }
        
        $this->_output .= '</tr></thead></table>';
        //$this->_output .= '</div>';
        
                       
        //get the slides
        $slides = $this->_getSlides($lang_id, $shop_id);
        
        if (!$slides){
            $this->_output .= '<br /><br /><strong>'.$this->l('You have not added any slide yet!').'</strong><br /><br />';
        }
        else{
            $this->_output .= '<br /><br />';
            //$this->_output .= '<div class="margin-form">';
            $this->_output .= '<table class="table" cellpadding=0 cellspacing=0 style="width:85%;padding:10px;"><tbody>';
            
            $fe_counter = 1;
            foreach ($slides as $slide) {
                
                $slides_count = count($slides);
                
                if ($slide['is_active'] == "1"){
                    $stat = "0";
                    $stat_text = $this->l('Deactivate');
                    $stat_title = $this->l('Click here to hide this slide');
                    $stat_image = '<img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/assets/img/active.png" title="'.$stat_title.'"/>';
                }
                else{
                    $stat = "1";
                    $stat_text = $this->l('Activate');
                    $stat_title = $this->l('Click here to show this slide');
                    $stat_image = '<img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/assets/img/passive.png" title="'.$stat_title.'"/> ';
                }
                
                $this->_output .= '<tr style="background:#fbfbfb;"><td>';
                $this->_output .= '<img style="height:100px;" src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/slides/'.$slide['image'].'" />';
                $this->_output .= '</td><td align="right" width=340><div style="float:right;"><div style="float:left;margin-right:15px;">';
                $this->_output .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&changeStat&slideid='.$slide['slide_id'].'&stat='.$stat.'&lang='.$lang_id.'" title="'.$stat_title.'">';
                $this->_output .= $stat_image.$stat_text.'</a>';
                $this->_output .= '</div><div style="float:left;margin-right:15px;">';
                $this->_output .= '<a title="'.$this->l('Edit Slide').'" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&addSlide&slideid='.$slide['slide_id'].'">';
                $this->_output .= '<img src="'._PS_BASE_URL_.__PS_BASE_URI__.'img/admin/edit.gif" title="'.$this->l('Edit Slide').'"/> '.$this->l('Edit').'</a>';
                $this->_output .= '</div><div style="float:left;margin-right:25px;">';
                $this->_output .= '<a title="'.$this->l('Delete Slide').'" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&deleteSlide&slideid='.$slide['slide_id'].'">';
                $this->_output .= '<img src="'._PS_BASE_URL_.__PS_BASE_URI__.'img/admin/delete.gif" title="'.$this->l('Delete Slide').'"/> '.$this->l('Delete').'</a>';
                $this->_output .= '</div>';
                
                if ($slides_count > 1){
                    if ( $fe_counter == 1 ){

                        $this->_output .= '<div style="float:left;margin-right:40px;">';
                        $this->_output .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&lang='.$lang_id.'&movedown&slideid='.$slide['slide_id'].'">';
                        $this->_output .= '<img src="'._PS_BASE_URL_.__PS_BASE_URI__.'img/admin/arrow-down.png" title="'.$this->l('Down').'"/></a>';
                        $this->_output .= '</div>';

                    }else if( $fe_counter == count($slides) ){

                        $this->_output .= '<div style="float:left;margin-right:40px;">';
                        $this->_output .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&lang='.$lang_id.'&moveup&slideid='.$slide['slide_id'].'">';
                        $this->_output .= '<img src="'._PS_BASE_URL_.__PS_BASE_URI__.'img/admin/arrow-up.png" title="'.$this->l('Up').'"/></a>';
                        $this->_output .= '</div>';

                    }else{

                        $this->_output .= '<div style="float:left;margin-right:5px;">';
                        $this->_output .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&lang='.$lang_id.'&moveup&slideid='.$slide['slide_id'].'">';
                        $this->_output .= '<img src="'._PS_BASE_URL_.__PS_BASE_URI__.'img/admin/arrow-up.png" title="'.$this->l('Up').'"/></a>';
                        $this->_output .= '</div>';

                        $this->_output .= '<div style="float:left;margin-right:15px;">';
                        $this->_output .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&lang='.$lang_id.'&movedown&slideid='.$slide['slide_id'].'">';
                        $this->_output .= '<img src="'._PS_BASE_URL_.__PS_BASE_URI__.'img/admin/arrow-down.png" title="'.$this->l('Down').'"/></a>';
                        $this->_output .= '</div>';
                    }    
                }
                
                
                $this->_output .= '<div style="float:left;margin-right:15px;">';
                $this->_output .= $slide['position'];                
                $this->_output .= '</div>';
                
                $this->_output .= '</div></div></td></tr>';
                $fe_counter++;
                
            }
            $this->_output .= '</tbody></table>';
            //$this->_output .= '</div>';
            
            //$this->_output .= '<div class="margin-form">';
            $this->_output .= '<br />';
            $this->_output .= '<p><h3>'.$this->l('Notes:').'</h3></p>';
            $this->_output .= '<p> &bull; '.$this->l('Deleting the slide does not delete the image file from the server. After delete the slide here, you have to manually delete the image from "modules/autumnslider/slides" directory.').'</p>';
            //$this->_output .= '</div>';
        }
        
        $this->_output .= '</fieldset>';
        $this->_output .= '<br /><br />';
    }


    
/*-------------------------------------------------------------*/
/*  DISPLAY SLIDE ADD FORM
/*-------------------------------------------------------------*/
    private function _displaySlideAddForm(){
        if (Tools::isSubmit('lang')){
            $lang_id = (int)Tools::getValue('lang');
            $lang_name = $this->_getLangNameByID($lang_id);
        }
        else{
            $lang_id = false;
        }
        
        //  EDIT SLIDE FORM               
        if (Tools::isSubmit('slideid') && Validate::isInt(Tools::isSubmit('slideid'))){
            
            $slide_id = (int)Tools::getValue('slideid');
            
            if ($this->_isSlideExists($slide_id)){
                
                $slide_lang_id = $this->_getLangIDBySlideID($slide_id);
                $slide_lang_name = $this->_getLangNameByID((int)$slide_lang_id['lang_id']);
                
                $slide = $this->_getSlide($slide_id);
                
                $this->_output .= '<fieldset><legend>';
                $this->_output .= $this->l('Edit Slide').' - #'.$slide_id.' - '.$slide_lang_name;
                $this->_output .= '</legend>';
                
                $this->_output .= '<div class="margin-form">';
                $this->_output .= '<img style="max-width:700px;" src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/slides/'.$slide['image'].'" />';
                $this->_output .= '</div>';
                
                $this->_output .= '<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post" enctype="multipart/form-data">';
                
                $this->_output .= '<label>'.$this->l('Select an image:').' * </label><div class="margin-form">';
                $this->_output .= '<input type="file" name="image" id="image" size="30" /><p class="clear">'.$this->l('Slide background image').' '.$this->l('[Required]').'</p>';
                
                $this->_output .= '</div><label>'.$this->l('Url:').'</label><div class="margin-form">';
                $this->_output .= '<input type="text" name="url" id="url" size="36" value="'.$slide['url'].'" /><p class="clear">'.$this->l('Link of the slide').' '.$this->l('[Optional]').'</p>';
                $this->_output .= '</div>';
                
                //$this->_output .= '<label>'.$this->l('Caption:').'</label><div class="margin-form">';
                //$this->_output .= '<textarea name="caption" id="caption" cols=36 rows=10>'.$slide['caption'].'</textarea><p class="clear">'.$this->l('Slide caption - HTML allowed!').' '.$this->l('[Optional]').'</p>';
                //$this->_output .= '</div>';
                
                $this->_output .= '<div class="margin-form" style="float:left;margin-right:15px;margin-top:20px;">
                                   <input type="submit" name="saveSlide" id="saveSlide" class="button" value="'.$this->l('Save').'">
                                   </div>';
                
                $this->_output .= '<div class="margin-form" style="margin-top:23px;"><a style="font-weight:bold;text-decoration:underline;" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&lang='.$slide_lang_id['lang_id'].'">Cancel</a></div>';
                
                $this->_output .= '</form></fieldset>';
                
            }
            
            else{
                return $this->displayError($this->l('Invalid Slide ID!'));
            }        
        }
        
        
        //  ADD SLIDE FORM
        else if(!Tools::isSubmit('slideid')){
            $this->_output .= '<fieldset><legend>';
            $this->_output .= $this->l('Add Slide').' - '.$lang_name;
            $this->_output .= '</legend>';
            
            $this->_output .= '<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post" enctype="multipart/form-data">';
            
            $this->_output .= '<label>'.$this->l('Select an image:').'</label><div class="margin-form">';
            $this->_output .= '<input type="file" name="image" id="image" size="30" /><p class="clear">'.$this->l('Slide background image').' '.$this->l('[Required]').'</p>';
            
            $this->_output .= '</div><label>'.$this->l('Url:').'</label><div class="margin-form">';
            $this->_output .= '<input type="text" name="url" id="url" size="36" /><p class="clear">'.$this->l('Link of the slide').' '.$this->l('[Optional]').'</p>';
            $this->_output .= '</div>';
            
            //$this->_output .= '<label>'.$this->l('Caption:').'</label><div class="margin-form">';
            //$this->_output .= '<textarea name="caption" id="caption" cols=36 rows=6></textarea><p class="clear">'.$this->l('Slide caption - HTML allowed!').' '.$this->l('[Optional]').'</p>';
            //$this->_output .= '</div>';
            
            $this->_output .= '<div class="margin-form" style="float:left;margin-right:15px;margin-top:20px;">
                               <input type="submit" name="saveSlide" id="saveSlide" class="button" value="'.$this->l('Save').'">
                               </div>';
            
            $this->_output .= '<div class="margin-form" style="margin-top:23px;"><a style="font-weight:bold;text-decoration:underline;" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&lang='.$lang_id.'">Cancel</a></div>';
            
            $this->_output .= '</form></fieldset>';
        }
        else{
            return $this->displayError($this->l('Invalid Slide ID!'));
        }
        
    }
    
    
/*-------------------------------------------------------------*/
/*  VALIDATE & SAVE CONFIG
/*-------------------------------------------------------------*/

    private function _validateAndSaveConfig(){
        
        //VALIDATION
        
        $errors = array();
        
        if (!(Tools::getValue('AUTUMN_SLIDER_EFFECT'))){
            $errors[] = $this->l('Please select an effect for your slider!');
        }
        if ( !Validate::isInt((Tools::getValue('AUTUMN_SLIDER_ANI_SPEED'))) ||
             !Validate::isInt((Tools::getValue('AUTUMN_SLIDER_PAUSE_TIME'))) ){
                $errors[] = $this->l('Invalid Values!');
        }
        
        if (count($errors)){
             return $this->displayError(implode('<br />', $errors));
        
        } else {
            
            //NO ERROR -> SAVE

            $errors = array();
               
            $response = Configuration::updateValue('AUTUMN_SLIDER_EFFECT', Tools::getValue('AUTUMN_SLIDER_EFFECT'));
            $response &= Configuration::updateValue('AUTUMN_SLIDER_ANI_SPEED', Tools::getValue('AUTUMN_SLIDER_ANI_SPEED'));
            $response &= Configuration::updateValue('AUTUMN_SLIDER_PAUSE_TIME', Tools::getValue('AUTUMN_SLIDER_PAUSE_TIME'));

            if (!$response){
                return $this->displayError($this->l('Configuration could not be updated!'));
            }
            else{
                return $this->displayConfirmation($this->l('Configuration updated!'));
            }
            
        }
        
    }
    

/*-------------------------------------------------------------*/
/*  VALIDATE AND ADD/UPDATE SLIDE
/*-------------------------------------------------------------*/

    private function _validateAndSaveSlide (){
        
        //  UPDATE THE SLIDE
        if (Tools::isSubmit('slideid') && Validate::isInt(Tools::getValue('slideid'))){
            if ($this->_isSlideExists((int)Tools::getValue('slideid'))){
                
                $upload = true;
                $slide_caption = '';
                                
                //  Validation stuff
                if (strlen(Tools::getValue('url')) > 0 && !Validate::isUrl(Tools::getValue('url'))){
                    return $this->displayError($this->l('URL format is not correct!'));
                }
                       
                if (Tools::getValue('image') != null && !Validate::isFileName(Tools::getValue('image'))){
                    return $this->displayError($this->l('Invalid filename!'));
                }
                
                if (strlen(Tools::getValue('caption')) > 0){
                    $slide_caption = pSQl(Tools::getValue('caption'), true);
                }
                                
                $slide_id = (int)Tools::getValue('slideid');
                
                $slide = $this->_getSlide($slide_id);
                $current_slide_image = $slide['image'];
                
                if (!$_FILES['image']['name'] && $_FILES['image']['error']){
                    $upload = false;
                }
                 
                
                //PREP THE VARIABLES
                $slide_url = pSQL(Tools::getValue('url'));
                
                //If there is a new image
                if ($upload){
                    $type = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
                    $imagesize = array();
                    $imagesize = @getimagesize($_FILES['image']['tmp_name']);

                    if (isset($_FILES['image']) &&
                        isset($_FILES['image']['tmp_name']) &&
                        !empty($_FILES['image']['tmp_name']) &&
                        !empty($imagesize) &&
                        in_array(strtolower(substr(strrchr($imagesize['mime'], '/'), 1)), array('jpg', 'gif', 'jpeg', 'png')) &&
                        in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
                    {
                        $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                        $salt = sha1(microtime());
                        if ($error = ImageManager::validateUpload($_FILES['image'])){
                            return $error;
                        }
                        elseif (!$temp_name || !move_uploaded_file($_FILES['image']['tmp_name'], $temp_name)){
                            return $this->displayError($this->l('An error occurred during the image upload.'));
                        }
                        elseif (!ImageManager::resize($temp_name, dirname(__FILE__).'/slides/'.Tools::encrypt($_FILES['image']['name'].$salt).'.'.$type)){
                            return $this->displayError($this->l('An error occurred during the image upload.'));
                        }
                        if (isset($temp_name)){
                            @unlink($temp_name);
                        }

                        $slide_image = pSQL(Tools::encrypt($_FILES['image']['name'].$salt).'.'.$type);
                    }
                    else{
                        return $this->displayError($this->l('Please select a proper image! (Allowed extensions: *.jpg *.gif *.jpeg *.png)'));
                    }
                    
                    //DO THE UPDATE (WITH THE NEW IMAGE)
                    $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                                UPDATE '._DB_PREFIX_.'autumn_slider
                                SET `url` = \''.$slide_url.'\', `caption` = \''.$slide_caption.'\', `image` =\''.$slide_image.'\'
                                WHERE `slide_id` = '.$slide_id.'
                                ');
                    if (!$response){
                        return $this->displayError($this->l('Slide could not be added!'));
                    }
                    else{
                        return NULL;
                    }
                    
                }
                
                //DO THE UPDATE
                if (!$upload){
                  $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                                UPDATE '._DB_PREFIX_.'autumn_slider
                                SET `url` = \''.$slide_url.'\', `caption` = \''.$slide_caption.'\'
                                WHERE `slide_id` = '.$slide_id.'
                                ');
                    if (!$response){
                        return $this->displayError($this->l('Slide could not be added!'));
                    }
                    else{
                        return NULL;
                    }
                }
                
            }
        }
        
        
        
        //  ADD NEW SLIDE
        elseif (!Tools::isSubmit('slideid')){
            
            $slide_caption = '';
            
            //  Validation stuff
            if (!Tools::isSubmit('lang')){
                return $this->displayError($this->l('Invalid Language!'));
            }
            
            if (strlen(Tools::getValue('url')) > 0 && !Validate::isUrl(Tools::getValue('url'))){
                return $this->displayError($this->l('URL format is not correct!'));
            }
            
            if (strlen(Tools::getValue('caption')) > 0){
                    $slide_caption = pSQL(Tools::getValue('caption'), true);
                }
            
            if (Tools::getValue('image') != null && !Validate::isFileName(Tools::getValue('image'))){
                return $this->displayError($this->l('Invalid filename!'));
            }
            
            
            // PREP THE VARIABLES
            // Get the url
            if (Tools::getValue('url') == ''){
                $slide_url = '';
            }
            else{
                $slide_url = pSQL(Tools::getValue('url'));
            }
            
            //Get the language
            $lang_id = pSQL((int)Tools::getValue('lang'));
            
            // Upload the image and get the filename       
            $type = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
            $imagesize = array();
            $imagesize = @getimagesize($_FILES['image']['tmp_name']);

            if (isset($_FILES['image']) &&
                isset($_FILES['image']['tmp_name']) &&
                !empty($_FILES['image']['tmp_name']) &&
                !empty($imagesize) &&
                in_array(strtolower(substr(strrchr($imagesize['mime'], '/'), 1)), array('jpg', 'gif', 'jpeg', 'png')) &&
                in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
            {
                $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                $salt = sha1(microtime());
                if ($error = ImageManager::validateUpload($_FILES['image'])){
                    return $error;
                }
                elseif (!$temp_name || !move_uploaded_file($_FILES['image']['tmp_name'], $temp_name)){
                    return $this->displayError($this->l('An error occurred during the image upload to the tmp folder.'));
                }
                elseif (!ImageManager::resize($temp_name, dirname(__FILE__).'/slides/'.Tools::encrypt($_FILES['image']['name'].$salt).'.'.$type)){
                    return $this->displayError($this->l('An error occurred during the image upload.'));
                }
                if (isset($temp_name)){
                    @unlink($temp_name);
                }
                
                $slide_image = pSQL(Tools::encrypt($_FILES['image']['name'].$salt).'.'.$type);
            }
            else{
                return $this->displayError($this->l('Please select a proper image! (Allowed extensions: *.jpg *.gif *.jpeg *.png)'));
            }
            
            // Get the shop id
            $context = Context::getContext();
            $shop_id = pSQL($context->shop->id);
            
            //Get the last position and do the math
            $position_array = $this->_getLastSlidePosition($shop_id, $lang_id);
            $position_values = array_values($position_array);
            $position = array_shift($position_values);
            
            if ($position == NULL){
                $position = 1;
            }else{
                $position++;
            }
            
            //DO THE SAVE
            $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                        INSERT INTO '._DB_PREFIX_.'autumn_slider
                        (`shop_id`,`lang_id`,`position`,`url`,`caption`,`image`)
                        VALUES ('.$shop_id.','.$lang_id.','.$position.',\''.$slide_url.'\',\''.$slide_caption.'\',\''.$slide_image.'\')
                        ');
            if (!$response){
                return $this->displayError($this->l('Slide could not be added!'));
            }
            else{
                return NULL;
            }
        }
        
        //Errors
        else{
            return $this->displayError($this->l('Slide could not be saved! Unknown error! Please try again!'));
        }
        
    }
    
    
/*-------------------------------------------------------------*/
/*  CHANGE THE SLIDE STATUS -> ACTIVE/DEACTIVE
/*-------------------------------------------------------------*/

    private function _validateAndChangeSlideStat(){
        if (!Tools::isSubmit('slideid')){
            return $this->displayError($this->l('Invalid Slide ID!'));
        }else if (!Tools::isSubmit('stat')){
            return $this->displayError($this->l('Invalid State!'));
        }else if (!Validate::isInt(Tools::getValue('stat')) || Tools::getValue('stat') != 0 && Tools::getValue('stat') != 1 ){
            return $this->displayError($this->l('Invalid State!'));
        }
        
        $slide_id = (int)Tools::getValue('slideid');
        $stat = (int)Tools::getValue('stat');
        
        if ($this->_isSlideExists($slide_id)){
            $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                        UPDATE '._DB_PREFIX_.'autumn_slider
                        SET `is_active` = '.$stat.'
                        WHERE `slide_id` = '.$slide_id.'
                        ');
            if ($response){
                return $this->displayConfirmation($this->l('Slide state updated!'));
            }
            else{
                return $this->displayError($this->l('Slide state could not be changed!'));
            }
        }
        
    }
    
    
/*-------------------------------------------------------------*/
/*  CHECH AND DELETE THE SLIDE - ONLY FROM DB
/*-------------------------------------------------------------*/

    private function _checkAndDeleteSlide(){
        if (!Tools::isSubmit('slideid')){
            return $this->displayError($this->l('Invalid Slide ID!'));
        }
        
        $slide_id = (int)Tools::getValue('slideid');
                                
        if ($this->_isSlideExists($slide_id)){
                
            $curr_slide = $this->_getSlide($slide_id);
            $shop_id = $curr_slide['shop_id'];
            $lang_id = $curr_slide['lang_id'];
            $curr_position = $curr_slide['position'];
                        
            $delete = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                      DELETE FROM '._DB_PREFIX_.'autumn_slider
                      WHERE `slide_id` = '.$slide_id.'
                      ');
            
            if (!$delete){
                return $this->displayError($this->l('Slide could not be deleted!'));
            }    
            
            $next_rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                           SELECT `slide_id`, `position`
                           FROM '._DB_PREFIX_.'autumn_slider
                           WHERE `shop_id` = '.$shop_id.'
                           AND `lang_id` = '.$lang_id.'
                           AND `position` > '.$curr_position
                           );
            
            $new_position = $curr_position;
            foreach ($next_rows as $next_row){
                
                $fix_positions = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                                 UPDATE '._DB_PREFIX_.'autumn_slider
                                 SET `position` = '.$new_position.'
                                 WHERE `slide_id` = '.$next_row['slide_id'].'
                                 ');
                
                $new_position++;
            }
            
            
            return $this->displayConfirmation($this->l('Slide deleted!'));
            
        }
        
    }
    
   
/*-------------------------------------------------------------*/
/*  CHECK & GET SLIDES WITH USING 'lang_id' & 'shop_id'
/*-------------------------------------------------------------*/    
    
    private function _getSlides($lang_id, $shop_id) {
       
       $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                      SELECT `slide_id`, `shop_id`, `lang_id`, `is_active`, `position`,`url`, `caption`, `image`
                      FROM '._DB_PREFIX_.'autumn_slider
                      WHERE `shop_id` = '.$shop_id.' AND `lang_id` = '.$lang_id.'
                      ORDER BY `position` ASC'
                      );
       return $response;
    }

    
/*-------------------------------------------------------------*/
/*  CHECK & GET SLIDE WITH USING 'slide_id'
/*-------------------------------------------------------------*/    
    
    private function _getSlide($slide_id){
                     
       $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                      SELECT `slide_id`, `shop_id`, `lang_id`, `is_active`, `position`,`url`, `caption`, `image`
                      FROM '._DB_PREFIX_.'autumn_slider
                      WHERE `slide_id` = '.$slide_id);
       return $response;
    }
      
    
/*-------------------------------------------------------------*/
/*  CHECK THE SLIDE IF EXISTS
/*-------------------------------------------------------------*/    
    
    private function _isSlideExists($slide_id){
        $request = 'SELECT `slide_id`
                    FROM `'._DB_PREFIX_.'autumn_slider`
                    WHERE `slide_id` = '.$slide_id;
	$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($request);
	return ($row);
    }    
    
    
/*-------------------------------------------------------------*/
/*  GET LANGUAGE NAME BY USING LANGUAGE ID
/*-------------------------------------------------------------*/    
    
    private function _getLangNameByID($lang_id){
        $languages = Language::getLanguages(false);
        
        foreach ($languages as $language) {
            if ($language['id_lang'] == $lang_id){
                return $language['name'];
            }
        }
        
        return false;
        
    }    
    
    
/*-------------------------------------------------------------*/
/*  GET LANGUAGE ID FROM DB BY USING SLIDE ID
/*-------------------------------------------------------------*/    
    
    private function _getLangIDBySlideID($slide_id){
        
        $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                    SELECT `lang_id`
                    FROM '._DB_PREFIX_.'autumn_slider
                    WHERE `slide_id` = '.$slide_id.'
                    ');
        return $response;
    }    
    
    
/*-------------------------------------------------------------*/
/*  GET LAST SLIDE POSITON
/*-------------------------------------------------------------*/    
    
    private function _getLastSlidePosition($shop_id, $lang_id){
        
        $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                    SELECT MAX(`position`)
                    FROM '._DB_PREFIX_.'autumn_slider
                    WHERE `shop_id` = '.$shop_id.'
                    AND `lang_id` = '.$lang_id.'
                    ');
        return $response;
    }    

    
/*-------------------------------------------------------------*/
/*  POSITION -> MOVE DOWN
/*-------------------------------------------------------------*/    
    
    private function _moveDown(){
        if (!Tools::isSubmit('slideid')){
            return $this->displayError($this->l('Invalid Slide ID!'));
        }
        
        $slide_id = (int)Tools::getValue('slideid');
        
        if ($this->_isSlideExists($slide_id)){
            
            $curr_slide = $this->_getSlide($slide_id);
            $shop_id = $curr_slide['shop_id'];
            $lang_id = $curr_slide['lang_id'];
            $curr_position = $curr_slide['position'];
            $next_position = $curr_position + 1;
            
            $next_slide_id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                             SELECT `slide_id`
                             FROM '._DB_PREFIX_.'autumn_slider
                             WHERE `shop_id` = '.$shop_id.'
                             AND `lang_id` = '.$lang_id.'
                             AND `position` = '.$next_position.'
                             ');
            
            if (!$next_slide_id){
                return $this->displayError($this->l('Invalid operation!'));
            }
            
            $update_next_slide_position = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                                          UPDATE '._DB_PREFIX_.'autumn_slider
                                          SET `position` = '.$curr_position.'
                                          WHERE `slide_id` = '.$next_slide_id['slide_id'].'
                                          ');
            
            $update_curr_slide_position = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                                          UPDATE '._DB_PREFIX_.'autumn_slider
                                          SET `position` = '.$next_position.'
                                          WHERE `slide_id` = '.$slide_id.'
                                          ');
        }        
        
        if ($update_next_slide_position && $update_curr_slide_position){
                return $this->displayConfirmation($this->l('Slide position updated!'));
        }else{
                return $this->displayError($this->l('Slide position could not be updated!'));
        }
    }    

    
/*-------------------------------------------------------------*/
/*  POSITION -> MOVE UP
/*-------------------------------------------------------------*/    
    
    private function _moveUp(){
        if (!Tools::isSubmit('slideid')){
            return $this->displayError($this->l('Invalid Slide ID!'));
        }
        
        $slide_id = (int)Tools::getValue('slideid');
        
        if ($this->_isSlideExists($slide_id)){
            
            $curr_slide = $this->_getSlide($slide_id);
            $shop_id = $curr_slide['shop_id'];
            $lang_id = $curr_slide['lang_id'];
            $curr_position = $curr_slide['position'];
            $pre_position = $curr_position - 1;
            
            $pre_slide_id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                             SELECT `slide_id`
                             FROM '._DB_PREFIX_.'autumn_slider
                             WHERE `shop_id` = '.$shop_id.'
                             AND `lang_id` = '.$lang_id.'
                             AND `position` = '.$pre_position.'
                             ');
            
            if (!$pre_slide_id){
                return $this->displayError($this->l('Invalid operation!'));
            }
            
            $update_pre_slide_position = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                                          UPDATE '._DB_PREFIX_.'autumn_slider
                                          SET `position` = '.$curr_position.'
                                          WHERE `slide_id` = '.$pre_slide_id['slide_id'].'
                                          ');
            
            $update_curr_slide_position = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                                          UPDATE '._DB_PREFIX_.'autumn_slider
                                          SET `position` = '.$pre_position.'
                                          WHERE `slide_id` = '.$slide_id.'
                                          ');
        }        
        
        if ($update_pre_slide_position && $update_curr_slide_position){
                return $this->displayConfirmation($this->l('Slide position updated!'));
        }else{
                return $this->displayError($this->l('Slide position could not be updated!'));
        }
    }    
    
/*-------------------------------------------------------------*/
/*  HOOKS
/*-------------------------------------------------------------*/    
    
/*-------------------------------------------------------------*/
/*  PREPARE FOR HOOK
/*-------------------------------------------------------------*/    
    
    private function _prepHook(){
        
        $slide_config = array(
            'effect' => "\"".Configuration::get('AUTUMN_SLIDER_EFFECT')."\"",
            'anim_speed' => Configuration::get('AUTUMN_SLIDER_ANI_SPEED'),
            'pause_time' => Configuration::get('AUTUMN_SLIDER_PAUSE_TIME')
        );
        
        $context = Context::getContext();
        
        $slides = $this->_getSlides($this->context->language->id, $this->context->shop->id);
        
        if (!$slides){
            return false;
        }
        
        $this->smarty->assignGlobal('slide_config', $slide_config);
        $this->smarty->assignGlobal('slides', $slides);
        
        return true;
    }
    
    
/*-------------------------------------------------------------*/
/*  HOOK DISPLAY HOME
/*-------------------------------------------------------------*/    
    
    public function hookDisplayHeader(){
        
        if (!$this->_prepHook()){
            return;
        }
        
	$this->context->controller->addJS($this->_path.'assets/js/jquery.nivo.slider.pack.js');
	$this->context->controller->addCSS($this->_path.'assets/css/jquery.nivo-slider.css');
        
	//$this->context->controller->addJS($this->_path.'js/homeslider.js');
	return $this->display(__FILE__, 'autumnslider.tpl');
        
    }
    
    
}