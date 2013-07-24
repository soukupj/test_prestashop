<?php
/* Autumn Theme - Mega Menu - 2012 - Sercan YEMEN - twitter.com/sercan */
    
if (!defined('_PS_VERSION_'))
	exit;

class AutumnMegaMenu extends Module{
    
    private $_html = '';
    private $_respMenu = '';
    private $user_groups;
    
    function __construct(){
        $this->name = 'autumnmegamenu';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'Sercan YEMEN';
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);

        parent::__construct();

        $this->displayName = $this->l('Autumn Theme - Mega Menu');
        $this->description = $this->l('Mega Menu');
    }
    

/*-------------------------------------------------------------*/
/*  INSTALL THE MODULE
/*-------------------------------------------------------------*/
    
    public function install(){
        if (parent::install() && $this->registerHook('displayTop')){
            $response = $this->createTables();
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
            return $response;
        }
        return false;
    }
 

/*-------------------------------------------------------------*/
/*  CREATE THE TABLES
/*-------------------------------------------------------------*/  
    
    protected function createTables(){
           
            $response = (bool)Db::getInstance()->execute('
                    CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'megamenu_menus` (
                            `id_menu` int(10) unsigned NOT NULL AUTO_INCREMENT,
                            `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
                            `position` int(10) unsigned NOT NULL DEFAULT \'0\',
                            `link` varchar(255) NOT NULL DEFAULT \'\',
                            `id_shop` int(10) unsigned NOT NULL,
                            PRIMARY KEY (`id_menu`)
                    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');

            
            $response &= Db::getInstance()->execute('
                    CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'megamenu_menus_lan` (
                      `id_menu` int(10) unsigned NOT NULL,
                      `id_lang` int(10) unsigned NOT NULL,
                      `name` varchar(255) NOT NULL,
                      PRIMARY KEY (`id_menu`,`id_lang`)
                    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');
            
            
            $response &= Db::getInstance()->execute('
                    CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'megamenu_menus_context` (
                      `id_menu` int(10) unsigned NOT NULL,
                      `top` varchar(255) NOT NULL,
                      `m_left` varchar(255) NOT NULL,
                      `m_right` varchar(255) NOT NULL,
                      `bottom` varchar(255) NOT NULL,
                      PRIMARY KEY (`id_menu`)
                    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');

            return $response;
            
    }


/*-------------------------------------------------------------*/
/*  DELETE THE TABLES
/*-------------------------------------------------------------*/  
    
	protected function deleteTables(){
            return Db::getInstance()->execute('
                    DROP TABLE IF EXISTS `'._DB_PREFIX_.'megamenu_menus`, `'._DB_PREFIX_.'megamenu_menus_lan`, `'._DB_PREFIX_.'megamenu_menus_context`;
            ');
	}
    
    
    
/*-------------------------------------------------------------*/
/*  MODUL INITIALIZE & FORM SUBMIT CHECKs
/*-------------------------------------------------------------*/    
    
        
    public function getContent(){
                
        //$this->_html .= $this->headerHTML();
        $this->_output = '<h2>'.$this->displayName.'</h2>';

        if (Tools::isSubmit('saveMenu')){
            
           $validate = $this->_validate();
           
           if ( $validate ){ //Validate will return NULL if there is no error
               $this->_output .= $this->displayError(implode('<br />', $validate));
               $this->_output .= $this->displayAddMenuForm();
           }else{
               if ($process = $this->_process()){
                   $this->_output .= $this->displayError(implode('<br />', $process));
                   $this->_output .= $this->displayAddMenuForm();
               }elseif (Tools::isSubmit('editMenu')){
                   $this->_output .= $this->displayConfirmation($this->l('Menu updated!'));
                   $this->_output .= $this->displayForm();
               }else{
                   $this->_output .= $this->displayConfirmation($this->l('Menu added!'));
                   $this->_output .= $this->displayForm();
               }
           }
        }
        elseif( Tools::isSubmit('saveMenuEditor') ){
           //$validate = $this->_validate();
            
           // if ( $validate ){ //Validate will return NULL if there is no error
           //    $this->_output .= $this->displayError(implode('<br />', $validate));
           //    $this->_output .= $this->displayMenuEditor(Tools::getValue('hidden_menu_id'));
           //}else{
               //if ($process = $this->_process()){
               //    $this->_output .= $this->displayError(implode('<br />', $process));
               //    $this->_output .= $this->displayMenuEditor(Tools::getValue('hidden_menu_id'));
               //}else{
                   $this->_output .= $this->_processMenuEditorSave(Tools::getValue('hidden_id_menu'));
                   $this->_output .= $this->displayMenuEditor(Tools::getValue('hidden_id_menu'));
               //}
           //}
            
        }
        
        elseif ( Tools::isSubmit('addMenu') ){
            return $this->displayAddMenuForm();
        }
        elseif ( Tools::isSubmit('editMenu') ){
            return $this->displayAddMenuForm();
        }
        elseif ( Tools::isSubmit('delMenu') ){
            $this->_output .= $this->_delMenu(Tools::getValue('delMenu'));
            $this->_output .= $this->displayForm();
        }
        elseif ( Tools::isSubmit('menuEditor') ){
            $this->_output .= $this->displayMenuEditor(Tools::getValue('menuEditor'));
        }
        elseif( Tools::isSubmit('moveLeft') && Tools::getValue('moveLeft') != "" ){
            $this->_output .= $this->_moveLeft(Tools::getValue('moveLeft'));
            $this->_output .= $this->displayForm();
        }
        elseif( Tools::isSubmit('moveRight') && Tools::getValue('moveRight') != "" ){
            $this->_output .= $this->_moveRight(Tools::getValue('moveRight'));
            $this->_output .=  $this->displayForm();
        }        
        else{
            $this->_output .= $this->displayForm();
        }
        
        return $this->_output;
    }
        
/*-------------------------------------------------------------*/
/*  DISPLAY CONFIGURATION FORM
/*-------------------------------------------------------------*/    
                
    public function displayForm(){
                
        $this->_html = '
                        <style>
                        fieldset.nice-layout{
                            background:#fafafa;
                            border:none;
                            color:#333333;

                            box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                            -moz-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                            -webkit-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;

                        }

                        fieldset.nice-layout legend{
                            background:#ffffff;
                            border:none;
                            padding:10px 15px;

                            border-radius:4px;
                            -moz-border-radius:4px;
                            -webkit-border-radius:4px;

                            box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                            -moz-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                            -webkit-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                        }
                        
                        li.menu-box{
                            padding:5px 10px;
                            background:#ffffff;
                            border:none;

                            box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                            -moz-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                            -webkit-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                            
                            float:left;
                            margin:10px 10px 10px 0;
                        }
                        
                        a.addMenu{
                            display:block;
                            float:left;
                            padding:5px 10px;
                            background:#ffffff;
                            border:none;

                            box-shadow: 0 0 0 1.5px rgba(0,0,0,0.25) , 0 1.5px 1.5px 0 rgba(0,0,0,0.25), 0 2px 1.5px 0 rgba(0, 0, 0, 0.15) ;
                            -moz-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.25) , 0 1.5px 1.5px 0 rgba(0,0,0,0.25), 0 2px 1.5px 0 rgba(0, 0, 0, 0.15) ;
                            -webkit-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.25) , 0 1.5px 1.5px 0 rgba(0,0,0,0.25), 0 2px 1.5px 0 rgba(0, 0, 0, 0.15) ;

                            margin:10px 10px 10px 0;
                        }
                        

                        </style>
                        
                        <fieldset class="nice-layout"><legend><img src="'.$this->_path.'/img/tab.png" alt="" title="" />'.$this->l('Mega Menu').'</legend>

                         <div>';
        
        $count = $this->_getMenuCount();
        
        $menus = $this->_getMenus($this->context->shop->id);
        
        if ($menus){
            $this->_html .= '<ul>';
            foreach ($menus as $menu){
                if ($menu['id_lang'] == $this->context->language->id){
                    $this->_html .= '<li class="menu-box">';
                    $this->_html .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&menuEditor='.$menu['id_menu'].'"><b>';
                    $this->_html .= $menu['name'];
                    $this->_html .= '</b></a>';
                    $this->_html .= ' - ';
                    $this->_html .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&editMenu='.$menu['id_menu'].'"><img src="'.$this->_path.'/img/pencil.png" alt="" title="'.$this->l('Edit menu name').'" /></a>';
                    $this->_html .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&delMenu='.$menu['id_menu'].'" onclick="return confirm(\''.$this->l('Are you sure you want to delete this menu?').'\')"><img src="'.$this->_path.'/img/del.png" alt="" title="'.$this->l('Delete menu').'" /></a>';
                    $this->_html .= ' - ';
                    
                    if ($menu['position'] == 1){
                        $this->_html .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&moveRight='.$menu['id_menu'].'"><img src="'.$this->_path.'/img/next.png" alt="" title="'.$this->l('Move Right').'" /></a>';
                    }else if($menu['position'] == $count){
                        $this->_html .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&moveLeft='.$menu['id_menu'].'"><img src="'.$this->_path.'/img/prev.png" alt="" title="'.$this->l('Move Left').'" /></a>';
                    }else{
                        $this->_html .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&moveLeft='.$menu['id_menu'].'"><img src="'.$this->_path.'/img/prev.png" alt="" title="'.$this->l('Move Left').'" /></a> <a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&moveRight='.$menu['id_menu'].'"><img src="'.$this->_path.'/img/next.png" alt="" title="'.$this->l('Move Left').'" /></a>';
                    }
                    $this->_html .= '</li>';
                }
            }
            $this->_html .= '</ul>';
        }

        
        $this->_html .= '<a class="addMenu" style="text-decoration:none;" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&addMenu">';
        $this->_html .= '<img src="'.$this->_path.'/img/add.png" alt="" title="" />'.$this->l('Add New Menu');
        $this->_html .= '</a>
                
                        </div>
                        <div style="clear:both;">
                        <br />
                            <p style="font-size:12px;font-weight:bold;">'.$this->l("Click on the menu name to build your menu.").'</p>
                        </div>

                        </fieldset>';
        return $this->_html;
    }
    
    
/*-------------------------------------------------------------*/
/*  DISPLAY ADD MENU FORM
/*-------------------------------------------------------------*/    
                
    public function displayAddMenuForm(){
        if (Tools::getValue('editMenu') != ""){
            $id_menu = Tools::getValue('editMenu');
            if ($this->_isMenuExist($id_menu)){
                if($names = $this->_getNameLang($id_menu)){
                    foreach ($names as $name_lang){
                        //$name[$key] = $value['name'];
                        $name[$name_lang['id_lang']] = $name_lang['name'];
                    }
                }
                
                $link = $this->_getMenuBasicLink($id_menu);
                if (!$link){
                    $link = "";
                }
            }
            else{
                $this->_html .= $this->displayError($this->l('Invalid Menu ID!'));
                return $this->_html;
            }
        }
        
        
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $languages = Language::getLanguages(false);
        
        $this->_html .= '<script type="text/javascript">id_language = Number('.$id_lang_default.');</script>';
        
        $this->_html .= '<fieldset><legend>';
        
        if (Tools::isSubmit('editMenu')){
             $this->_html .= '<img src="'.$this->_path.'/img/pencil.png" alt="" title="" />'.$this->l('Edit Menu');
        }else{
            $this->_html .= '<img src="'.$this->_path.'/img/add.png" alt="" title="" />'.$this->l('Add New Menu');
        }
        
        $this->_html .= '</legend>';

        $this->_html .= '<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">';

        $this->_html .= '<label>'.$this->l('Name:').'</label><div class="margin-form">';
        
        foreach ($languages as $language){
            $this->_html .= ' <div id="name_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $id_lang_default ? 'block' : 'none').';float: left;">
                                <input type="text" name="name_'.$language['id_lang'].'" id="name_'.$language['id_lang'].'" size="36" value="'.(isset($name[$language['id_lang']]) ? $name[$language['id_lang']] : '').'" />
                              </div>';
        }
        
        $this->_html .= $this->displayFlags($languages, $id_lang_default, 'name', 'name', true);
        
        $this->_html .= '</div>';
        
        $this->_html .= '<br />';
        
        $this->_html .= '<label>'.$this->l('Link:').'</label><div class="margin-form">';
        $this->_html .= ' <div id="link_wrapper" style="float: left;">
                            <input type="text" name="link" id="link" size="36" value="'.(isset($link) ? $link : '').'" />
                          </div>';
        
        $this->_html .= '<div class="margin-form" style="clear:both;float:left;margin-right:15px;margin-top:20px;">
                           <input type="submit" name="saveMenu" id="saveMenu" class="button" value="'.$this->l('Save').'">
                           </div>';
        
        $this->_html .= '</div>';
        
        $this->_html .= '<div class="margin-form" style="margin-top:33px;"><a style="font-weight:bold;text-decoration:underline;" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'">Cancel</a></div>';

        $this->_html .= '</form></fieldset>';
        
        return $this->_html;
    }

       
/*-------------------------------------------------------------*/
/*  DISPLAY MENU EDITOR
/*-------------------------------------------------------------*/    
                
    public function displayMenuEditor($id_menu){
        
        if (isset($id_menu) && $id_menu){
            if ($this->_isMenuExist($id_menu)){
                
                $this->_html = '<style>
                        
                                fieldset.nice-layout{
                                    background:#eee;
                                    border:none;
                                    color:#333333;

                                    box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                                    -moz-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                                    -webkit-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;

                                }

                                fieldset.nice-layout legend{
                                    background:#ffffff;
                                    border:none;
                                    padding:10px 15px;

                                    border-radius:4px;
                                    -moz-border-radius:4px;
                                    -webkit-border-radius:4px;

                                    box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                                    -moz-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                                    -webkit-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                                }

                                input.hide{
                                    position:absolute;
                                    left:-999em;
                                }

                                label.selectable{
                                    display:block;
                                    float:left;
                                    width:auto;
                                    padding:10px;
                                    cursor:pointer;
                                }

                                label.selectable img{
                                    padding:0;
                                }

                                input[type=radio]:checked + label.selectable{
                                    background:#e0e0e0;

                                    box-shadow:0 1px 0 0 rgba(255,255,255,0.75), inset 0 0 3px 0 rgba(0,0,0,0.35);
                                    -moz-box-shadow:0 1px 0 0 rgba(255,255,255,0.75), inset 0 0 3px 0 rgba(0,0,0,0.35);
                                    -webkit-box-shadow:0 1px 0 0 rgba(255,255,255,0.75), inset 0 0 3px 0 rgba(0,0,0,0.35);
                                }
                                
                                
                                .actual-content div{
                                    margin-top:10px;
                                    height:200px;
                                    clear:both;
                                }
                                
                                .actual-content .hidden{
                                    display:none;
                                }
                                
                                .multiple-select{
                                    width:99%;
                                    height:100%;
                                }
                                
                                .select{
                                    width:99%;
                                }
                                
                                span.inf{
                                    padding:3px;
                                    font-size:10px;
                                    margin-top:5px;
                                    display:block;
                                    color:#000;
                                }

                                #product-select{
                                    display:block;
                                    clear:both;
                                }
                                
                                #add-product-select{
                                    cursor:pointer;
                                    padding:5px 10px;
                                    background:#346f36;
                                    color:#ffffff;
                                    display:block;
                                    float:left;
                                    margin-top:10px;
                                    
                                    box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.4), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                                    -moz-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.4), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                                    -webkit-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.4), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                                }
                                
                                #remove-product-select{
                                    cursor:pointer;
                                    padding:5px 10px;
                                    background:#da3b44;
                                    color:#ffffff;
                                    display:block;
                                    float:left;
                                    margin-top:10px;
                                    margin-left:10px;
                                    
                                    box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                                    -moz-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                                    -webkit-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                                }
                                
                                .m-right-image-content{
                                    display:none;
                                    float:left;
                                    clear:both;
                                }
                                
                                .m-right-image-content input{
                                    padding:5px;
                                    margin-top:5px;
                                    width:200px;
                                }
                                
                                #m-right-image:checked ~ .m-right-image-content{
                                    display:block;
                                }
                                
                                table.layout{
                                    border:none;
                                    width:1000px;
                                    border-collapse:collapse;
                                }

                                table.layout tr{

                                }

                                table.layout tr td{
                                    border:2px solid #8c8c8c;
                                    padding:10px;
                                }

                                table.layout tr.top{
                                    height:40px;
                                    vertical-align:middle;
                                }

                                table.layout tr td.middle-left{
                                    vertical-align:top;
                                }                                   

                                table.layout tr td.middle-right{
                                    width:35%;
                                }
                                
                                table.layout tr td.middle-right label{
                                    float:left;
                                    clear:both;
                                }

                                table.layout tr.bottom{
                                    height:40px;
                                    vertical-align:middle;
                                }
                                
                                td.middle-left ul{
                                    overflow:hidden;
                                    border-bottom:1px solid #da3;
                                }
                                
                                td.middle-left ul li{
                                    float:left;
                                    overflow:hidden;
                                    padding:10px 3px;
                                }
                                
                                td.middle-left ul li label{
                                    margin-bottom:5px;
                                }
                                
                                </style>';
                
                
                
                if ($this->_isMenuContextExist($id_menu)){
                    //UPDATE
                    $menuConfig = $this->_getMenuContextConfig($id_menu);
                    $top_radios = $menuConfig['top'];
                    $m_right_radios = $menuConfig['m_right'];
                    $m_left_radios = $menuConfig['m_left'];
                    $bottom_radios = $menuConfig['bottom'];
                    
                    $update = true;
                    
                }else{
                    //ADD - Defaults
                    $top_radios = "top_hide";
                    $m_right_radios = "right_hide";
                    $m_left_radios = "left_hide";
                    $bottom_radios = "bottom_manufacturers";
                    
                    $update = false;
                }
                    $menu_name = $this->_getMenuName($id_menu, $this->context->language->id);
                    
                    
                    
                    $this->_html .= '<fieldset class="nice-layout"><legend><img src="'.$this->_path.'/img/tab.png" alt="" title="" />'.$this->l('Menu Editor').' - '.$menu_name[0]['name'].'</legend>
                        
                                    <form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
                                    <input type="hidden" name="hidden_id_menu" value="'.$id_menu.'">
                                    <div>
                                    <table class="layout">
                                        <tr class="top">
                                            <td colspan=2>
                                                <ul class="top_bottom_radios">
                                                    <li>
                                                        <input type="radio" name="top_option" id="top_manufacturers" class="hide" value="top_manufacturers"'. (isset($top_radios) && $top_radios == "top_manufacturers" ? ' checked="checked"' : '') .'>
                                                        <label for="top_manufacturers" class="t selectable">Show Manufacturers List</label>
                                                        
                                                        <input type="radio" name="top_option" id="top_suppliers" class="hide" value="top_suppliers"'. (isset($top_radios) && $top_radios == "top_suppliers" ? ' checked="checked"' : '') .'>
                                                        <label for="top_suppliers" class="t selectable">Show Suppliers List</label>
                                                        
                                                        <input type="radio" name="top_option" id="top_hide" class="hide" value="top_hide"'. (isset($top_radios) && $top_radios == "top_hide" ? ' checked="checked"' : '') .'>
                                                        <label for="top_hide" class="t selectable">Hide Top Part</label>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr class="middle">
                                            <td class="middle-left">
                                                <script type="text/javascript">
                                                    $(document).ready(function(){
                                                    
                                                        $(".middle-left input").each(function(){
                                                            if ($(this).attr("checked") == "checked"){
                                                                id = "#" + $(this).attr("id") + "-content";
                                                                $(id).toggleClass("hidden");
                                                            }
                                                        });
                                                        
                                                        $(".hascontent").click(function(){
                                                            id = "#" + $(this).attr("for") + "-content";
                                                            $(".hideall").click();
                                                            $(id).toggleClass("hidden");
                                                        });
                                                        
                                                        $(".hideall").click(function(){
                                                            $(".actual-content div").addClass("hidden");
                                                        });
                                                        
                                                        $("#add-product-select").click(function(){
                                                            value = prompt("'.$this->l('Enter your product ID').'");
                                                            if (value == null || value == "" || isNaN(value)){
                                                                return;
                                                            }
                                                            text = "'.$this->l('Product ID').' "+value;
                                                            value = "product_"+value;
                                                            $("#product-select").append("<option value=\""+value+"\">"+text+"</option>");

                                                            serialize();
                                                        });

                                                        $("#remove-product-select").click(function(){
                                                            $("#product-select option:selected").each(function(i){
                                                                $(this).remove();

                                                                serialize();
                                                                return false;
                                                            });
                                                        });

                                                        function serialize(){
                                                            var options = "";
                                                            $("#product-select option").each(function(i){
                                                                options += $(this).val() + ",";
                                                            });
                                                            $("#products-serialized").val(options);
                                                        }

                                                    });
                                                </script>
                                            
                                                <ul>
                                                    <li>
                                                    
                                                        <input type="radio" name="m-left-option" id="m-left-categories" class="hide" value="m-left-categories"'. (isset($m_left_radios) && $m_left_radios == "left_categories" ? ' checked="checked"' : '') .'>
                                                        <label for="m-left-categories" class="t selectable hascontent">Show Categories</label>
                                                        
                                                    </li><li>
                                                    
                                                        <input type="radio" name="m-left-option" id="m-left-products" class="hide" value="m-left-products"'. (isset($m_left_radios) && $m_left_radios == "left_products" ? ' checked="checked"' : '') .'>
                                                        <label for="m-left-products" class="t selectable hascontent">Show Products</label>
                                                        
                                                    </li><li>  

                                                        <input type="radio" name="m-left-option" id="m-left-cms-list" class="hide" value="m-left-cms-list"'. (isset($m_left_radios) && $m_left_radios == "left_cms_list" ? ' checked="checked"' : '') .'>
                                                        <label for="m-left-cms-list" class="t selectable hascontent">Show CMS Links</label>
                                                            
                                                   </li><li>
                                                        
                                                        <input type="radio" name="m-left-option" id="m-left-cms-page" class="hide" value="m-left-cms-page"'. (isset($m_left_radios) && $m_left_radios == "left_cms_page" ? ' checked="checked"' : '') .'>
                                                        <label for="m-left-cms-page" class="t selectable hascontent">Show CMS Page</label>

                                                    </li><li>
                                                        
                                                        <input type="radio" name="m-left-option" id="m-left-hide" class="hide" value="m-left-hide"'. (isset($m_left_radios) && $m_left_radios == "left_hide" ? ' checked="checked"' : '') .'>
                                                        <label for="m-left-hide" class="t selectable hideall">Hide Left Part</label>
                                                        
                                                    </li>
                                                    
                                                </ul>
                                                <div class="actual-content">

                                                    <div id="m-left-categories-content" class="hidden">
                                                        <select class="multiple-select" multiple="multiple" id="category-select" name="category-select[]">';
                                                            if ($update){
                                                                $context = $this->_getContext($id_menu);
                                                                if (isset($context['m_left']['CAT'])){
                                                                    $selectedlist = $context['m_left']['CAT'];
                                                                    $this->_getCategories(1, (int)$this->context->language->id, (int)Shop::getContextShopID(), true, $selectedlist);
                                                                }else{
                                                                    $this->_getCategories(1, (int)$this->context->language->id, (int)Shop::getContextShopID(), true);
                                                                }
                                                            }else{
                                                                    $this->_getCategories(1, (int)$this->context->language->id, (int)Shop::getContextShopID(), true);
                                                                }
                                                            

                $this->_html .=                        '</select>
                                                    </div>


                                                    <div id="m-left-products-content" class="hidden">
                                                        <select class="multiple-select" multiple="multiple" id="product-select" name="product-select[]">';
                                                            if ($update){
                                                                $context = $this->_getContext($id_menu);
                                                                if (isset($context['m_left']['PRD'])){
                                                                    $productlist = $context['m_left']['PRD'];
                                                                    foreach($productlist as $key => $value){
                                                                        $this->_html .= '<option value="product_'.$value.'">'.$this->l("Product ID").' '.$value.'</option>';
                                                                    }
                                                                    
                                                                }
                                                            }
                 $this->_html .=                        '</select>
                                                        <div style="display:none">
                                                            <input type="text" id="products-serialized" name="products-serialized" ';
                                                            if ($update){
                                                                if (isset($context['m_left']['PRD'])){
                                                                    $this->_html .= 'value="';
                                                                    foreach($productlist as $key => $value){
                                                                        $this->_html .= 'product_'.$value.',';
                                                                    }
                                                                }
                                                            }
                 $this->_html .=                            '" />
                                                        </div>
                                                        
                                                        <a id="add-product-select">Add Product</a>
                                                        <a id="remove-product-select">Remove Product</a>

                                                    </div>

                                                    <div id="m-left-cms-list-content" class="hidden">
                                                        <select class="multiple-select" multiple="multiple" id="cms-list-select" name="cms-list-select[]">';
                                                            if ($update){
                                                                $context = $this->_getContext($id_menu);
                                                                if (isset($context['m_left']['CMSL'])){
                                                                    $selectedlist = $context['m_left']['CMSL'];
                                                                    $this->_getCmsLinks(0, 1, $this->context->language->id, $selectedlist);
                                                                }else{
                                                                    $this->_getCmsLinks(0, 1, $this->context->language->id);
                                                                }
                                                            }else{
                                                                    $this->_getCmsLinks(0, 1, $this->context->language->id);
                                                                }
                                                            

                $this->_html .=                        '</select>
                                                    </div>

                                                    <div id="m-left-cms-page-content" class="hidden">
                                                        <select class="select" id="cms-page-select" name="cms-page-select">';
                                                            if ($update){
                                                                $context = $this->_getContext($id_menu);
                                                                if (isset($context['m_left']['CMSP'])){
                                                                    $selectedlist = $context['m_left']['CMSP'];
                                                                    $this->_getCmsPages(0, 1, $this->context->language->id, $selectedlist);
                                                                }else{
                                                                    $this->_getCmsPages(0, 1, $this->context->language->id);
                                                                }
                                                            }else{
                                                                    $this->_getCmsPages(0, 1, $this->context->language->id);
                                                                }

                $this->_html .=                        '</select>
                                                    </div>


                                                </div>
                                            </td>




                                            <td class="middle-right">
                                                <ul>
                                                    <li>
                                                        <input type="radio" name="m-right-option" id="m-right-featured" class="hide" value="m-right-featured"'. (isset($m_right_radios) && $m_right_radios == "right_featured" ? ' checked="checked"' : '') .'>
                                                        <label for="m-right-featured" class="t selectable">Show Random Featured Product</label>
                                                        
                                                        <input type="radio" name="m-right-option" id="m-right-image" class="hide" value="m-right-image"'. (isset($m_right_radios) && $m_right_radios == "right_image" ? ' checked="checked"' : '') .'>
                                                        <label for="m-right-image" class="t selectable">Show Custom Image</label>
                                                        <div class="m-right-image-content">
                                                            <span class="inf">'.$this->l("Enter the URL of the Image").'</span>
                                                            <input id="custom-image" name="custom-image"';
                                                                if ($update){
                                                                    $context = $this->_getContext($id_menu);
                                                                    if (isset($context['m_right']['IMG'])){
                                                                        $img = $context['m_right']['IMG'];
                                                                        $this->_html .= ' value="'.$img.'"';
                                                                    }
                                                                }
                  $this->_html .=                           '/>
                                                        </div>
                                                        
                                                        <input type="radio" name="m-right-option" id="m-right-hide" class="hide" value="m-right-hide"'. (isset($m_right_radios) && $m_right_radios == "right_hide" ? ' checked="checked"' : '') .'>
                                                        <label for="m-right-hide" class="t selectable">Hide Right Part</label>
                                                    </li>
                                                </ul>
                                                
                                            </td>
                                            
                                        </tr>
                                        <tr class="bottom">
                                            <td colspan=2>
                                                <ul class="top_bottom_radios">
                                                    <li>
                                                        <input type="radio" name="bottom_option" id="bottom_manufacturers" class="hide" value="bottom_manufacturers"'. (isset($bottom_radios) && $bottom_radios == "bottom_manufacturers" ? ' checked="checked"' : '') .'>
                                                        <label for="bottom_manufacturers" class="t selectable">Show Manufacturers List</label>
                                                        
                                                        <input type="radio" name="bottom_option" id="bottom_suppliers" class="hide" value="bottom_suppliers"'. (isset($bottom_radios) && $bottom_radios == "bottom_suppliers" ? ' checked="checked"' : '') .'>
                                                        <label for="bottom_suppliers" class="t selectable">Show Suppliers List</label>
                                                        
                                                        <input type="radio" name="bottom_option" id="bottom_hide" class="hide" value="bottom_hide"'. (isset($bottom_radios) && $bottom_radios == "bottom_hide" ? ' checked="checked"' : '') .'>
                                                        <label for="bottom_hide" class="t selectable">Hide Bottom Part</label>
                                                    </li>
                                                    <li></li>
                                                    <li></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                    </div>
                                    
                                    <div class="margin-form" style="margin-top:33px;">
                                    <input type="submit" name="saveMenuEditor" id="saveMenuEditor" class="button" value="'.$this->l('Save').'">
                                    <a style="font-weight:bold;text-decoration:underline;margin-left:10px;" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'">Go back</a>
                                    </div>
                                </form>
                            </fieldset>';
                    return $this->_html;
                    
                
            }
        }
        
        
    }
    
    
        
