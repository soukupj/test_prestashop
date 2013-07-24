{* Autumn Theme - Showcase Module - 2012 - Sercan YEMEN - twitter.com/sercan *}
<script type="text/javascript">  
    var autoscroll = {if isset($showcases.autoscroll) && $showcases.autoscroll}true{else}false{/if};
    var autoscrollInterval = {if isset($showcases.autoscrollInterval) && $showcases.autoscrollInterval}{$showcases.autoscrollInterval}{else}0{/if};
</script>

    {if isset($showcases.featured) && $showcases.featured}
        
        <div class="autumnshowcase_block group featured_products {(isset($image_shape) && $image_shape == 'rect_img') ? 'rect_img' : ' '}">
            
            <div class="title_block group">
                
                <h4>{l s='Featured products' mod='autumnshowcase'}</h4>

                <div class="carousel_controls {if count($featured_products) <= 5}hide{/if}">
                    <a class="carousel_prev" href="#"></a>
                    <a class="carousel_next" href="#"></a>
                </div>

            </div>
                 
	{if isset($featured_products) && $featured_products}
            <div class="autumnshowcase_carousel">
                <ul>
                    
                {foreach $featured_products as $featured}
                    <li class="ajax_block_product {if $featured@first}first_item{elseif $featured@last}last_item{else}item{/if}">
                        
                        <a class="image_link" href="{$featured.link}" title="{$featured.name|escape:html:'UTF-8'}">
                            {if isset($featured.new) && $featured.new == 1}<span class="new">{l s='New' mod='autumnshowcase'}</span>{/if}
                            <img class="product_image" src="{$link->getImageLink($featured.link_rewrite, $featured.id_image, (isset($image_shape) && $image_shape == 'rect_img') ? 'rect_default' : 'home_default')}" alt="{$featured.name|escape:html:'UTF-8'}" />
                        </a>
                        
                        <div class="product_details">
                            <h5><a href="{$featured.link}" title="{$featured.name|escape:'htmlall':'UTF-8'}">{$featured.name|truncate:28:'...'|escape:'htmlall':'UTF-8'}</a></h5>

                            {if $featured_prices && $featured.show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                                <div class="price_container">
                                    <span class="price">{if !$priceDisplay}{convertPrice price=$featured.price}{else}{convertPrice price=$featured.price_tax_exc}{/if}</span>

                                    {if isset($featured.reduction) && $featured.reduction != 0}
                                        <span class="old-price">{convertPrice price=$featured.price_without_reduction}</span>
                                    {/if}

                                    {if isset($featured.on_sale) && $featured.on_sale && isset($featured.show_price) && $featured.show_price && !$PS_CATALOG_MODE}
                                      <span class="discount">{l s='On sale!' mod='autumnshowcase'}</span>     
                                    {elseif isset($featured.reduction) && $featured.reduction && isset($featured.show_price) && $featured.show_price && !$PS_CATALOG_MODE}
                                     <span class="discount">{l s='Discount!' mod='autumnshowcase'}</span>
                                    {/if}

                                </div>
                            {/if}
			</div>
                        
                        {if $featured_cart}
                            {if ($featured.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $featured.available_for_order && !isset($restricted_country_mode) && $featured.minimal_quantity == 1 && $featured.customizable != 2 && !$PS_CATALOG_MODE}
                                
                                {if ($featured.quantity > 0 || $featured.allow_oosp)}
                                    <a class="ajax_add_to_cart_button autumn-button" rel="ajax_id_product_{$featured.id_product}" href="{$link->getPageLink('cart')}?qty=1&amp;id_product={$featured.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart' mod='autumnshowcase'}">{l s='Add to cart' mod='autumnshowcase'}</a>
                                {else}
                                    {*<span class="out-of-stock">{l s='- Out of stock -'}</span>*}
                                {/if}
                                
                            {/if}
                        {/if}
                        
                    </li>
                {/foreach}
                
                </ul>
            </div>
	{else}
            
            <p>{l s='No featured products' mod='autumnshowcase'}</p>
            
	{/if}
        
        </div>
        
    {/if}

    {if isset($showcases.new) && $showcases.new}
              
        <div class="autumnshowcase_block group new_products {(isset($image_shape) && $image_shape == 'rect_img') ? 'rect_img' : ' '}">
            
            <div class="title_block group">
                
                <h4>{l s='New products' mod='autumnshowcase'}</h4>

                <div class="carousel_controls {if count($new_products) <= 5}hide{/if}">
                    <a class="carousel_prev" href="#"></a>
                    <a class="carousel_next" href="#"></a>
                </div>

            </div>
        
	{if isset($new_products) && $new_products}
            <div class="autumnshowcase_carousel">
                <ul>
                    
                {foreach $new_products as $new}
                    <li class="ajax_block_product {if $new@first}first_item{elseif $new@last}last_item{else}item{/if}">
                                                
                        <a class="image_link" href="{$new.link}" title="{$new.name|escape:html:'UTF-8'}">
                            {if isset($new.new) && $new.new == 1}<span class="new">{l s='New' mod='autumnshowcase'}</span>{/if}
                            <img class="product_image" src="{$link->getImageLink($new.link_rewrite, $new.id_image, (isset($image_shape) && $image_shape == 'rect_img') ? 'rect_default' : 'home_default')}" alt="{$new.name|escape:html:'UTF-8'}" />
                        </a>
                        
                        <div class="product_details">
                            <h5><a href="{$new.link}" title="{$new.name|escape:'htmlall':'UTF-8'}">{$new.name|truncate:28:'...'|escape:'htmlall':'UTF-8'}</a></h5>

                            {if $new_prices && $new.show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                                <div class="price_container">
                                    <span class="price">{if !$priceDisplay}{convertPrice price=$new.price}{else}{convertPrice price=$new.price_tax_exc}{/if}</span>

                                    {if isset($new.reduction) && $new.reduction != 0}
                                        <span class="old-price">{convertPrice price=$new.price_without_reduction}</span>
                                    {/if}

                                    {if isset($new.on_sale) && $new.on_sale && isset($new.show_price) && $new.show_price && !$PS_CATALOG_MODE}
                                     <span class="discount">{l s='On sale!' mod='autumnshowcase'}</span>     
                                    {elseif isset($new.reduction) && $new.reduction && isset($new.show_price) && $new.show_price && !$PS_CATALOG_MODE}
                                     <span class="discount">{l s='Discount!' mod='autumnshowcase'}</span>
                                    {/if}

                                </div>
                            {/if}
			</div>
                        
                        {if $new_cart}
                            {if ($new.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $new.available_for_order && !isset($restricted_country_mode) && $new.minimal_quantity == 1 && $new.customizable != 2 && !$PS_CATALOG_MODE}
                                {if ($new.quantity > 0 || $new.allow_oosp)}
                                    <a class="ajax_add_to_cart_button autumn-button" rel="ajax_id_product_{$new.id_product}" href="{$link->getPageLink('cart')}?qty=1&amp;id_product={$new.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart' mod='autumnshowcase'}">{l s='Add to cart' mod='autumnshowcase'}</a>
                                {else}
                                   {*<span class="out-of-stock">{l s='- Out of stock -'}</span>*}
                                {/if}
                            {/if}
                        {/if}
                        
                    </li>
                {/foreach}
                
                </ul>
            </div>
	{else}
            
            <p>{l s='No new products' mod='autumnshowcase'}</p>
            
	{/if}
        
        </div>
        
    {/if}

    
    
    
    {if isset($showcases.special) && $showcases.special}
               
        <div class="autumnshowcase_block group special_procducts {(isset($image_shape) && $image_shape == 'rect_img') ? 'rect_img' : ' '}">
            
            <div class="title_block group">
                
                <h4>{l s='Special products' mod='autumnshowcase'}</h4>

                <div class="carousel_controls {if count($special_products) <= 5}hide{/if}">
                    <a class="carousel_prev" href="#"></a>
                    <a class="carousel_next" href="#"></a>
                </div>

            </div>
        
	{if isset($special_products) && $special_products}
            <div class="autumnshowcase_carousel">
                <ul>
                    
                {foreach $special_products as $special}
                    <li class="ajax_block_product {if $special@first}first_item{elseif $special@last}last_item{else}item{/if}">
                                                
                        <a class="image_link" href="{$special.link}" title="{$special.name|escape:html:'UTF-8'}">
                            {if isset($special.new) && $special.new == 1}<span class="new">{l s='New' mod='autumnshowcase'}</span>{/if}
                            <img class="product_image" src="{$link->getImageLink($special.link_rewrite, $special.id_image, (isset($image_shape) && $image_shape == 'rect_img') ? 'rect_default' : 'home_default')}" alt="{$special.name|escape:html:'UTF-8'}" />
                        </a>
                        
                        <div class="product_details">
                            <h5><a href="{$special.link}" title="{$special.name|escape:'htmlall':'UTF-8'}">{$special.name|truncate:28:'...'|escape:'htmlall':'UTF-8'}</a></h5>

                            {if $special_prices && $special.show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                                <div class="price_container">
                                    <span class="price">{if !$priceDisplay}{convertPrice price=$special.price}{else}{convertPrice price=$special.price_tax_exc}{/if}</span>

                                    {if isset($special.reduction) && $special.reduction != 0}
                                        <span class="old-price">{convertPrice price=$special.price_without_reduction}</span>
                                    {/if}

                                    {if isset($special.on_sale) && $special.on_sale && isset($special.show_price) && $special.show_price && !$PS_CATALOG_MODE}
                                     <span class="discount">{l s='On sale!' mod='autumnshowcase'}</span>     
                                    {elseif isset($special.reduction) && $special.reduction && isset($special.show_price) && $special.show_price && !$PS_CATALOG_MODE}
                                     <span class="discount">{l s='Discount!' mod='autumnshowcase'}</span>
                                    {/if}

                                </div>
                            {/if}
			</div>
                        
                        {if $special_cart}
                            {if ($special.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $special.available_for_order && !isset($restricted_country_mode) && $special.minimal_quantity == 1 && $special.customizable != 2 && !$PS_CATALOG_MODE}
                                {if ($special.quantity > 0 || $special.allow_oosp)}
                                    <a class="ajax_add_to_cart_button autumn-button" rel="ajax_id_product_{$special.id_product}" href="{$link->getPageLink('cart')}?qty=1&amp;id_product={$special.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart' mod='autumnshowcase'}">{l s='Add to cart' mod='autumnshowcase'}</a>
                                {else}
                                    {*<span class="out-of-stock">{l s='- Out of stock -'}</span>*}
                                {/if}
                            {/if}
                        {/if}
                        
                    </li>
                {/foreach}
                
                </ul>
            </div>
	{else}
            
            <p>{l s='No special products' mod='autumnshowcase'}</p>
            
	{/if}
        
        </div>
    {/if}