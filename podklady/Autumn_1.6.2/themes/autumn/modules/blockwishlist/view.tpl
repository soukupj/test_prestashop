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

<div id="view_wishlist">
<h2>{l s='Wishlist' mod='blockwishlist'}</h2>

{if $wishlists && $wishlists|count > 1}
    <p>
	{l s='Other wishlists of' mod='blockwishlist'} {$current_wishlist.firstname} {$current_wishlist.lastname}:
	{foreach $wishlists as $wishlist}
		{if $wishlist.id_wishlist != $current_wishlist.id_wishlist}
                    <a style="font-weight:700!important" href="{$base_dir_ssl}modules/blockwishlist/view.php?token={$wishlist.token}">{$wishlist.name}</a>&nbsp;
		{/if}
	{/foreach}
    </p>
{/if}

{if $products}
    <div id="wishlist_view">
	<h3 class="title">{l s='Welcome to the wishlist of' mod='blockwishlist'} {$current_wishlist.firstname} {$current_wishlist.lastname}: {$current_wishlist.name}</h3>
	
        <ul class="wishlist grid">
	{foreach from=$products item=product name=i}
	 
		<li class="product"{if $smarty.foreach.i.last}last_item{elseif $smarty.foreach.i.first}first_item{/if} {if $smarty.foreach.i.index % 2}alternate_item{else}item{/if}" id="block_{$product.id_product}_{$product.id_product_attribute}">
                    
                    <a class="product_name" href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category_rewrite)}" title="{l s='View' mod='blockwishlist'}">
                        {$product.name|truncate:30:'...'|escape:'htmlall':'UTF-8'}
                    </a>
                        
                    <a class="product_image" href="{$link->getProductlink($product.id_product, $product.link_rewrite, $product.category_rewrite)}" title="{l s='Product detail' mod='blockwishlist'}" class="product_image">
                        <img src="{$link->getImageLink($product.link_rewrite, $product.cover, 'medium_default')}" alt="{$product.name|escape:'htmlall':'UTF-8'}" />				
                    </a>
                    
                    <span class="product_detail">
                        {if isset($product.attributes_small)}
                            <a class="product_attributes" href="{$link->getProductlink($product.id_product, $product.link_rewrite, $product.category_rewrite)}" title="{l s='Product detail' mod='blockwishlist'}">
                                {$product.attributes_small|escape:'htmlall':'UTF-8'}
                            </a>
                        {/if}
                        
                        <span class="product_qty">
                            {l s='Quantity:' mod='blockwishlist'}
                            <span style="{if $product.priority eq 0}color:darkred;{elseif $product.priority eq 1}color:darkorange;{else}color:green;{/if}" id="{$product.id_product}_{$product.id_product_attribute}" >{$product.quantity|intval}</span>
                        </span>
                        
                        <span class="product_priority">
                            {l s='Priority:' mod='blockwishlist'}
                            {if $product.priority eq 0}
                                <span style="color:darkred;">{l s='High' mod='blockwishlist'}</span>
                            {elseif $product.priority eq 1}
                                <span style="color:darkorange;">{l s='Medium' mod='blockwishlist'}</span>
                            {else}
                                <span style="color:green;">{l s='Low' mod='blockwishlist'}</span>
                            {/if}
                        </span>
                    </span>
                    
                    <span class="product_buttons">
                        <a class="autumn-button clear" href="{$link->getProductLink($product.id_product,  $product.link_rewrite, $product.category_rewrite)}" title="{l s='View' mod='blockwishlist'}">{l s='View' mod='blockwishlist'}</a>

                        {if isset($product.attribute_quantity) AND $product.attribute_quantity >= 1 OR !isset($product.attribute_quantity) AND $product.product_quantity >= 1}
                            {if !$ajax}
                                <form id="addtocart_{$product.id_product|intval}_{$product.id_product_attribute|intval}" action="{$link->getPageLink('cart')}" method="post">
                                <p class="hidden">
                                        <input type="hidden" name="id_product" value="{$product.id_product|intval}" id="product_page_product_id"  />
                                        <input type="hidden" name="add" value="1" />
                                        <input type="hidden" name="token" value="{$token}" />
                                        <input type="hidden" name="id_product_attribute" id="idCombination" value="{$product.id_product_attribute|intval}" />
                                </p>
                                </form>
                            {/if}
                            <a href="javascript:;" class="exclusive" onclick="WishlistBuyProduct('{$token|escape:'htmlall':'UTF-8'}', '{$product.id_product}', '{$product.id_product_attribute}', '{$product.id_product}_{$product.id_product_attribute}', this, {$ajax});" title="{l s='Add to cart' mod='homefeatured'}">{l s='Add to cart' mod='blockwishlist'}</a>
                        {else}
                            <span class="exclusive">{l s='Add to cart' mod='blockwishlist'}</span>
                        {/if}
                    </span>
		</li>
                
            {/foreach}
        </ul>
        
    </div>
{else}
    <p class="warning">{l s='No products' mod='blockwishlist'}</p>
{/if}
</div>
