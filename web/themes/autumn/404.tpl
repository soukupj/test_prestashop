{*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div class="pagenotfound">
	<h1 class="dotted_bottom">{l s='This page is not available'}</h1>
        
	<h2>
		{l s='We\'re sorry, but the web address you\'ve entered is no longer available.'}
                {l s='To search a product, please use the field below:'}
	</h2>

	<form action="{$link->getPageLink('search')}" method="post" class="std">
		<fieldset>
			<p>
				<input id="search_query" name="search_query" type="text" class="light"/>
				<input type="submit" name="Submit" value="Search" class="autumn-button" />
			</p>
		</fieldset>
	</form>
       <br />
	<p><a class="back_home_link" href="{$base_dir}" title="{l s='Home'}">{l s='Back to the homepage'}</a></p>
</div>