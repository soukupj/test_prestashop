<?php

if (!defined('_PS_VERSION_'))
	exit;

class AutumnSearchBlock extends Module
{
	public function __construct()
	{
		$this->name = 'autumnsearchblock';
		$this->tab = 'front_office_features';
		$this->version = 1.0;
		$this->author = 'Sercan YEMEN';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Autumn Theme - Search Block with Extended Features');
		$this->description = $this->l('Adds a quick search block.');
                
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('top') || !$this->registerHook('header') || !$this->registerHook('displayMobileTopSiteMap'))
			return false;
		return true;
	}
	
               
	public function hookdisplayMobileTopSiteMap($params)
	{
		$this->smarty->assign(array('hook_mobile' => true, 'instantsearch' => false));
		return $this->hookTop($params);
	}
	
	
	public function hookHeader($params)
	{
		if (Configuration::get('PS_SEARCH_AJAX'))
		$this->context->controller->addJqueryPlugin('autocomplete');
		$this->context->controller->addCSS(_THEME_CSS_DIR_.'product_list.css');
		$this->context->controller->addCSS(($this->_path).'autumnsearchblock.css', 'all');
	}

	public function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

	public function hookRightColumn($params)
	{
		$this->calculHookCommon($params);
		$this->smarty->assign('blocksearch_type', 'block');
		return $this->display(__FILE__, 'autumnsearchblock.tpl');
	}

	public function hookTop($params)
	{
		$this->calculHookCommon($params);
		$this->smarty->assign('blocksearch_type', 'top');
		return $this->display(__FILE__, 'autumnsearchblock-top.tpl');
	}

	/**
	 * _hookAll has to be called in each hookXXX methods. This is made to avoid code duplication.
	 *
	 * @param mixed $params
	 * @return void
	 */
	private function calculHookCommon($params)
	{
		$this->smarty->assign(array(
			'ENT_QUOTES' =>		ENT_QUOTES,
			'search_ssl' =>		Tools::usingSecureMode(),
			'ajaxsearch' =>		Configuration::get('PS_SEARCH_AJAX'),
			'instantsearch' =>	Configuration::get('PS_INSTANT_SEARCH'),
			'self' =>			dirname(__FILE__),
		));

		return true;
	}
}

