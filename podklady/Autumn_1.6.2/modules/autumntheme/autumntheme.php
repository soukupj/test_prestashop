<?php
/* Autumn Theme - Main Theme Module - 2012 - Sercan YEMEN - twitter.com/sercan */
    
if (!defined('_PS_VERSION_'))
	exit;

class AutumnTheme extends Module{
    
    function __construct(){
        $this->name = 'autumntheme';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'Sercan YEMEN';
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);

        parent::__construct();

        $this->displayName = $this->l('Autumn Theme - Main Theme Module');
        $this->description = $this->l('Required by Autumn Theme');
    }
    

/*-------------------------------------------------------------*/
/*  INSTALL THE MODULE
/*-------------------------------------------------------------*/
    
    public function install(){
        if (parent::install() && $this->registerHook('displayTop') && $this->registerHook('displayHeader')){
            $response = Configuration::updateValue('AUTUMN_DEFAULT_CAT_VIEW', 'grid_view');
            $response &= Configuration::updateValue('AUTUMN_DEFAULT_IMG_SIZE', 'sqr_img');
            $response &= Configuration::updateValue('AUTUMN_SHOW_CAT_NAME', 0);
            $response &= Configuration::updateValue('AUTUMN_SHOW_CAT_DESC', 0);
            $response &= Configuration::updateValue('AUTUMN_SHOW_SUB_CATS', 0);
            $response &= Configuration::updateValue('AUTUMN_COLOR', 'da3b44');
            $response &= Configuration::updateValue('AUTUMN_BCOLOR', 'da3b44');
            $response &= Configuration::updateValue('AUTUMN_BHCOLOR', 'e6434c');
            $response &= Configuration::updateValue('AUTUMN_BBCOLOR', 'a40f18');
            $response &= Configuration::updateValue('AUTUMN_LOGOW', 160);
            return $response;
        }
        return false;
    }
    
    
/*-------------------------------------------------------------*/
/*  UNINSTALL THE MODULE
/*-------------------------------------------------------------*/    
    
    public function uninstall(){
        if (parent::uninstall()){
            $response = Configuration::deleteByName('AUTUMN_DEFAULT_CAT_VIEW');
            $response &= Configuration::deleteByName('AUTUMN_DEFAULT_IMG_SIZE');
            $response &= Configuration::deleteByName('AUTUMN_SHOW_CAT_NAME');
            $response &= Configuration::deleteByName('AUTUMN_SHOW_CAT_DESC');
            $response &= Configuration::deleteByName('AUTUMN_SHOW_SUB_CATS');
            $response &= Configuration::deleteByName('AUTUMN_COLOR');
            $response &= Configuration::deleteByName('AUTUMN_BCOLOR');
            $response &= Configuration::deleteByName('AUTUMN_BHCOLOR');
            $response &= Configuration::deleteByName('AUTUMN_BBCOLOR');
            $response &= Configuration::deleteByName('AUTUMN_LOGOW');
            return $response;
        }
        return false;
    }
        