/*-------------------------------------------------------------*/
/*  VALIDATION
/*-------------------------------------------------------------*/          

    private function _validate(){
        $errors = array();
        
        
            $languages = Language::getLanguages(false);
            foreach ($languages as $language){
                if (strlen(Tools::getValue('name_'.$language['id_lang'])) > 40){
                    $errors[] = $this->l('Title is too long');
                }
            }

            $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
            if (strlen(Tools::getValue('name_'.$id_lang_default)) == 0){
                $errors[] = $this->l('Title cannot be empty in default language');
            }
            
            if (strlen(Tools::getValue('link')) > 0){
                if ( !Validate::isUrl(Tools::getValue('link')) ){
                    $errors[] = $this->l('Please enter a proper link!');
                }
            }
        
        
        if (count($errors)){
            return $errors;
        }

        return null;
    }

    
        
/*-------------------------------------------------------------*/
/*  PROCESS
/*-------------------------------------------------------------*/          

    private function _process(){
        $errors = array();
                               
        $position_array = $this->_getLastPosition($this->context->shop->id);
        $position_values = array_values($position_array);
        $position = array_shift($position_values);

        if ($position == NULL){
            $position = 1;
        }else{
            $position++;
        }

        $active = 1;

        $languages = Language::getLanguages(false);
                
        $first = true;

        foreach ($languages as $language)
        {
            if (Tools::getValue('name_'.$language['id_lang']) != '')
            {
                $name[$language['id_lang']] = Tools::getValue('name_'.$language['id_lang']);
                if ($first)
                {
                    $first_lang = $name[$language['id_lang']];
                    $first = false;
                }
            }
            else
            {
                if ($first == false)
                {
                    $name[$language['id_lang']] = $first_lang;
                }
                else
                {
                    $name[$language['id_lang']] = "";
                }
            }
        }

        if (!$errors){
            /* Adds */
            if (!Tools::getValue('editMenu')){
                if (!$this->_addMenu($this->context->shop->id, $position, $active, $name, Tools::getValue('link'))){
                    $errors[] = $this->displayError($this->l('Menu could not be added'));
                }
            } /* Update */
            elseif (!$this->_updateMenu($this->context->shop->id, Tools::getValue('editMenu'), $name, Tools::getValue('link'))){
                $errors[] = $this->displayError($this->l('Menu could not be updated'));
            }
        }
                
                
        if (count($errors)){
            return $errors;
        }
        
        return null;
    }
        

