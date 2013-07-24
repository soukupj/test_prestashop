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

{if $userLoggedIn}

<p class="buttons_bottom_block">
    
    {if isset($wishlists) && $wishlists}
        
        <p class="add_to_wishlist">
            {l s='Add to wishlist:' mod='blockwishlist'}
            
            <select name="wishlists" id="wishlists" onchange="WishlistChangeDefault('wishlist_block_list', $('#wishlists').val());">
                {foreach from=$wishlists item=wishlist name=i}
                    <option value="{$wishlist.id_wishlist}"{if $id_wishlist eq $wishlist.id_wishlist or ($id_wishlist == false and $smarty.foreach.i.first)} selected="selected"{/if}>{$wishlist.name|truncate:22:'...'|escape:'htmlall':'UTF-8'}</option>
                {/foreach}
            </select>
        
            <a href="#" id="wishlist_button" class="autumn-button" onclick="WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', $('#idCombination').val(), document.getElementById('quantity_wanted').value); return false;">
                {l s='Add' mod='blockwishlist'}
            </a>
        </p>
            
    {else if isset($wishlists) && !$wishlists}
        <p class="add_to_wishlist">
            <a class="create_wishlist autumn-button" href="{$wishlist_link}" title="{l s='My wishlists' mod='blockwishlist'}">{l s='Create a wishlist' mod='blockwishlist'}</a>
        </p>
    {/if}
        
    
</p>

{/if}