/*-------------------------------------------------------------*/
/*  MODUL INITIALIZE & FORM SUBMIT CHECKs
/*-------------------------------------------------------------*/    
    
        
    public function getContent(){
        
		if (Tools::isSubmit('submitAutumnThemeOptions'))
		{
                    if (Tools::isSubmit('cat_view')){
                        Configuration::updateValue('AUTUMN_DEFAULT_CAT_VIEW', Tools::getValue('cat_view'));
                    }
                    
                    if (Tools::isSubmit('img_size')){
                        Configuration::updateValue('AUTUMN_DEFAULT_IMG_SIZE', Tools::getValue('img_size'));
                    }
                    
                    if (Tools::isSubmit('cat_name')){
                        if (Tools::getValue('cat_name') == "true"){
                            Configuration::updateValue('AUTUMN_SHOW_CAT_NAME', 1);
                        }else{
                            Configuration::updateValue('AUTUMN_SHOW_CAT_NAME', 0);
                        }
                    }
                    
                    if (Tools::isSubmit('cat_desc')){
                        if (Tools::getValue('cat_desc') == "true"){
                            Configuration::updateValue('AUTUMN_SHOW_CAT_DESC', 1);
                        }else{
                            Configuration::updateValue('AUTUMN_SHOW_CAT_DESC', 0);
                        }
                    }
                    
                    if (Tools::isSubmit('sub_cats')){
                        if (Tools::getValue('sub_cats') == "true"){
                            Configuration::updateValue('AUTUMN_SHOW_SUB_CATS', 1);
                        }else{
                            Configuration::updateValue('AUTUMN_SHOW_SUB_CATS', 0);
                        }
                    }
                    
                    if (Tools::isSubmit('color-scheme')){
                        if (Validate::isColor(Tools::getValue('color-scheme'))){
                            Configuration::updateValue('AUTUMN_COLOR', Tools::getValue('color-scheme'));
                        }else{
                            $this->displayError($this->l('Please enter a valid color code (hex)'));
                        }
                    }
                    
                    if (Tools::isSubmit('button-color')){
                        if (Validate::isColor(Tools::getValue('button-color'))){
                            Configuration::updateValue('AUTUMN_BCOLOR', Tools::getValue('button-color'));
                        }else{
                            $this->displayError($this->l('Please enter a valid color code (hex)'));
                        }
                    }
                    
                    if (Tools::isSubmit('button-hover-color')){
                        if (Validate::isColor(Tools::getValue('button-hover-color'))){
                            Configuration::updateValue('AUTUMN_BHCOLOR', Tools::getValue('button-hover-color'));
                        }else{
                            $this->displayError($this->l('Please enter a valid color code (hex)'));
                        }
                    }
                    
                    if (Tools::isSubmit('button-border-color')){
                        if (Validate::isColor(Tools::getValue('button-border-color'))){
                            Configuration::updateValue('AUTUMN_BBCOLOR', Tools::getValue('button-border-color'));
                        }else{
                            $this->displayError($this->l('Please enter a valid color code (hex)'));
                        }
                    }
                    
                    if (Tools::isSubmit('logo-width')){
                        if (Validate::isInt(Tools::getValue('logo-width'))){
                            Configuration::updateValue('AUTUMN_LOGOW', Tools::getValue('logo-width'));
                        }else{
                            $this->displayError($this->l('Logo width must be a numeric value'));
                        }
                    }
                }
               
                return $this->displayForm();
        }
        