/*-------------------------------------------------------------*/
/*  GET LAST MENU POSITION
/*-------------------------------------------------------------*/
        
    private function _getLastPosition($id_shop){
        $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                    SELECT MAX(`position`)
                    FROM '._DB_PREFIX_.'megamenu_menus
                    ');

        return $response;
    }

/*-------------------------------------------------------------*/
/*  IS MENU EXIST
/*-------------------------------------------------------------*/
        
    private function _isMenuExist($id_menu){
        $response = 'SELECT `id_menu`
                    FROM `'._DB_PREFIX_.'megamenu_menus`
                    WHERE `id_menu` = '.$id_menu;
	$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($response);
	return ($row);
    }
    
/*-------------------------------------------------------------*/
/*  IS MENU CONTEXT EXIST
/*-------------------------------------------------------------*/
        
    private function _isMenuContextExist($id_menu){
        $response = 'SELECT `id_menu`
                    FROM `'._DB_PREFIX_.'megamenu_menus_context`
                    WHERE `id_menu` = '.$id_menu;
	$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($response);
	return ($row);
    }
    
/*-------------------------------------------------------------*/
/*  GET MENU CONTEXT CONFIG
/*-------------------------------------------------------------*/
        
    private function _getMenuContextConfig($id_menu){
        $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                    SELECT *
                    FROM `'._DB_PREFIX_.'megamenu_menus_context`
                    WHERE `id_menu` = '.$id_menu
                    );
        
	if ($response){
            $menuConfig = array();

            $menuConfig['top'] = $response[0]['top'];
            $menuConfig['bottom'] = $response[0]['bottom'];

            $m_left = explode("_", $response[0]['m_left']);

            switch ($m_left[0]) {
                case 'CAT':
                    $menuConfig['m_left'] = "left_categories";
                    break;

                case 'PRD':
                    $menuConfig['m_left'] = "left_products";
                    break;

                case 'CMSL':
                    $menuConfig['m_left'] = "left_cms_list";
                    break;

                case 'CMSP':
                    $menuConfig['m_left'] = "left_cms_page";
                    break;

                case 'HIDE':
                    $menuConfig['m_left'] = "left_hide";
                    break;
            }

            $m_right = explode("_", $response[0]['m_right']);
            switch ($m_right[0]) {
                case 'IMG':
                    $menuConfig['m_right'] = "right_image";
                    break;

                case 'RNDF':
                    $menuConfig['m_right'] = "right_featured";
                    break;

                case 'RND':
                    $menuConfig['m_right'] = "right_random";
                    break;

                case 'HIDE':
                    $menuConfig['m_right'] = "right_hide";
                    break;
            }
            
            return $menuConfig;
        }
    }

