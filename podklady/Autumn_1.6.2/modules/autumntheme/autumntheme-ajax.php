<?php
include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('autumntheme.php');

$context = Context::getContext();
$autumnTheme = new AutumnTheme();

if (!Tools::isSubmit('secure_hash') || Tools::getValue('secure_hash') != $autumnTheme->secure_key || !Tools::getValue('action')){
	die(1);
}

$item = Tools::getValue('item');
$action = Tools::getValue('action');

if ($item == "category_view"){
    if ($action == "list_view"){
        $context->cookie->category_view_type = "list_view";
        $context->cookie->write();
        die(Tools::jsonEncode($context->cookie->category_view_type));
        
    }elseif ($action == "grid_view"){
        $context->cookie->category_view_type = "grid_view";
        $context->cookie->write();
        die(Tools::jsonEncode($context->cookie->category_view_type));
    }
}