/*-------------------------------------------------------------*/
/*  DISPLAY CONFIGURATION FORM
/*-------------------------------------------------------------*/    
                
	public function displayForm(){
            
            $category_view = Configuration::get('AUTUMN_DEFAULT_CAT_VIEW');
            $image_size = Configuration::get('AUTUMN_DEFAULT_IMG_SIZE');
            $cat_name = Configuration::get('AUTUMN_SHOW_CAT_NAME');
            $cat_desc = Configuration::get('AUTUMN_SHOW_CAT_DESC');
            $sub_cats = Configuration::get('AUTUMN_SHOW_SUB_CATS');
            $color = Configuration::get('AUTUMN_COLOR');
            $bcolor = Configuration::get('AUTUMN_BCOLOR');
            $bhcolor = Configuration::get('AUTUMN_BHCOLOR');
            $bbcolor = Configuration::get('AUTUMN_BBCOLOR');
            $logowidth = Configuration::get('AUTUMN_LOGOW');
            
            $grid = false;
            $list = false;
            
            if ($category_view == "grid_view"){
                $grid = true;
            }elseif ($category_view == "list_view"){
                $list = true;
            }
            
            
            $sqr = false;
            $rect = false;
            
            if ($image_size == "sqr_img"){
                $sqr = true;
            }elseif ($image_size == "rect_img"){
                $rect = true;
            }
            
            
            $name = false;
            $desc = false;

            if($cat_name == 1){
                $name = true;
            }
            
            if($cat_desc == 1){
                $desc = true;
            }
            
            
            $sub = false;
            
            if($sub_cats == 1){
                $sub = true;
            }
            
            $this->output = '<h2>'.$this->displayName.'</h2>
                <link rel="stylesheet" href="'.$this->_path.'assets/css/colorpicker.css" type="text/css" />

                <script type="text/javascript" src="'.$this->_path.'assets/js/colorpicker.js"></script>
                <script type="text/javascript" src="'.$this->_path.'assets/js/eye.js"></script>
                <script type="text/javascript" src="'.$this->_path.'assets/js/utils.js"></script>
                
                <script type="text/javascript">
                $(document).ready(function(){
                
                    $("#color-scheme").ColorPicker({
                        onSubmit: function(hsb, hex, rgb, el) {
                            $(el).val(hex);
                            $(el).ColorPickerHide();
                            $("#color-scheme-preview").css("background-color", "#" + hex);
                            
                        },
                        onBeforeShow: function () {
                            $(this).ColorPickerSetColor(this.value);
                            $("#color-scheme-preview").css("background-color", "#" + this.value);
                        }
                        })
                        .bind("keyup", function(){
                            $(this).ColorPickerSetColor(this.value);
                            $("#color-scheme-preview").css("background-color", "#" + this.value);
                     });
                     

                     
                    $("#button-color").ColorPicker({
                        onSubmit: function(hsb, hex, rgb, el) {
                            $(el).val(hex);
                            $(el).ColorPickerHide();
                            $("#button-color-preview").css("background-color", "#" + hex);
                            
                        },
                        onBeforeShow: function () {
                            $(this).ColorPickerSetColor(this.value);
                            $("#button-color-preview").css("background-color", "#" + this.value);
                        }
                        })
                        .bind("keyup", function(){
                            $(this).ColorPickerSetColor(this.value);
                            $("#button-color-preview").css("background-color", "#" + this.value);
                     });
                     

                    $("#button-hover-color").ColorPicker({
                        onSubmit: function(hsb, hex, rgb, el) {
                            $(el).val(hex);
                            $(el).ColorPickerHide();
                            $("#button-hover-color-preview").css("background-color", "#" + hex);
                            
                        },
                        onBeforeShow: function () {
                            $(this).ColorPickerSetColor(this.value);
                            $("#button-hover-color-preview").css("background-color", "#" + this.value);
                        }
                        })
                        .bind("keyup", function(){
                            $(this).ColorPickerSetColor(this.value);
                            $("#button-hover-color-preview").css("background-color", "#" + this.value);
                     });
                     

                     
                    $("#button-border-color").ColorPicker({
                        onSubmit: function(hsb, hex, rgb, el) {
                            $(el).val(hex);
                            $(el).ColorPickerHide();
                            $("#button-border-color-preview").css("background-color", "#" + hex);
                            
                        },
                        onBeforeShow: function () {
                            $(this).ColorPickerSetColor(this.value);
                            $("#button-border-color-preview").css("background-color", "#" + this.value);
                        }
                        })
                        .bind("keyup", function(){
                            $(this).ColorPickerSetColor(this.value);
                            $("#button-border-color-preview").css("background-color", "#" + this.value);
                     });
                
                        

                });
                </script>

                <style>
                    
                    fieldset.nice-layout{
                        background:#eeeeee;
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
                        margin-right:10px;
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
                    
                    div.element-wrapper{
                        display:block;
                        clear:both;
                        float:left;
                        overflow:hidden;
                        margin-bottom:10px;
                        padding:10px;
                    }
                    
                    label.element-title{
                        display:block;
                        float:left;
                        overflow:hidden;
                        margin:20px 0;
                        padding:0;
                        width:220px;
                        background:#ffffff;
                        
                        padding:10px 15px;
                        
                        border-radius:4px;
                        -moz-border-radius:4px;
                        -webkit-border-radius:4px;

                        box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                        -moz-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                        -webkit-box-shadow: 0 0 0 1.5px rgba(0,0,0,0.095) , 0 1.5px 1.5px 0 rgba(0,0,0,0.2), 0 2px 1.5px 0 rgba(0, 0, 0, 0.1) ;
                    }
                    
                    div.element-options{
                        display:block;
                        float:left;
                        margin:13px 20px;
                    }
                    
                    span.field-value-type{
                        float:left;
                        margin:16px 0 0 5px;
                    }

                    input#color-scheme,
                    input#button-color,
                    input#button-hover-color,
                    input#button-border-color,
                    input#logo-width{
                        padding:7px 10px;
                        margin-top:10px;
                        float:left;
                        display:block;
                    }
                    
                    #color-scheme-preview,
                    #button-color-preview,
                    #button-hover-color-preview,
                    #button-border-color-preview{
                        display:block;
                        width:30px;
                        height:30px;
                        margin:11px 0 0 5px;
                        float:left;
                    }
                    
                    #color-scheme-preview{background-color:#'.$color.';}
                    #button-color-preview{background-color:#'.$bcolor.';}
                    #button-hover-color-preview{background-color:#'.$bhcolor.';}
                    #button-border-color-preview{background-color:#'.$bbcolor.';}
                        
                    .ps{
                        font-size:10px;
                        padding-top:5px;
                        display:block;
                        clear:both;
                    }
                    
                </style>
                <form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
                    
			<fieldset class="nice-layout"><legend><img src="'.$this->_path.'/img/cog.png"/>'.$this->l('Theme Options').'</legend>
                            
                            <div class="element-wrapper">
                                <label class="element-title">'.$this->l('Default category view:').'</label>
                                
                                <div class="element-options">
                                    <input type="radio" name="cat_view" id="cat_view_list" class="hide" value="list_view"'. ($list ? 'checked="checked" ' : '') .'>
                                    <label for="cat_view_list" class="t selectable"><img src="'.$this->_path.'/img/list.png" title="'.$this->l("List View").'"/></label>

                                    <input type="radio" name="cat_view" id="cat_view_grid" class="hide" value="grid_view"'. ($grid ? 'checked="checked" ' : '') .'>
                                    <label for="cat_view_grid" class="t selectable"><img src="'.$this->_path.'/img/grid.png" title="'.$this->l("Grid View").'"/></label>
                                    
                                </div>
                                
                            </div>
                            
                            <div class="element-wrapper">
                                <label class="element-title">'.$this->l('Image sizes:').'</label>
                                <div class="element-options">
                                
                                    <input type="radio" name="img_size" id="img_size_sqr" class="hide" value="sqr_img"'. ($sqr ? 'checked="checked" ' : '') .'>
                                    <label for="img_size_sqr" class="t selectable"><img src="'.$this->_path.'/img/sqr.png" title="'.$this->l("Square Images").'"/></label>
                                    
                                    <input type="radio" name="img_size" id="img_size_rect" class="hide" value="rect_img"'. ($rect ? 'checked="checked" ' : '') .'>
                                    <label for="img_size_rect" class="t selectable"><img src="'.$this->_path.'/img/rect.png" title="'.$this->l("Rectangle Images").'"/></label>

                                </div>
                            </div>  
                            
                            <div class="element-wrapper">
                                <label class="element-title">'.$this->l('Show category names:').'</label>
                                <div class="element-options">
                                
                                    <input type="radio" name="cat_name" id="cat_name_true" class="hide" value="true"'. ($name ? 'checked="checked" ' : '') .'>
                                    <label for="cat_name_true" class="t selectable" ><img src="'.$this->_path.'/img/yes.png" title="'.$this->l("Show").'"/></label>
                                    
                                    <input type="radio" name="cat_name" id="cat_name_false" class="hide" value="false"'. (!$name ? 'checked="checked" ' : '') .'>
                                    <label for="cat_name_false" class="t selectable" ><img src="'.$this->_path.'/img/no.png" title="'.$this->l("Hide").'"/></label>

                                </div>
                            </div>                                    
                            
                            <div class="element-wrapper">
                                <label class="element-title">'.$this->l('Show category description:').'</label>
                                <div class="element-options">
                                
                                    <input type="radio" name="cat_desc" id="cat_desc_true" class="hide" value="true"'. ($desc ? 'checked="checked" ' : '') .'>
                                    <label for="cat_desc_true" class="t selectable" ><img src="'.$this->_path.'/img/yes.png" title="'.$this->l("Show").'"/></label>
                                    
                                    <input type="radio" name="cat_desc" id="cat_desc_false" class="hide" value="false"'. (!$desc ? 'checked="checked" ' : '') .'>
                                    <label for="cat_desc_false" class="t selectable" ><img src="'.$this->_path.'/img/no.png" title="'.$this->l("Hide").'"/></label>

                                </div>
                            </div>                                    
                            
                            <div class="element-wrapper">
                                <label class="element-title">'.$this->l('Show subcategories:').'</label>
                                <div class="element-options">
                                
                                    <input type="radio" name="sub_cats" id="sub_cats_true" class="hide" value="true"'. ($sub ? 'checked="checked" ' : '') .'>
                                    <label for="sub_cats_true" class="t selectable" ><img src="'.$this->_path.'/img/yes.png" title="'.$this->l("Show").'"/></label>
                                    
                                    <input type="radio" name="sub_cats" id="sub_cats_false" class="hide" value="false"'. (!$sub ? 'checked="checked" ' : '') .'>
                                    <label for="sub_cats_false" class="t selectable" ><img src="'.$this->_path.'/img/no.png" title="'.$this->l("Hide").'"/></label>

                                </div>
                            </div>        
                            
                            <div class="element-wrapper">
                                <label class="element-title">'.$this->l('Color Scheme:').'</label>
                                <div class="element-options">
                                
                                    <input type="text" name="color-scheme" id="color-scheme" value="'.$color.'">
                                    <div id="color-scheme-preview"></div> 
                                    <p class="ps">Default: da3b44</p>
                                </div>
                            </div>        
                            
                            <div class="element-wrapper">
                                <label class="element-title">'.$this->l('Button Color:').'</label>
                                <div class="element-options">
                                
                                    <input type="text" name="button-color" id="button-color" value="'.$bcolor.'">
                                    <div id="button-color-preview"></div> 
                                    <p class="ps">Default: da3b44</p>
                                </div>
                            </div>       
                            
                            <div class="element-wrapper">
                                <label class="element-title">'.$this->l('Button Hover Color:').'</label>
                                <div class="element-options">
                                
                                    <input type="text" name="button-hover-color" id="button-hover-color" value="'.$bhcolor.'">
                                    <div id="button-hover-color-preview"></div> 
                                    <p class="ps">Default: e6434c</p>
                                </div>
                            </div>        
                            
                            <div class="element-wrapper">
                                <label class="element-title">'.$this->l('Button Border Color:').'</label>
                                <div class="element-options">
                                
                                    <input type="text" name="button-border-color" id="button-border-color" value="'.$bbcolor.'">
                                    <div id="button-border-color-preview"></div> 
                                    <p class="ps">Default: a40f18</p>
                                </div>
                            </div>   
                            
                            <div class="element-wrapper">
                                <label class="element-title">'.$this->l('Logo Width:').'</label>
                                <div class="element-options">
                                
                                    <input type="text" name="logo-width" id="logo-width" value="'.$logowidth.'"><span class="field-value-type">px</span>
                                    <p class="ps">Default: 160px</p>
                                </div>
                            </div>        
                            

                            <center style="clear:both;"><input style="padding:5px 10px;" type="submit" name="submitAutumnThemeOptions" value="'.$this->l('Save').'" class="button" /></center>
                        </fieldset>
                        
                        <br />
                ';
            return $this->output;
            
        }
        
        