/*-------------------------------------------------------------*/
/*  GET MENU CONTEXT
/*-------------------------------------------------------------*/
    private function _getContext($id_menu){
       if ($this->_isMenuContextExist($id_menu)){             
            
            $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                        SELECT *
                        FROM `'._DB_PREFIX_.'megamenu_menus_context`
                        WHERE `id_menu` = '.$id_menu
                        );

            $context['top'] = $response[0]['top'];
            $context['bottom'] = $response[0]['bottom'];

            $m_left_array = explode("_", $response[0]['m_left']);
            if (isset($m_left_array[1])){
                $m_left[$m_left_array[0]] = array_map('intval', explode(":",$m_left_array[1]));
            }else{
                $m_left[$m_left_array[0]] = 0;
            }

            $context['m_left'] = $m_left;

            $m_right_array = explode("_", $response[0]['m_right'], 2);
            if (isset($m_right_array[1])){
                $m_right[$m_right_array[0]] = $m_right_array[1];
            }else{
                $m_right[$m_right_array[0]] = 0;
            }

            $context['m_right'] = $m_right;

            return $context;
        
        }
    }

/*-------------------------------------------------------------*/
/*  GET MENU NAMES
/*-------------------------------------------------------------*/
        
    private function _getNameLang($id_menu){
        $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
                    'SELECT `id_lang`, `name`
                    FROM `'._DB_PREFIX_.'megamenu_menus_lan`
                    WHERE `id_menu` = '.$id_menu);
	
	return ($response);
    }
    
