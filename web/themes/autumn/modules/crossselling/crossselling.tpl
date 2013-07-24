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
{if isset($orderProducts) && count($orderProducts)}
<div id="blockcrossselling" class="clearfix {$image_shape}">
	<script type="text/javascript">var cs_middle = {$middlePosition_crossselling};</script>
	        
        <div class="title_block group">

            <h4 class="productscategory_h4">{l s='Customers who bought this product also bought:' mod='crossselling'}</h4>

            <div class="carousel_controls {if count($orderProducts) <= 5}hide{/if}">
                <a class="carousel_prev" href="#"></a>
                <a class="carousel_next" href="#"></a>
            </div>

        </div>
        
	<div id="crossselling">
        {*<div id="{if count($orderProducts) > 5}crossselling{else}crossselling_noscroll{/if}">*}
		
		<div id="crossselling_list">
			<ul class="carousel_products">
				{foreach from=$orderProducts item='orderProduct' name=orderProduct}
				<li>
					<a href="{$orderProduct.link}" title="{$orderProduct.name|htmlspecialchars}" class="lnk_img">
                                            <img src="{$link->getImageLink($orderProduct.link_rewrite, $orderProduct.product_id|cat:'-'|cat:$orderProduct.id_image, (isset($image_shape) && $image_shape == 'rect_img') ? 'rect_default' : 'home_default')}" alt="{$orderProduct.name}" />
                                        </a>
					<p class="product_name flex-caption"><a href="{$orderProduct.link}" title="{$orderProduct.name|htmlspecialchars}">{$orderProduct.name|truncate:25:'...'|escape:'htmlall':'UTF-8'}</a></p>
					{if $crossDisplayPrice AND $orderProduct.show_price == 1 AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
						<span class="price_display">
							<span class="price">{convertPrice price=$orderProduct.displayed_price}</span>
						</span><br />
					{else}
						<br />
					{/if}
					<!-- <a title="{l s='View' mod='crossselling'}" href="{$orderProduct.link}" class="button_small">{l s='View' mod='crossselling'}</a><br /> -->
				</li>
				{/foreach}
			</ul>
		</div>
	
	{*</div>*}
        </div>
</div>
        
<script>
    $(document).ready(function(){
    
            $('#crossselling_list').jcarousel({
                'wrap': 'circular'
            });

            $('#blockcrossselling .carousel_prev').jcarouselControl({
                target: '-=1'
            });

            $('#blockcrossselling .carousel_next').jcarouselControl({
                target: '+=1'
            });

    });
</script>
        
        
{/if}