/*-------------------------------------------------------------*/
/*  PREPARE FOR HOOK
/*-------------------------------------------------------------*/          

    private function _prepHook($params){
        
        $category_view_type_cookie = $this->context->cookie->category_view_type;
        
        //Options
        if ($category_view_type_cookie && $category_view_type_cookie == "list_view"){
            $this->smarty->assignGlobal('category_view_type', "list_view");
        }elseif ($category_view_type_cookie && $category_view_type_cookie == "grid_view"){
            $this->smarty->assignGlobal('category_view_type', "grid_view");
        }else{
            $this->smarty->assignGlobal('category_view_type', Configuration::get('AUTUMN_DEFAULT_CAT_VIEW'));
        }
        
        $color_changer = '';
        
        if (Configuration::get('AUTUMN_COLOR') != 'da3b44'){
            $newcolorscheme = Configuration::get('AUTUMN_COLOR');
            //Begin the color changer code
            $color_changer .= '<style>';
            
            //autumn.css
            //For "color" rule
            $color_changer .= '.required_info, .error, a.color, .autumn_add_to_cart:disabled, .autumn_add_to_cart_ph:disabled, .ac_product_name strong, #cart_block .price, .sf-contener .sf-menu > li > a:active, .sf-menu a:focus, .sf-menu a:hover, .sf-menu a:active, .sfHoverForce > a, #featured-products_block_center .on_sale, #featured-products_block_center .discount, #featured-products_block_center .price_container .price, #layered_block_left .layered_subtitle, #layered_block_left .nomargin a:hover, #category_view_type.grid_view #product_list .product_list_details_left .price, #category_view_type.grid_view #product_list .product_list_details .on_sale, #category_view_type.grid_view #product_list .product_list_details .discount, #category_view_type.grid_view #product_list .product_list_details .online_only, #category_view_type.list_view #product_list .product_list_details_left .price, #category_view_type.list_view #product_list .product_list_add_to_cart .out-of-stock, #category_view_type.list_view #product_list .product_list_details .on_sale, #category_view_type.list_view #product_list .product_list_details .discount, #category_view_type.list_view #product_list .product_list_details .online_only, #product_price_block .our_price_display, #product_stock_stat #availability_statut span#availability_value.warning_inline, #product_stock_stat #last_quantities, #new_comment_form .title, #product_comparison .price, #product_comparison .discount, #product_comparison .on_sale, table.autumn-table a:hover, table.std a:hover, ul.step li.step_current span, ul.step li.step_current_end span, ul#order_step li.step_done:after, table#cart_summary tfoot .price, .addresses ul.address .address_delete a, p.required sup, .payment_module:hover a, ul.myaccount_lnk_list li a:hover, #block-history #order-detail-content span.price, #block-history #order-detail-content span.price-shipping';
            $color_changer .= '{color:#'.$newcolorscheme.'!important;}';
            
            //For "background" rule
            $color_changer .= '.ac_over, .ui-slider-horizontal .ui-slider-range, #category_view_type.grid_view #product_list li span.new, #category_view_type.list_view #product_list li span.new, ul#order_step li.step_done, table#cart_summary .cart_total_price .total_price_container p';
            $color_changer .= '{background:#'.$newcolorscheme.'!important;}';
            
            //For "border-left-color" rule
            $color_changer .= 'ul#order_step li.step_done:after';
            $color_changer .= '{border-left-color:#'.$newcolorscheme.'!important;}';
            
            //For "border-top-color" rule
            $color_changer .= '#multishipping_mode_box, div.addresses, #add_adress fieldset, #add_address fieldset, .order_carrier_content, #opc_payment_methods, #contact fieldset, #order-opc #new_account_form fieldset, #order-opc #login_form fieldset, #authentication #create-account_form fieldset, #authentication #account-creation_form fieldset, #authentication #login_form fieldset, #form_forgotpassword fieldset, #identity #personal_info fieldset, #module-blockwishlist-mywishlist #mywishlist fieldset,#order-opc #new_account_form fieldset, #order-opc #login_form fieldset, #authentication #create-account_form fieldset, #authentication #account-creation_form fieldset, #authentication #login_form fieldset, #authentication #new_account_form fieldset';
            $color_changer .= '{border-top-color:#'.$newcolorscheme.'!important;}';
            
            
            //custom modules
            //For "color" rule
            $color_changer .= '#megamenu li:hover .root_link, #megamenu_left li.m_left_product .m_left_prd_name a:hover, #megamenu .megamenu_context a:hover, .autumnshowcase_block .on_sale, .autumnshowcase_block .discount, .autumnshowcase_block .price_container .price, .autumnshowcase_block .out-of-stock, .autumnshowcase_block .ajax_add_to_cart_button:hover, #best-sellers_block_right li .price, #crossselling_list li p.price_display, #productscategory_list li p.price_display, .accessories_block div ul li .price';
            $color_changer .= '{color:#'.$newcolorscheme.'!important;}';
            
            //For "background-color" rule
            $color_changer .= '.autumnshowcase_block .new';
            $color_changer .= '{background-color:#'.$newcolorscheme.'!important;}';
            
            //For "border-color" rule
            $color_changer .= '#megamenu .megamenu_context';
            $color_changer .= '{border-color:#'.$newcolorscheme.'!important;}';
            
            
            //End the color changer code
            $color_changer .= '</style>';
        }else{
            //Return empty
            $color_changer .= '';
        }
        
        
        if (Configuration::get('AUTUMN_BCOLOR') != 'da3b44'){
            $newbuttoncolor = Configuration::get('AUTUMN_BCOLOR');
            //Begin the color changer code
            $color_changer .= '<style>';
            
            //button color
            //background-color
            $color_changer .= 'input.button_mini, input.button_small, input.button, input.button_large, input.exclusive_mini, input.exclusive_small, input.exclusive, input.exclusive_large, a.button_mini, a.button_small, a.button, a.button_large, a.exclusive_mini, a.exclusive_small, a.exclusive, a.exclusive_large, span.button_mini, span.button_small, span.button, span.button_large, span.exclusive_mini, span.exclusive_small, span.exclusive, span.exclusive_large, .flat-red-button, .autumn_add_to_cart, .autumn_add_to_cart_ph';
            $color_changer .= '{background-color:#'.$newbuttoncolor.'!important;}';
            
            //color
            $color_changer .= '.autumn_add_to_cart:disabled, .autumn_add_to_cart_ph:disabled';
            $color_changer .= '{color:#'.$newbuttoncolor.'!important;}';
          
            //End the color changer code
            $color_changer .= '</style>';            
        }else{
            //Return empty
            $color_changer .= '';
        }
        
        
        if (Configuration::get('AUTUMN_BHCOLOR') != 'e6434c'){
            $newbuttonhovercolor = Configuration::get('AUTUMN_BHCOLOR');
            //Begin the color changer code
            $color_changer .= '<style>';
            
            //button hover color
            //hover-color
            $color_changer .= '.autumn_add_to_cart:hover, .autumn_add_to_cart_ph:hover, input.button_mini:hover, input.button_small:hover, input.button:hover, input.button_large:hover, input.exclusive_mini:hover, input.exclusive_small:hover, input.exclusive:hover, input.exclusive_large:hover, a.button_mini:hover, a.button_small:hover, a.button:hover, a.button_large:hover, a.exclusive_mini:hover, a.exclusive_small:hover, a.exclusive:hover, a.exclusive_large:hover, span.button_mini:hover, span.button_small:hover, span.button:hover, span.button_large:hover, span.exclusive_mini:hover, span.exclusive_small:hover, span.exclusive:hover, span.exclusive_large:hover';
            $color_changer .= '{background-color:#'.$newbuttonhovercolor.'!important;}';
            
            //active-color
            $color_changer .= '.autumn_add_to_cart:active, .autumn_add_to_cart_ph:active, input.button_mini:active, input.button_small:active, input.button:active, input.button_large:active, input.exclusive_mini:active, input.exclusive_small:active, input.exclusive:active, input.exclusive_large:active, a.button_mini:active, a.button_small:active, a.button:active, a.button_large:active, a.exclusive_mini:active, a.exclusive_small:active, a.exclusive:active, a.exclusive_large:active, span.button_mini:active, span.button_small:active, span.button:active, span.button_large:active, span.exclusive_mini:active, span.exclusive_small:active, span.exclusive:active, span.exclusive_large:active';
            $color_changer .= '{color:#'.$newbuttonhovercolor.'!important;}';
          
            //End the color changer code
            $color_changer .= '</style>';            
        }else{
            //Return empty
            $color_changer .= '';
        }
        
        
        if (Configuration::get('AUTUMN_BBCOLOR') != 'a40f18'){
            $newbuttonbordercolor = Configuration::get('AUTUMN_BBCOLOR');
            //Begin the color changer code
            $color_changer .= '<style>';
            
            //button color
            //border-color
            $color_changer .= '.autumn_add_to_cart, .autumn_add_to_cart_ph, input.button_mini, input.button_small, input.button, input.button_large, input.exclusive_mini, input.exclusive_small, input.exclusive, input.exclusive_large, a.button_mini, a.button_small, a.button, a.button_large, a.exclusive_mini, a.exclusive_small, a.exclusive, a.exclusive_large, span.button_mini, span.button_small, span.button, span.button_large, span.exclusive_mini, span.exclusive_small, span.exclusive, span.exclusive_large';
            $color_changer .= '{border-color:#'.$newbuttonbordercolor.'!important;}';
          
            //End the color changer code
            $color_changer .= '</style>';            
        }else{
            //Return empty
            $color_changer .= '';
        }
        
        
        if (Configuration::get('AUTUMN_LOGOW') != 160 && Configuration::get('AUTUMN_LOGOW') != ""){
            $newlogowidth = Configuration::get('AUTUMN_LOGOW');
            $logowidth = '<style>';
            
            $logowidth .= '#megamenu{left:'.$newlogowidth.'px!important;}';
            $logowidth .= '.sf-contener{margin-left:'.$newlogowidth.'px!important;}';
            $logowidth .= '#header-logo{width:'.$newlogowidth.'px!important;}';
            
            $logowidth .= '</style>';
        }else{
            $logowidth = '';
        }
                
        $this->smarty->assignGlobal('image_shape', Configuration::get('AUTUMN_DEFAULT_IMG_SIZE'));
        $this->smarty->assignGlobal('show_cat_names', Configuration::get('AUTUMN_SHOW_CAT_NAME'));
        $this->smarty->assignGlobal('show_cat_desc', Configuration::get('AUTUMN_SHOW_CAT_DESC'));
        $this->smarty->assignGlobal('show_subcategories', Configuration::get('AUTUMN_SHOW_SUB_CATS'));
        $this->smarty->assignGlobal('color_changer', $color_changer);
        $this->smarty->assignGlobal('logo_width', $logowidth);
        
        //Autumn Newsletter Block Control
        $this->smarty->assignGlobal('autumnnewsletter', (int)Module::getInstanceByName('autumnnewsletterblock')->active);
        
        //Wishlist Block Control
        if (Module::isEnabled('blockwishlist') && Module::isRegisteredInHook('displayHeader')){
            $this->smarty->assignGlobal('wishlistActive', true);
        }
        
        
        //Load custom CSS files
        $this->context->controller->addCSS(_THEME_CSS_DIR_."autumn.css");
        $this->context->controller->addCSS(_THEME_CSS_DIR_."responsive.css");
        $this->context->controller->addCSS(_THEME_CSS_DIR_."retina.css");
        $this->context->controller->addCSS(_THEME_CSS_DIR_."jquery.jscrollpane.css");

        if (Configuration::get('PS_DISPLAY_JQZOOM') == 1){                   
            $this->context->controller->addCSS(_THEME_CSS_DIR_."jquery.jqzoom.css");
        }

        
        //Load custom JS files
        $this->context->controller->addJqueryPlugin("mousewheel", _THEME_JS_DIR_."autumn/");
        $this->context->controller->addJqueryPlugin("jscrollpane.min", _THEME_JS_DIR_."autumn/");
        $this->context->controller->addJqueryPlugin("ui.touch-punch.min", _THEME_JS_DIR_."autumn/");
        $this->context->controller->addJqueryPlugin("touchwipe.min", _THEME_JS_DIR_."autumn/");
        
        $this->context->controller->addJqueryPlugin("jcarousel.min", _THEME_JS_DIR_."autumn/jcarousel/");
        $this->context->controller->addJqueryPlugin("jcarousel-control.min", _THEME_JS_DIR_."autumn/jcarousel/");
        $this->context->controller->addJqueryPlugin("jcarousel-autoscroll.min", _THEME_JS_DIR_."autumn/jcarousel/");

        if (Configuration::get('PS_DISPLAY_JQZOOM') == 1){                   
            $this->context->controller->addJqueryPlugin("jqzoom", _THEME_JS_DIR_."autumn/jqzoom/");
        }
        
        $this->context->controller->addJqueryPlugin("noty", _THEME_JS_DIR_."autumn/noty/");
        $this->context->controller->addJS(_THEME_JS_DIR_."autumn/noty/layouts/topCenter.js");
        $this->context->controller->addJS(_THEME_JS_DIR_."autumn/noty/themes/default.js");
        
        $this->context->controller->addJqueryPlugin("autumn", _THEME_JS_DIR_."autumn/");
        
        //User logged in?
        if ($this->context->customer->isLogged()){
            $this->smarty->assignGlobal('userLoggedIn', true);
        }else{
            $this->smarty->assignGlobal('userLoggedIn', false);
        }
            
        
        //Wishlist Fix
        require_once(_PS_MODULE_DIR_.'blockwishlist/WishList.php');
        
        if ($this->context->customer->isLogged())
        {
            $wishlists = Wishlist::getByIdCustomer($this->context->customer->id);
            if (empty($this->context->cookie->id_wishlist) === true || WishList::exists($this->context->cookie->id_wishlist, $this->context->customer->id) === false)
            {
                if (!sizeof($wishlists)){
                    $id_wishlist = false;
                }
                else
                {
                    $id_wishlist = (int)($wishlists[0]['id_wishlist']);
                    $this->context->cookie->id_wishlist = (int)($id_wishlist);
                }
            }
            else
            {
                $id_wishlist = $this->context->cookie->id_wishlist;
            }
                    
            $this->smarty->assignGlobal('id_wishlist', $id_wishlist);
            $this->smarty->assignGlobal('wishlists', $wishlists);
            $this->smarty->assignGlobal('ptoken', Tools::getToken(false));
           
        }
        else
        {
            $this->smarty->assignGlobal('wishlists', false);
        }

        return true;
    }

    
            
