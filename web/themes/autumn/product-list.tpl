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

{if isset($products)}
        <div id="product_list_wrapper" class="group">
            
            <div class="resumecat category-product-count">
                {if !isset($packItems)}
                    {if isset($category) && $category->id == 1 OR $nb_products == 0}
                        {l s='There are no products in this category.'}
                    {else}
                        {if $nb_products <= $n && $nb_products == 1}
                            {l s='%d item' sprintf=$nb_products}
                        {elseif $nb_products <= $n && $nb_products > 1}
                            {l s='%d items' sprintf=$nb_products}
                        {else}
                            {assign var='starting_point' value=(($p - 1) * $n) + 1}
                            {l s='Items'} {$starting_point} {l s='to'}            
                            {if $p == $pages_nb}
                                {$nb_products}
                            {else}
                                {$starting_point + ($n - 1)}
                            {/if}
                                {l s='of'} {$nb_products} {l s='total'}
                        {/if}
                    {/if}
                 {/if}
            </div>
            
            <div class="grid-container">                
                <ul id="product_list" class="grid">

                    {foreach from=$products item=product name=products}
                        <li class="ajax_block_product {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if} {if $smarty.foreach.products.index % 2}alternate_item{else}item{/if} group">
                                
                                <div class="product_image_wrapper">
                                    <div class="product_list_hover hide-tablet-n-mobile">

                                        <div class="product_list_compare">
                                            {if isset($comparator_max_item) && $comparator_max_item}
                                                <input type="checkbox" class="comparator" id="comparator_item_{$product.id_product}" value="comparator_item_{$product.id_product}" {if isset($compareProducts) && in_array($product.id_product, $compareProducts)}checked="checked"{/if} /> 
                                                <label for="comparator_item_{$product.id_product}">{l s='Select to compare'}</label>
                                            {/if}
                                        </div>

                                        {if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
                                             {if ($product.allow_oosp || $product.quantity > 0)}
                                                 <div class="product_list_add_to_cart">
                                                    {if isset($static_token)}
                                                        <a class="ajax_add_to_cart_button autumn_add_to_cart_ph" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)}" title="{l s='Add to cart'}">{l s='Add to cart'}</a>
                                                    {else}
                                                        <a class="ajax_add_to_cart_button autumn_add_to_cart_ph" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}", false)}" title="{l s='Add to cart'}">{l s='Add to cart'}</a>
                                                    {/if}                                               
                                                </div>
                                             {/if}
                                        {/if}
                                        
                                        <a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_list_view_details_link" title="{$product.name|escape:'htmlall':'UTF-8'}">{l s='View Details'}</a>
                                        
                                    </div>
                                                                                                               
                                    <a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}"><img class="product_image" src="{$link->getImageLink($product.link_rewrite, $product.id_image, (isset($image_shape) && $image_shape == 'rect_img') ? 'rect_default' : 'home_default')}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} />
                                        {if isset($product.new) && $product.new == 1}<span class="new">{l s='New'}</span>{/if}
                                    </a>
                                    
                                </div>
                                
                                <div class="product_list_details">
                                    <div class="product_list_details_left">
                                        <h5><a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.name|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'|truncate:35:'...'}</a></h5>
                                        <p class="product_desc"><a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}" >{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}</a></p>
                                        
                                        {if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
                                            <div class="price_container">
                                                {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}<span class="price">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span>{/if}

                                                {if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}<span class="availability">{if ($product.allow_oosp || $product.quantity > 0)}{l s='Available'}{elseif (isset($product.quantity_all_versions) && $product.quantity_all_versions > 0)}{l s='Product available with different options'}{else}{l s='Out of stock'}{/if}</span>{/if}

                                                {if isset($product.reduction) && $product.reduction != 0}
                                                    <span class="old-price">{convertPrice price=$product.price_without_reduction}</span>
                                                {/if}                                                
                                            </div>

                                            {if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
                                               <span class="discount">{l s='On sale!'}</span>     
                                            {elseif isset($product.reduction) && $product.reduction && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
                                               <span class="discount">{l s='Discount'}</span>
                                            {/if}

                                            {if isset($product.online_only) && $product.online_only}<span class="online_only">{l s='Online only'}</span>{/if}

                                        {/if}
                                    </div>
                                    
                                    <div class="product_list_details_right">
                                        <div class="product_list_add_to_cart">
                                            {if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
                                                
                                                {if ($product.allow_oosp || $product.quantity > 0)}
                                                    {if isset($static_token)}
                                                        <a class="ajax_add_to_cart_button autumn_add_to_cart link" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)}" title="{l s='Add to cart'}">{l s='Add to cart'}</a>
                                                    {else}
                                                        <a class="ajax_add_to_cart_button autumn_add_to_cart link" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add&amp;id_product={$product.id_product|intval}", false)}" title="{l s='Add to cart'}">{l s='Add to cart'}</a>
                                                    {/if}                                               
                                                {else}
                                                    <span class="out-of-stock">{l s='Out of stock'}</span>
                                                {/if}
                                                
                                            {/if}
                                        </div>
                                        
                                        <div class="product_list_compare">
                                            {if isset($comparator_max_item) && $comparator_max_item}
                                                <input type="checkbox" class="comparator" id="comparator_item_{$product.id_product}" value="comparator_item_{$product.id_product}" {if isset($compareProducts) && in_array($product.id_product, $compareProducts)}checked="checked"{/if} /> 
                                                <label for="comparator_item_{$product.id_product}">{l s='Select to compare'}</label>
                                            {/if}
                                        </div>
                                    </div>
                                        
                                </div>
                        </li>
                {/foreach}
                </ul>
            </div>
         </div>                    
{/if}