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

{if count($categoryProducts) > 0 && $categoryProducts !== false}
<div class="clearfix blockproductscategory {$image_shape}">
        <div class="title_block group">

            <h4 class="productscategory_h4">{$categoryProducts|@count} {if $categoryProducts|@count == 1}{l s='other product in the same category:' mod='productscategory'}{else}{l s='other products in the same category:' mod='productscategory'}{/if}</h4>

            <div class="carousel_controls {if count($categoryProducts) <= 5}hidden{/if}">
                <a class="carousel_prev" href="#"></a>
                <a class="carousel_next" href="#"></a>
            </div>

        </div>
        
        <div id="productscategory">
            
            <div id="productscategory_list">
                    <ul class="carousel_products">
                            {foreach from=$categoryProducts item='categoryProduct' name=categoryProduct}
                            <li {if $smarty.foreach.categoryProduct.last}class="last-item"{/if}>
                                    <a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" class="lnk_img" title="{$categoryProduct.name|htmlspecialchars}">
                                        <img src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, (isset($image_shape) && $image_shape == 'rect_img') ? 'rect_default' : 'home_default')}" alt="{$categoryProduct.name|htmlspecialchars}" />
                                    </a>
                                    <p class="product_name flex-caption">
                                            <a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" title="{$categoryProduct.name|htmlspecialchars}">{$categoryProduct.name|truncate:25:'...'|escape:'htmlall':'UTF-8'}</a>
                                    </p>
                                    {if $ProdDisplayPrice AND $categoryProduct.show_price == 1 AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                                    <p class="price_display">
                                            <span class="price">{convertPrice price=$categoryProduct.displayed_price}</span>
                                    </p>
                                    {else}
                                    <br />
                                    {/if}
                            </li>
                            {/foreach}
                    </ul>
            </div>
           
	</div>
	
</div>
                    
<script>
    $(document).ready(function(){
    
            $('#productscategory_list').jcarousel({
                'wrap': 'circular'
            });

            $('.blockproductscategory .carousel_prev').jcarouselControl({
                target: '-=1'
            });

            $('.blockproductscategory .carousel_next').jcarouselControl({
                target: '+=1'
            });

    });
</script>

{/if}