/*-------------------------------------------------------------*/
/*  Category View Type
/*-------------------------------------------------------------*/          

    private function _categoryViewType($params){
        
        $this->output = '
            <script>
            // <![CDATA[
                    $("document").ready(function(){

                        $("#category_view_changer a").click(function(){

                            if ( $(this).hasClass("list_view") ){
                                action = "list_view";
                            }else if ( $(this).hasClass("grid_view") ) {
                                action = "grid_view";
                            }

                            if ( !$("#category_view_type").hasClass(action) ){

                                $.ajax({
                                    type: "GET",
                                    url:   baseDir + "modules/autumntheme/autumntheme-ajax.php",
                                    async: true,
                                    cache: false,
                                    data: "item=category_view&action=" + action + "&secure_hash=';
        $this->output .= $this->secure_key .'",
                                    success: function(data){
                                        data = data.replace(/"/g, "");
                                        $("#category_view_type").fadeOut("fast", function(){
                                            $(this).removeClass().addClass("group " + data)
                                        });
                                        $("#category_view_type").fadeIn("fast");
                                        
                                        $("#category_view_changer a").removeClass("active");
                                        $("#category_view_changer a." + data).addClass("active");
                                    }
                                });

                            }
                            return false;
                        });

                    });
            //]]>
            </script>
        ';
        
        $categoryViewTypeHeader = $this->output;
        $this->smarty->assignGlobal('categoryViewTypeHeader', $categoryViewTypeHeader);
        
    }
        
/*-------------------------------------------------------------*/
/*  HOOK (displayTop)
/*-------------------------------------------------------------*/
        
        public function hookDisplayTop ($params){
            $this->_categoryViewType($params);
            $this->_prepHook($params);
            
        }
        
        
/*-------------------------------------------------------------*/
/*  HOOK (displayHeader)
/*-------------------------------------------------------------*/
        
        public function hookDisplayHeader ($params){
            $this->hookDisplayTop($params);
            
        }
        
}