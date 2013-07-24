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

{* IMPORTANT : If you change some data here, you have to report these changes in the ./blockcart-json.js (to let ajaxCart available) *}
{if $ajax_allowed}
    <script type="text/javascript">
        var CUSTOMIZE_TEXTFIELD = {$CUSTOMIZE_TEXTFIELD};
        var img_dir = '{$img_dir}';
    </script>
{/if}
<script type="text/javascript">
    var customizationIdMessage = '{l s='Customization #' mod='blockcart' js=1}';
    var removingLinkText = '{l s='remove this product from my cart' mod='blockcart' js=1}';
    var freeShippingTranslation = '{l s='Free shipping!' mod='blockcart' js=1}';
    var freeProductTranslation = '{l s='Free!' mod='blockcart' js=1}';
    var delete_txt = '{l s='Delete' mod='blockcart' js=1}';
    
    //For noty
    var itemAddedMsg = '{l s='Product added to your cart' mod='blockcart' js=1}';
    var viewCartBtn = '{l s='View Cart' mod='blockcart' js=1}';
    var continueBtn = '{l s='Continue Shopping' mod='blockcart' js=1}';
    
    var cartLink = '{$link->getPageLink("$order_process", true)}';
    
</script>
<!-- MODULE Block cart -->
<div id="cart_block">
	<h4>
		<a href="{$link->getPageLink("$order_process", true)}" rel="nofollow">{l s='Cart' mod='blockcart'}</a>
		{if $ajax_allowed}
		<span id="block_cart_expand" {if isset($colapseExpandStatus) && $colapseExpandStatus eq 'expanded' || !isset($colapseExpandStatus)}class="hidden"{/if}>&nbsp;</span>
		<span id="block_cart_collapse" {if isset($colapseExpandStatus) && $colapseExpandStatus eq 'collapsed'}class="hidden"{/if}>&nbsp;</span>
		{/if}
	</h4>
	<div class="block_content">
	<!-- block summary -->
	<div id="cart_block_summary" class="{if isset($colapseExpandStatus) && $colapseExpandStatus eq 'expanded' || !$ajax_allowed || !isset($colapseExpandStatus)}collapsed{else}expanded{/if}">
		<span class="ajax_cart_quantity" {if $cart_qties <= 0}style="display:none;"{/if}>{$cart_qties}</span>
		<span class="ajax_cart_product_txt_s" {if $cart_qties <= 1}style="display:none"{/if}>{l s='products' mod='blockcart'}</span>
		<span class="ajax_cart_product_txt" {if $cart_qties > 1}style="display:none"{/if}>{l s='product' mod='blockcart'}</span>
		<span class="ajax_cart_total" {if $cart_qties == 0}style="display:none"{/if}>
			{if $cart_qties > 0}
				{if $priceDisplay == 1}
					{convertPrice price=$cart->getOrderTotal(false)}
				{else}
					{convertPrice price=$cart->getOrderTotal(true)}
				{/if}
			{/if}
		</span>
		<span class="ajax_cart_no_product" {if $cart_qties != 0}style="display:none"{/if}>{l s='(empty)' mod='blockcart'}</span>
	</div>
	<!-- block list of products -->
	<div id="cart_block_list" class="{if isset($colapseExpandStatus) && $colapseExpandStatus eq 'expanded' || !$ajax_allowed || !isset($colapseExpandStatus)}expanded{else}collapsed{/if}">
	{if $products}
		<dl class="products">
		{foreach from=$products item='product' name='myLoop'}
			{assign var='productId' value=$product.id_product}
			{assign var='productAttributeId' value=$product.id_product_attribute}
			<dt id="cart_block_product_{$product.id_product}_{if $product.id_product_attribute}{$product.id_product_attribute}{else}0{/if}_{if $product.id_address_delivery}{$product.id_address_delivery}{else}0{/if}" class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if}">
				
                                <img class="cart_block_product_image" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}" height="30" alt="{$product.name|escape:html:'UTF-8'}" />
                        
                                <span class="quantity-formated"><span class="quantity">{$product.cart_quantity}</span></span>
				
                                <a class="cart_block_product_name" href="{$link->getProductLink($product, $product.link_rewrite, $product.category, null, null, $product.id_shop, $product.id_product_attribute)}" title="{$product.name|escape:html:'UTF-8'}">
                                    {$product.name|truncate:22:'...'|escape:html:'UTF-8'}
                                </a>
				
                                {if !isset($customizedDatas.$productId.$productAttributeId) && (!isset($product.is_gift) || !$product.is_gift)}
                                    <span class="remove_link"><a rel="nofollow" class="ajax_cart_block_remove_link" href="{$link->getPageLink('cart', true, NULL, "delete=1&amp;id_product={$product.id_product}&amp;ipa={$product.id_product_attribute}&amp;id_address_delivery={$product.id_address_delivery}&amp;token={$static_token}", true)}" title="{l s='remove this product from my cart' mod='blockcart'}">&nbsp;</a></span>                                    
                                {/if}
				
                                <span class="price">
					{if !isset($product.is_gift) || !$product.is_gift}
						{if $priceDisplay == $smarty.const.PS_TAX_EXC}{displayWtPrice p="`$product.total`"}{else}{displayWtPrice p="`$product.total_wt`"}{/if}
					{else}
						<b>{l s='Free!' mod='blockcart'}</b>
					{/if}
				</span>
			</dt>
			{if isset($product.attributes_small)}
			<dd id="cart_block_combination_of_{$product.id_product}{if $product.id_product_attribute}_{$product.id_product_attribute}{/if}_{$product.id_address_delivery|intval}" class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if}">
				<a href="{$link->getProductLink($product, $product.link_rewrite, $product.category, null, null, $product.id_shop, $product.id_product_attribute)}" title="{l s='Product detail' mod='blockcart'}">{$product.attributes_small}</a>
			{/if}

			{if isset($customizedDatas.$productId.$productAttributeId[$product.id_address_delivery])}
				{if !isset($product.attributes_small)}<dd id="cart_block_combination_of_{$product.id_product}_{if $product.id_product_attribute}{$product.id_product_attribute}{else}0{/if}_{if $product.id_address_delivery}{$product.id_address_delivery}{else}0{/if}" class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if}">{/if}
				<ul class="cart_block_customizations" id="customization_{$productId}_{$productAttributeId}">
					{foreach from=$customizedDatas.$productId.$productAttributeId[$product.id_address_delivery] key='id_customization' item='customization' name='customizations'}
						<li name="customization">
							{*<span class="deleteCustomizableProduct remove_link" id="deleteCustomizableProduct_{$id_customization|intval}_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}"><a class="ajax_cart_block_remove_link" href="{$link->getPageLink('cart', true, NULL, "delete=1&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;token={$static_token}", true)}" rel="nofollow"> </a></span>*}
							
                                                        {if isset($customization.datas.$CUSTOMIZE_TEXTFIELD.0)}
                                                        {$customization.datas.$CUSTOMIZE_TEXTFIELD.0.value|replace:"<br />":" "|truncate:28:'...'|escape:html:'UTF-8'}
							{else}
							{l s='Customization #%d:' sprintf=$id_customization|intval mod='blockcart'}
							{/if}
						</li>
					{/foreach}
				</ul>
				{if !isset($product.attributes_small)}</dd>{/if}
			{/if}

			{if isset($product.attributes_small)}</dd>{/if}

		{/foreach}
		</dl>
	{/if}
		<p {if $products}class="hidden"{/if} id="cart_block_no_products">{l s='No products' mod='blockcart'}</p>
	{if $discounts|@count > 0}
		<div id="vouchers">
                    <span class="title">{l s='Vouchers' mod='blockcart'}</span>
                    <div id="vouchers_list">
                        {foreach from=$discounts item=discount}
                            {if $discount.value_real > 0}
                            <dt class="bloc_cart_voucher" id="bloc_cart_voucher_{$discount.id_discount}">
                                <span class="name" title="{$discount.description}">{$discount.name|cat:' : '|cat:$discount.description|truncate:18:'...'|escape:'htmlall':'UTF-8'}</span>
                                <span class="price">-{if $priceDisplay == 1}{convertPrice price=$discount.value_tax_exc}{else}{convertPrice price=$discount.value_real}{/if}</span>
                                <div class="delete remove_link">
                                    {if strlen($discount.code)}
                                            <a class="delete_voucher ajax_cart_block_remove_link" href="{$link->getPageLink('$order_process', true)}?deleteDiscount={$discount.id_discount}" title="{l s='Delete' mod='blockcart'}" rel="nofollow"></a>
                                    {/if}
                                </div>
                            </dt>
                            {/if}
                        {/foreach}
                    </div>
		</div>
		{/if}
                <div id="cart_block_bottom">
                    
                    <div id="cart_block_other_costs">
                        <div class="other_cost_wrapper">
                            <span>{l s='Shipping:' mod='blockcart'}</span>
                            <span id="cart_block_shipping_cost" class="price ajax_cart_shipping_cost">{$shipping_cost}</span>
                        </div>
                        {if $show_wrapping}
                            <div class="other_cost_wrapper">
                                {assign var='cart_flag' value='Cart::ONLY_WRAPPING'|constant}
                                <span>{l s='Wrapping:' mod='blockcart'}</span>
                                <span id="cart_block_wrapping_cost" class="price cart_block_wrapping_cost">{if $priceDisplay == 1}{convertPrice price=$cart->getOrderTotal(false, $cart_flag)}{else}{convertPrice price=$cart->getOrderTotal(true, $cart_flag)}{/if}</span>
                            </div>

                        {/if}
                        {if $show_tax && isset($tax_cost)}
                            <div class="other_cost_wrapper">
                                <span>{l s='Tax:' mod='blockcart'}</span>	
                                <span id="cart_block_tax_cost" class="price ajax_cart_tax_cost">{$tax_cost}</span>
                            </div>
                        {/if}
                    </div>
                    
                    <div id="cart_block_total_checkout">
                        <div id="cart_block_total_wrapper">
                            <span id="cart_block_total" class="price ajax_block_cart_total">{$total}</span>
                            {if $use_taxes && $display_tax_label == 1 && $show_tax}

                                {if $priceDisplay == 0}
                                    <span id="cart-price-precisions">{l s='Tax included' mod='blockcart'}</span>
                                {/if}

                                {if $priceDisplay == 1}
                                    <span id="cart-price-precisions">{l s='Tax excluded' mod='blockcart'}</span>
                                {/if}

                            {/if}
                        </div>
                        
                        <div id="cart_checkout_button">
                                {if $order_process == 'order'}<a href="{$link->getPageLink("$order_process", true)}" id="button_view_cart" class="button_small" title="{l s='Cart' mod='blockcart'}" rel="nofollow">{l s='Cart' mod='blockcart'}</a>{/if}
                                <a href="{$link->getPageLink("$order_process", true)}" id="button_order_cart" class="exclusive{if $order_process == 'order-opc'}_large{/if}" title="{l s='Check out' mod='blockcart'}" rel="nofollow"><span>{l s='Check out' mod='blockcart'}</span></a>
                        </div>
                    </div> 
                        
		</div>
                        		
	</div>
	</div>
</div>
<!-- /MODULE Block cart -->