/*-------------------------------------------------------------*/
/*  GET MENU NAME WITH USING MENU ID AND LANG ID
/*-------------------------------------------------------------*/
        
    private function _getMenuName($id_menu, $id_lang){
        $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
                    'SELECT `id_menu`, `id_lang`, `name` 
                    FROM `'._DB_PREFIX_.'megamenu_menus_lan`
                    WHERE `id_menu` = '.$id_menu.'
                        AND `id_lang` = '.$id_lang.'
                    ');
	
	return ($response);
    }

    
/*-------------------------------------------------------------*/
/*  GET MENU COUNT
/*-------------------------------------------------------------*/
        
    private function _getMenuCount(){
        $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
                    'SELECT COUNT(id_menu)
                    FROM `'._DB_PREFIX_.'megamenu_menus`
                    ');
	
	return (int)$response[0]['COUNT(id_menu)'];
    }

    
/*-------------------------------------------------------------*/
/*  GET MENUS
/*-------------------------------------------------------------*/
        
    private function _getMenus($id_shop){
        $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
                    'SELECT '._DB_PREFIX_.'megamenu_menus.id_menu, '._DB_PREFIX_.'megamenu_menus.active, '._DB_PREFIX_.'megamenu_menus.position, '._DB_PREFIX_.'megamenu_menus_lan.id_menu, '._DB_PREFIX_.'megamenu_menus_lan.id_lang, '._DB_PREFIX_.'megamenu_menus_lan.name 
                    FROM `'._DB_PREFIX_.'megamenu_menus`, `'._DB_PREFIX_.'megamenu_menus_lan`
                    WHERE '._DB_PREFIX_.'megamenu_menus.id_shop = '.$id_shop.'
                        AND '._DB_PREFIX_.'megamenu_menus.id_menu = '._DB_PREFIX_.'megamenu_menus_lan.id_menu
                    ORDER BY '._DB_PREFIX_.'megamenu_menus.position ASC
                    ');
	
        if ($response){
            return $response;
        }
        else{
            return NULL;
        }
    }
    
    
/*-------------------------------------------------------------*/
/*  GET MENU WITH USING 'id_menu'
/*-------------------------------------------------------------*/    
    
    private function _getMenu($id_menu){
                     
       $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                      SELECT `id_menu`, `id_shop`, `active`, `position`
                      FROM '._DB_PREFIX_.'megamenu_menus
                      WHERE `id_menu` = '.$id_menu);
       return $response;
    }
    
/*-------------------------------------------------------------*/
/*  GET MENU LINK WITH USING 'id_menu'
/*-------------------------------------------------------------*/    
    
    private function _getMenuBasicLink($id_menu){
                     
       $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                      SELECT `link`
                      FROM '._DB_PREFIX_.'megamenu_menus
                      WHERE `id_menu` = '.$id_menu);
       if ($response){
           return $response['link'];
       }else{
           return false;
       }
    }

    
    
