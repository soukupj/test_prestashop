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

<!-- MODULE Home Featured Products -->
<div id="featured-products_block_center" class="block products_block clearfix {(isset($image_shape) && $image_shape == 'rect_img') ? 'rect_img' : ' '}">
	<h4>{l s='Featured products' mod='homefeatured'}</h4>
	{if isset($products) AND $products}
		<div class="grid-container">
			{assign var='liHeight' value=250}
			{assign var='nbItemsPerLine' value=6}
			{assign var='nbLi' value=$products|@count}
			{math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$nbItemsPerLine assign=nbLines}
			{math equation="nbLines*liHeight" nbLines=$nbLines|ceil liHeight=$liHeight assign=ulHeight}
			<ul class="grid">
			{foreach from=$products item=product name=homeFeaturedProducts}
				{math equation="(total%perLine)" total=$smarty.foreach.homeFeaturedProducts.total perLine=$nbItemsPerLine assign=totModulo}
				{if $totModulo == 0}{assign var='totModulo' value=$nbItemsPerLine}{/if}
				<li class="ajax_block_product {if $smarty.foreach.homeFeaturedProducts.first}first_item{elseif $smarty.foreach.homeFeaturedProducts.last}last_item{else}item{/if}">
                                    
                                        <div class="featured_product_image_wrapper group">
                                            <a class="featured_product_image_link" href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}">
                                                                                                
                                                {if isset($product.new) && $product.new == 1}<span class="new">{l s='New' mod='homefeatured'}</span>{/if}
                                                
                                                <img class="featured_product_image" src="{$link->getImageLink($product.link_rewrite, $product.id_image, (isset($image_shape) && $image_shape == 'rect_img') ? 'rect_default' : 'home_default')}" alt="{$product.name|escape:html:'UTF-8'}" />
                                            </a>
                                        </div>
                                        
                                        
                                        <div class="featured_product_details">
						<h5><a href="{$product.link}" title="{$product.name|escape:'htmlall':'UTF-8'}">{$product.name|truncate:28:'...'|escape:'htmlall':'UTF-8'}</a></h5>
						
                                                {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                                                    <div class="price_container">
                                                        <span class="price">
                                                
                                                        {if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
                                                            
                                                        {if ((isset($display_tax_label) && $display_tax_label == 1) OR !isset($display_tax_label))}
                                                            {if $priceDisplay == 1}{l s='tax excl.'}{else}{l s='tax incl.'}{/if}
                                                        {/if}
                                                        
                                                        </span>
                                                        
                                                        {if isset($product.reduction) && $product.reduction != 0}
                                                            <span class="old-price">{convertPrice price=$product.price_without_reduction}</span>
                                                        {/if}
                                                        
                                                        {if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
                                                          <span class="on_sale">{l s='On sale!'}</span>     
                                                        {elseif isset($product.reduction) && $product.reduction && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
                                                         <span class="discount">{l s='On sale!'}</span>
                                                        {/if}
                                                        
                                                    </div>
                                                {/if}
					</div>
				</li>
			{/foreach}
			</ul>
		</div>
	{else}
		<p>{l s='No featured products' mod='homefeatured'}</p>
	{/if}
</div>
<!-- /MODULE Home Featured Products -->
