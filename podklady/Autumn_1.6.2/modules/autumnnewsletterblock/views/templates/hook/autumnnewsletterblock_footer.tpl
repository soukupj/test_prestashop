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
*  @version  Release: $Revision: 6594 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- Block Newsletter module-->

<div id="newsletter_block_footer" class="block">
	<h4>{l s='Subscribe To Our Newsletter' mod='autumnnewsletterblock'}</h4>
        <a href="#" class="open-close-footer"></a>
	<div class="block_content">
        
                <p class="newsletter_desc">
                    {l s='Sign-up our newsletter to be the first to know about Sales, New Products and Exclusive Offers.' mod='autumnnewsletterblock'}
                </p>
        
		<form action="{$link->getPageLink('index')}" method="post">
			<p>
				{* @todo use jquery (focusin, focusout) instead of onblur and onfocus *}
				<input type="text" name="email" size="18" 
					value="{if isset($value) && $value}{$value}{else}{l s='e-mail address' mod='autumnnewsletterblock'}{/if}" 
					onfocus="javascript:if(this.value=='{l s='e-mail address' mod='autumnnewsletterblock'}')this.value='';" 
					onblur="javascript:if(this.value=='')this.value='{l s='e-mail address' mod='autumnnewsletterblock'}';" 
					class="newsletter_input dark" />
				
					<input type="submit" value="ok" class="submit_orange" name="submitNewsletter" />
				<input type="hidden" name="action" value="0" />
			</p>
		</form>
                                        
                {if isset($msg) && $msg}
                    <p class="{if $nw_error}newsletter_warning_inline{else}newsletter_success_inline{/if}">{$msg}</p>
                {/if}
	</div>
</div>
<!-- /Block Newsletter module-->
