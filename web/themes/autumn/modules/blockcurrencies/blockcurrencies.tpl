{*
* 2007-2012 PrestaShop
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
*  @copyright  2007-2012 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if count($currencies) > 1}
<div id="currencies_block_top">
	<form id="setcurrency" action="{$request_uri}" method="post">
                        
            <input type="hidden" name="id_currency" id="id_currency" value=""/>
            <input type="hidden" name="SubmitCurrency" value="" />
                           
                <script>
                    $('document').ready(function(){
                        $('#first-currencies.selectbox').change(function() {
                            window.location = $(this).find("option:selected").val();
                        });
                    });
                </script>
                
                <select id="first-currencies" class="selectbox currencies_select">
			{foreach from=$currencies key=k item=f_currency}
				<option {if $cookie->id_currency == $f_currency.id_currency}selected="selected"{/if} value="javascript:setCurrency({$f_currency.id_currency});">
					{$f_currency.sign}
				</option>
			{/foreach}
		</select>
	</form>
</div>
{/if}