/*-------------------------------------------------------------*/
/*  ADD MENU
/*-------------------------------------------------------------*/
        
    private function _addMenu($id_shop, $position, $active, $name, $link){
        
        $response = Db::getInstance()->execute('
                    INSERT INTO `'._DB_PREFIX_.'megamenu_menus` (`active`, `position`, `id_shop`, `link`)
                    VALUES('.(int)$active.', '.(int)$position.', '.(int)$id_shop.', \''.$link.'\')'
                    );
        
        $id =  Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                    SELECT MAX(`id_menu`)
                    FROM '._DB_PREFIX_.'megamenu_menus
                    ');
        
        $id = $id['MAX(`id_menu`)'];
        
        foreach ($name as $key => $value){
                     
            $response &= Db::getInstance()->execute('
                    INSERT INTO `'._DB_PREFIX_.'megamenu_menus_lan` (`id_menu`, `id_lang`, `name`)
                    VALUES('.(int)$id.', '.(int)$key.', \''.$value.'\')'
                    );
            
        }
        
        return $response;
    }
    
/*-------------------------------------------------------------*/
/*  UPDATE MENU
/*-------------------------------------------------------------*/
        
    private function _updateMenu($id_shop, $id_menu, $name, $link){
        
         foreach ($name as $key => $value){
        
            $response = Db::getInstance()->execute('
                        UPDATE '._DB_PREFIX_.'megamenu_menus_lan
                        SET `name` = \''.$value.'\'
                        WHERE `id_menu` = '.$id_menu.'
                        AND `id_lang` = '.$key.'
                        ');
        
         }
         
         
        $response &= Db::getInstance()->execute('
                   UPDATE '._DB_PREFIX_.'megamenu_menus
                   SET `link` = \''.$link.'\'
                   WHERE `id_menu` = '.$id_menu.'
                   AND `id_shop` = '.(int)$id_shop.'
                   ');

         
        return $response;
    }
    
    
/*-------------------------------------------------------------*/
/*  DELETE MENU
/*-------------------------------------------------------------*/
        
    private function _delMenu($id_menu){
         
        if ($this->_isMenuExist($id_menu)){
              $menu = $this->_getMenu($id_menu);
              $curr_position = $menu['position'];
              
              $delete = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                        DELETE FROM '._DB_PREFIX_.'megamenu_menus
                        WHERE `id_menu` = '.$id_menu.'
                       ');
              
              $delete &= Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                        DELETE FROM '._DB_PREFIX_.'megamenu_menus_lan
                        WHERE `id_menu` = '.$id_menu.'
                        ');
              
              if (!$delete){
                  return $this->displayError($this->l('Error occured while deleting the menu from database'));
              }
              
              $id_shop = $this->context->shop->id;
              
              $next_rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                           SELECT `id_menu`, `position`
                           FROM '._DB_PREFIX_.'megamenU_menus
                           WHERE `id_shop` = '.$id_shop.'
                           AND `position` > '.$curr_position
                           );
            
            $new_position = $curr_position;
            foreach ($next_rows as $next_row){
                
                $fix_positions = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                                 UPDATE '._DB_PREFIX_.'megamenu_menus
                                 SET `position` = '.$new_position.'
                                 WHERE `id_menu` = '.$next_row['id_menu'].'
                                 ');
                
                $new_position++;
            }
            
            
            return $this->displayConfirmation($this->l('Menu deleted!'));
        }
        
    }

    
/*-------------------------------------------------------------*/
/*  MOVE LEFT
/*-------------------------------------------------------------*/
        
    private function _moveLeft($id_menu){
                
        if ($this->_isMenuExist($id_menu)){
            
            $curr_menu = $this->_getMenu($id_menu);
            $id_shop = $curr_menu['id_shop'];
            $curr_position = $curr_menu['position'];
            $pre_position = $curr_position - 1;
            
            $pre_menu_id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                             SELECT `id_menu`
                             FROM '._DB_PREFIX_.'megamenu_menus
                             WHERE `id_shop` = '.$id_shop.'
                             AND `position` = '.$pre_position.'
                             ');
            
            if (!$pre_menu_id){
                return $this->displayError($this->l('Invalid operation!'));
            }
            
            $update_pre_menu_position = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                                          UPDATE '._DB_PREFIX_.'megamenu_menus
                                          SET `position` = '.$curr_position.'
                                          WHERE `id_menu` = '.$pre_menu_id['id_menu'].'
                                          ');
            
            $update_curr_menu_position = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                                          UPDATE '._DB_PREFIX_.'megamenu_menus
                                          SET `position` = '.$pre_position.'
                                          WHERE `id_menu` = '.$id_menu.'
                                          ');
        }        
        
        if ($update_pre_menu_position && $update_curr_menu_position){
                return $this->displayConfirmation($this->l('Menu position updated!'));
        }else{
                return $this->displayError($this->l('Menu position could not be updated!'));
        }
    }
    
    
/*-------------------------------------------------------------*/
/*  MOVE RIGHT
/*-------------------------------------------------------------*/
        
    private function _moveRight($id_menu){
                
        if ($this->_isMenuExist($id_menu)){
            
            $curr_menu = $this->_getMenu($id_menu);
            $id_shop = $curr_menu['id_shop'];
            $curr_position = $curr_menu['position'];
            $next_position = $curr_position + 1;
            
            $next_menu_id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                             SELECT `id_menu`
                             FROM '._DB_PREFIX_.'megamenu_menus
                             WHERE `id_shop` = '.$id_shop.'
                             AND `position` = '.$next_position.'
                             ');
            
            if (!$next_menu_id){
                return $this->displayError($this->l('Invalid operation!'));
            }
            
            $update_next_menu_position = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                                          UPDATE '._DB_PREFIX_.'megamenu_menus
                                          SET `position` = '.$curr_position.'
                                          WHERE `id_menu` = '.$next_menu_id['id_menu'].'
                                          ');
            
            $update_curr_menu_position = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                                          UPDATE '._DB_PREFIX_.'megamenu_menus
                                          SET `position` = '.$next_position.'
                                          WHERE `id_menu` = '.$id_menu.'
                                          ');
        }        
        
        if ($update_next_menu_position && $update_curr_menu_position){
                return $this->displayConfirmation($this->l('Menu position updated!'));
        }else{
                return $this->displayError($this->l('Menu position could not be updated!'));
        }
    }
    
    

/*-------------------------------------------------------------*/
/*  GET CATEGORIES
/*-------------------------------------------------------------*/       
    private function _getCategories($id_category = 1, $id_lang = false, $id_shop = false, $recursive = true, $selectedlist = array()){
        
            $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
            $category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);

            if (is_null($category->id))
                    return;

            if ($recursive)
            {
                    $children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
                    $spacer = str_repeat('&nbsp;', 5 * (int)$category->level_depth);
            }

            $shop = (object) Shop::getShop((int)$category->getShopID());           
            
            $selected = NULL;
            if (count($selectedlist) > 0){
                foreach ($selectedlist as $key => $value){
                    if ($value == (int)$category->id){
                        $selected = 'selected="selected"';
                        break;
                    }
                }
            }
           
            
            $this->_html .= '<option value="category_'.(int)$category->id.'" '.(isset($selected) ? $selected : '').'>'.(isset($spacer) ? $spacer : '').$category->name.' ('.$shop->name.')</option>';

            if (isset($children) && count($children))
                    foreach ($children as $child)
                    {
                            $this->_getCategories((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop'], true, $selectedlist);
                    }
     }
     
     
     
/*-------------------------------------------------------------*/
/*  GET CMS Links
/*-------------------------------------------------------------*/
    private function _getCmsLinks($parent = 0, $depth = 1, $id_lang = false, $selectedlist = array()){
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		$categories = $this->_getCmsCategories(false, (int)$parent, (int)$id_lang);
		$pages = $this->_getCmsPagesDB((int)$parent, false, (int)$id_lang);

		$spacer = str_repeat('&nbsp;', 5 * (int)$depth);

		foreach ($categories as $category){
                    
                    $this->_html .= '<option value="cmslcat_'.$category['id_cms_category'].'" style="font-weight: bold;" '.(isset($selected) ? $selected : '').'>'.$spacer.$category['name'].'</option>';
                    $this->_getCmsLinks($category['id_cms_category'], (int)$depth + 1, (int)$id_lang, $selectedlist);
		}
                
		foreach ($pages as $page){
                    $selected = NULL;
                    if (count($selectedlist) > 0){
                        foreach ($selectedlist as $key => $value){
                            if ($value == $page['id_cms']){
                               $selected = 'selected="selected"';
                               break;
                            }
                        }
                    }
                    $this->_html .= '<option value="cmsl_'.$page['id_cms'].'" '.(isset($selected) ? $selected : '').'>'.$spacer.$page['meta_title'].'</option>';
                }
	}
        
        
/*-------------------------------------------------------------*/
/*  GET CMS Pages
/*-------------------------------------------------------------*/
    private function _getCmsPages($parent = 0, $depth = 1, $id_lang = false, $selectedlist = array()){
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		$categories = $this->_getCmsCategories(false, (int)$parent, (int)$id_lang);
		$pages = $this->_getCmsPagesDB((int)$parent, false, (int)$id_lang);

		foreach ($categories as $category)
		{
			//$this->_html .= '<option value="cmslcat_'.$category['id_cms_category'].'" style="font-weight: bold;">'.$spacer.$category['name'].'</option>';
			$this->_getCmsPages($category['id_cms_category'], (int)$depth + 1, (int)$id_lang, $selectedlist);
		}

		foreach ($pages as $page){
                    $selected = NULL;
                        if (count($selectedlist) > 0){
                            foreach ($selectedlist as $key => $value){
                                if ($value == $page['id_cms']){
                                   $selected = 'selected="selected"';
                                   break;
                                }
                            }
                        }
			$this->_html .= '<option value="cmsp_'.$page['id_cms'].'" '.(isset($selected) ? $selected : '').'>'.$page['meta_title'].'</option>';
                }
                    
                        
	}
     
        
        
/*-------------------------------------------------------------*/
/*  GET CMS Categories
/*-------------------------------------------------------------*/
	private function _getCmsCategories($recursive = false, $parent = 1, $id_lang = false){
            
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		if ($recursive === false)
		{
			$sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'cms_category` bcp
				INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = '.(int)$id_lang.'
				AND bcp.`id_parent` = '.(int)$parent;

			return Db::getInstance()->executeS($sql);
		}
		else
		{
			$sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'cms_category` bcp
				INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = '.(int)$id_lang.'
				AND bcp.`id_parent` = '.(int)$parent;

			$results = Db::getInstance()->executeS($sql);
			foreach ($results as $result)
			{
				$sub_categories = $this->_getCmsCategories(true, $result['id_cms_category'], (int)$id_lang);
				if ($sub_categories && count($sub_categories) > 0)
					$result['sub_categories'] = $sub_categories;
				$categories[] = $result;
			}

			return isset($categories) ? $categories : false;
		}

	}
        
        
/*-------------------------------------------------------------*/
/*  GET CMS Pages From DB
/*-------------------------------------------------------------*/
	private function _getCmsPagesDB($id_cms_category, $id_shop = false, $id_lang = false){
            
		$id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		$sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
			FROM `'._DB_PREFIX_.'cms` c
			INNER JOIN `'._DB_PREFIX_.'cms_shop` cs
			ON (c.`id_cms` = cs.`id_cms`)
			INNER JOIN `'._DB_PREFIX_.'cms_lang` cl
			ON (c.`id_cms` = cl.`id_cms`)
			WHERE c.`id_cms_category` = '.(int)$id_cms_category.'
			AND cs.`id_shop` = '.(int)$id_shop.'
			AND cl.`id_lang` = '.(int)$id_lang.'
			AND c.`active` = 1
			ORDER BY `position`';

		return Db::getInstance()->executeS($sql);
	}
        
        
/*-------------------------------------------------------------*/
/*  PROCESS INFORMATIN COMING FROM MENU EDITOR
/*-------------------------------------------------------------*/    
    private function _processMenuEditorSave($id_menu){
        $errors = array();
        
        if (Tools::isSubmit('top_option')){
            $top_option = Tools::getValue('top_option');
        }else{
            $errors[]=$this->displayError($this->l("Error in Top Part!"));
        }
        
        
        if (Tools::isSubmit('bottom_option')){
            $bottom_option = Tools::getValue('bottom_option');
        }else{
            $errors[]=$this->displayError($this->l("Error in Bottom Part!"));
        }
        
        
        $hide_m_left = false;
        
        if (Tools::isSubmit('m-left-option') && Tools::getValue('m-left-option') == "m-left-categories" && Tools::isSubmit('category-select')){
            $categories = Tools::getValue('category-select');
        }
        elseif (Tools::isSubmit('m-left-option') && Tools::getValue('m-left-option') == "m-left-products" && Tools::isSubmit('products-serialized')){
            $products = Tools::getValue('products-serialized');
            $products = explode(",", rtrim($products, ","));
        }
        elseif (Tools::isSubmit('m-left-option') && Tools::getValue('m-left-option') == "m-left-cms-list" && Tools::isSubmit('cms-list-select')){
            $cms_list = Tools::getValue('cms-list-select');
        }
        elseif (Tools::isSubmit('m-left-option') && Tools::getValue('m-left-option') == "m-left-cms-page" && Tools::isSubmit('cms-page-select')){
            $cms_page = Tools::getValue('cms-page-select');
        }
        elseif (Tools::isSubmit('m-left-option') && Tools::getValue('m-left-option') == "m-left-hide"){
            $hide_m_left = true;
        }else{
            $errors[]=$this->displayError($this->l("Error in Left Part!"));
        }
        
        
        if (Tools::isSubmit('m-right-option') && Tools::getValue('m-right-option') == 'm-right-image' && Tools::isSubmit('custom-image')){
            $right_option = Tools::getValue('m-right-option');
            $right_option_value = Tools::getValue('custom-image');
        }
        elseif (Tools::isSubmit('m-right-option') && Tools::getValue('m-right-option') != 'm-right-image'){
            $right_option = Tools::getValue('m-right-option');
        }
        else{
            $errors[]=$this->displayError($this->l("Error in Right Part!"));
        }
        
        
        //GET THINGS READY
        $top = Tools::safeOutput($top_option, false);
        $bottom = Tools::safeOutput($bottom_option, false);

        if (isset($categories) && $categories != ""){
            $m_left = "CAT_";
            foreach ($categories as $category){
                $category = explode("_", $category);
                $m_left .= $category[1] . ":";
            }
            $m_left = rtrim($m_left, ":");
        }

        elseif (isset($products) && $products != ""){
            $m_left = "PRD_";
            foreach ($products as $product){
                if($product != ""){
                    $product = explode("_", $product);
                    $m_left .=  $product[1] . ":";
                }
            }
            $m_left = rtrim($m_left, ":");
        }

        elseif (isset($cms_list) && $cms_list != ""){
            $m_left = "CMSL_";
            foreach ($cms_list as $cms){
                $cms = explode("_", $cms);
                $m_left .=  $cms[1] . ":";
            }
            $m_left = rtrim($m_left, ":");
        }

        elseif (isset($cms_page) && $cms_page != ""){
            $cms_page = explode("_", $cms_page);
            $m_left = "CMSP_" . $cms_page[1];
        }

        elseif ($hide_m_left){
            $m_left = "HIDE";
        }
        else{
            $errors[] = $this->displayError($this->l("Please select at least one item from left part and fill it or select 'Hide Left Part'"));
        }


        if (isset($right_option) && isset($right_option_value)){
            $m_right = "IMG_" . $right_option_value;
        }
        elseif(isset($right_option) && !isset($right_option_value) && $right_option == "m-right-featured"){
            $m_right = "RNDF_" . $right_option;
        }
        elseif(isset($right_option) && !isset($right_option_value) && $right_option == "m-right-random"){
            $m_right = "RND_" . $right_option;
        }
        elseif(isset($right_option) && !isset($right_option_value) && $right_option == "m-right-hide"){
            $m_right = "HIDE";
        }
        else{
            $errors[] = $this->displayError($this->l("Error while parsing Right Part data!"));
        }


        if (count($errors)){
             return implode('<br />', $errors);
        }
        
        if ($this->_isMenuContextExist($id_menu)){
            //UPDATE
            $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                        UPDATE '._DB_PREFIX_.'megamenu_menus_context
                        SET `top` = "'.$top.'",
                        `m_left` = "'.$m_left.'",
                        `m_right` = "'.$m_right.'",
                        `bottom` = "'.$bottom.'"
                        WHERE `id_menu` = '.$id_menu.'
                        ');
            
            if ($response){
                return $this->displayConfirmation($this->l("Menu Updated!"));
            }else{
                return $this->displayError($this->l("An error occured while updating the Menu Context to the DB"));
            }
            
        }else{
            //ADD
            $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                        INSERT INTO `'._DB_PREFIX_.'megamenu_menus_context`
                        (`id_menu`,`top`,`m_left`,`m_right`,`bottom`)
                        VALUES ('.$id_menu.',"'.$top.'","'.$m_left.'","'.$m_right.'","'.$bottom.'")
                        ');
            
            if ($response){
                return $this->displayConfirmation($this->l("Menu Saved!"));
            }else{
                return  $this->displayError($this->l("An error occured while adding the Menu Context to the DB"));
            }
        }
        
    }
        
/*-------------------------------------------------------------*/
/*  BUILD THE MENU
/*-------------------------------------------------------------*/          

    private function _buildMenu(){
        
        $this->_html = '<ul>';
        $rootMenu = $this->_getMenus($this->context->shop->id);
        $menu = array();
        
        if ($rootMenu){
            
            foreach ($rootMenu as $key => $value){

                if ($value['id_lang'] == $this->context->language->id){

                    if ($this->_isMenuContextExist($value['id_menu'])){
                        $context = $this->_getContext($value['id_menu']);

                        $rootLink = $this->_getMenuBasicLink($value['id_menu']);
                        if ($rootLink){
                            $menu[$value['name']]['link'] = $rootLink;
                        }else{
                            $menu[$value['name']]['link'] = "";
                        }

                        switch ($context['top']){
                            case "top_manufacturers":
                                $menu[$value['name']]['top']['top_manufacturers'] = Manufacturer::getManufacturers();
                                break;

                            case "top_suppliers":
                                $menu[$value['name']]['top']['top_suppliers'] = Supplier::getSuppliers();
                                break;

                            case "top_hide":
                                $menu[$value['name']]['top']['top_hide'] = "HIDE";
                                break;
                        }

                        foreach ($context['m_left'] as $firstkey => $firstvalue){
                            switch ($firstkey){
                                case "CAT":
                                    foreach ($firstvalue as $secondkey => $secondvalue){
                                        $category = New Category($secondvalue, $value['id_lang']);

                                        if ($category->level_depth > 1)
                                            $category_link = $category->getLink();
                                        else
                                            $category_link = $this->context->link->getPageLink('index');

                                        if (is_null($category->id))
                                            break;


                                        $is_intersected = array_intersect($category->getGroups(), $this->user_groups);

                                        if (!empty($is_intersected)){
                                            $menu[$value['name']]['m_left']['CAT'][$category->name]['name'] = $category->name;
                                            $menu[$value['name']]['m_left']['CAT'][$category->name]['link'] = $category_link;

                                            $children = Category::getChildren($secondvalue, $value['id_lang']);
                                                foreach ($children as $child){
                                                    $childCategory = New Category($child['id_category'], $value['id_lang']);
                                                    $childCategory_link = $childCategory->getLink();
                                                    
                                                    $is_intersected_children = array_intersect($childCategory->getGroups(), $this->user_groups);
                                                    if (!empty($is_intersected_children)){
                                                        $menu[$value['name']]['m_left']['CAT'][$category->name]['children'][$child['name']] = array(
                                                            'name' => $child['name'],
                                                            'link' => $childCategory_link
                                                        );
                                                    }
                                                    
                                                    
                                                    /*
                                                    $childrenStep2 = Category::getChildren($childCategory->id_category, $value['id_lang']);
                                                    foreach ($childrenStep2 as $childStep2){
                                                        $childStep2Category = New Category($childStep2['id_category'], $value['id_lang']);
                                                        $childStep2Category_link = $childStep2Category->getLink();
                                                        $menu[$value['name']]['m_left']['CAT'][$category->name]['children'][$child['name']]['children'][$childStep2['name']] = $childStep2Category_link;
                                                    }
                                                    */
                                                }
                                        }

                                    }
                                    break;

                                case "PRD":
                                    foreach ($firstvalue as $secondkey => $secondvalue){
                                        $product = new Product($secondvalue, false, $value['id_lang']);

                                        if ($product->active){
                                            $p_name = $product->name;

                                            $p_images = $product->getImages($value['id_lang']);
                                            foreach ($p_images as $p_image){
                                                if ($p_image['cover'] == 1){
                                                    $p_image_id = $p_image['id_image'];
                                                    break;
                                                }
                                            }

                                            $p_link = $product->getLink();
                                            $p_link_rewrite = $product->link_rewrite;

                                            $p_array = array(
                                                'name' => $p_name,
                                                'image_id' => $p_image_id,
                                                'link' => $p_link,
                                                'link_rewrite' => $p_link_rewrite
                                            );
                                            $menu[$value['name']]['m_left']['PRD'][$product->id] = $p_array;
                                        }

                                    }
                                    break;

                                case "CMSL":
                                    foreach ($firstvalue as $secondkey => $secondvalue){
                                        $cms = new CMS($secondvalue, $value['id_lang']);
                                        if ($cms->active){
                                            $c_title = $cms->meta_title;
                                            $c_links = $cms->getLinks($value['id_lang']);
                                                foreach ($c_links as $c_link){
                                                    if ($c_link['id_cms'] == $secondvalue){
                                                        $c_def_link = $c_link['link'];
                                                        break;
                                                    }
                                                }

                                            $menu[$value['name']]['m_left']['CMSL'][$c_title] = $c_def_link;
                                        }

                                    }
                                    break;

                                case "CMSP":
                                    foreach ($firstvalue as $secondkey => $secondvalue){
                                        $cms = new CMS($secondvalue, $value['id_lang']);
                                        if ($cms->active){
                                            $content = $cms->content;
                                            $menu[$value['name']]['m_left']['CMSP'][$cms->id] = $content;
                                        }
                                    }
                                    break;

                                case "HIDE":
                                    $menu[$value['name']]['m_left']['HIDE'] = 1;
                                    break;
                            }
                        }

                        foreach ($context['m_right'] as $firstkey => $firstvalue){
                            switch ($firstkey){
                                case "IMG":
                                    $menu[$value['name']]['m_right']['IMG'] = $firstvalue;
                                    break;

                                case "RNDF":
                                    $category = new Category($this->context->shop->getCategory(), $value['id_lang']);
                                    if ($category){
                                        $product = $category->getProducts($value['id_lang'], 1, 1, null, null, false, true, true);
                                        if ($product){
                                            $p_name = $product[0]['name'];
                                            $p_image_id = $product[0]['id_image'];

                                            $p_link = $product[0]['link'];
                                            $p_link_rewrite = $product[0]['link_rewrite'];

                                            $p_array = array(
                                                'name' => $p_name,
                                                'image_id' => $p_image_id,
                                                'link' => $p_link,
                                                'link_rewrite' => $p_link_rewrite
                                            );
                                            $menu[$value['name']]['m_right']['RNDF'] = $p_array;
                                        }
                                    }
                                    break;

                                 case "RND":
                                     $product = $this->_getRandomSpecial($value['id_lang']);
                                        if ($product){
                                            $p_name = $product['name'];
                                            $p_image_id = $product['id_image'];

                                            $p_link = $product['link'];
                                            $p_link_rewrite = $product['link_rewrite'];

                                            $p_array = array(
                                                'name' => $p_name,
                                                'image_id' => $p_image_id,
                                                'link' => $p_link,
                                                'link_rewrite' => $p_link_rewrite
                                            );
                                            $menu[$value['name']]['m_right']['RND'] = $p_array;
                                        }
                                     break;

                                  case "HIDE":
                                     $menu[$value['name']]['m_right']['HIDE'] = 1;
                                     break;
                            }

                        }

                        switch ($context['bottom']){
                            case "bottom_manufacturers":
                                $menu[$value['name']]['bottom']['bottom_manufacturers'] = Manufacturer::getManufacturers();
                                break;

                            case "bottom_suppliers":
                                $menu[$value['name']]['bottom']['bottom_suppliers'] = Supplier::getSuppliers();
                                break;

                            case "bottom_hide":
                                $menu[$value['name']]['bottom']['bottom_hide'] = "HIDE";
                                break;
                        }


                    }
                    else{
                        $menu[$value['name']] = "" ;

                        $rootLink = $this->_getMenuBasicLink($value['id_menu']);
                        if ($rootLink){
                            $menu[$value['name']]['link'] = $rootLink;
                        }else{
                            $menu[$value['name']]['link'] = "";
                        }
                    }

                }

            }
        }
               
        return $menu;
    }
 
/*-------------------------------------------------------------*/
/*  GET CATEGORIES FOR RESPONSIVE MENU
/*-------------------------------------------------------------*/    
           
        private function _getRespCategories($id_category = 1, $id_lang = false, $id_shop = false){
        
            $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
            $category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);

            if (is_null($category->id)){
                return;
            }

            $children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
            
            
            $class = "";
            if (isset($children) && count($children) && $category->level_depth > 1){
                $class .= "parent ";
            }

                        
            if ($category->level_depth > 1){
                $cat_link = $category->getLink();
            }else{
                $cat_link = $this->context->link->getPageLink('index');
            }
            
            $is_intersected = array_intersect($category->getGroups(), $this->user_groups);
                                    
            if (!empty($is_intersected)){
                if ($category->id == 1){
                     $this->_respMenu .= '<ul>';
                     $start_home = true;
                }
                
                $this->_respMenu .= '<li class="'.$class.'">';
                $this->_respMenu .= '<a href="'.$cat_link.'"><span>'.$category->name.'</span></a>';
            }
            
            if (isset($children) && count($children)){
                
                $this->_respMenu .= '<ul>';
                
                foreach ($children as $child){
                        $this->_getRespCategories((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop']);
                }

                $this->_respMenu .= '</ul>';
                
            }
            $this->_respMenu .= '</li>';
            
            if (isset($start_home) && $start_home){
                $this->_respMenu .= '</ul>';
            }
            return $this->_respMenu;
     }
    
    
    
/*-------------------------------------------------------------*/
/*  BUILD RESPONSIVE MENU
/*-------------------------------------------------------------*/          

    private function _buildResponsiveMenu(){
 
        return $this->_getRespCategories(1, (int)Context::getContext()->language->id, $id_shop = false);
        
    }
    
    
/*-------------------------------------------------------------*/
/*  GET RANDOM SPECIAL PRODUCT
/*-------------------------------------------------------------*/          

    private function _getRandomSpecial($id_lang){
        $product = new Product;
        $random = $product->getRandomSpecial($id_lang);
        
        if ($random){
           if ($random['active'] == 0){
               $this->_getRandomSpecial($id_lang);
           }
        }else{
            return NULL;
        }
               
        return $random;
    }
    
    
/*-------------------------------------------------------------*/
/*  PREPARE FOR HOOK
/*-------------------------------------------------------------*/          

    private function _prepHook($params){
        $this->user_groups = ($this->context->customer->isLogged() ? $this->context->customer->getGroups() : array(Configuration::get('PS_UNIDENTIFIED_GROUP')));
        $menu = $this->_buildMenu();
        $responsive_menu = $this->_buildResponsiveMenu();
        
        $this->smarty->assign('mega_menu', $menu);
        $this->smarty->assign('responsive_menu', $responsive_menu);
    }

    
    
/*-------------------------------------------------------------*/
/*  HOOK (displayTop)
/*-------------------------------------------------------------*/
        
    public function hookDisplayTop ($params){
        $this->_prepHook($params);
        
        $this->context->controller->addJqueryPlugin('hoverIntent');
        $this->context->controller->addJqueryPlugin('autumnmegamenu', $this->_path.'js/');
	$this->context->controller->addCSS($this->_path.'autumnmegamenu.css');
        
        return $this->display(__FILE__, 'autumnmegamenu.tpl');
    }
    
}

