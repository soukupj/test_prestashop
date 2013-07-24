<ul id="megamenu">
{foreach $mega_menu as $root => $positions}
   
        <li class="root_menu">
            {if isset($positions.link) && $positions.link != ""}
                <a class="root_link" href="{$positions.link}">
                    {$root}
                </a>
            {else}
                <span class="root_link">
                    {$root}
                </span>
            {/if}
                        
            {if isset($positions.top) || isset($positions.bottom) || isset($positions.m_left) || isset($positions.m_right)}
               
                {if isset($positions.top.top_hide) && isset($positions.bottom.bottom_hide) && isset($positions.m_left.HIDE) && isset($positions.m_right.HIDE)}
                    {*Don't show anything*}
                {else}
                
                    <div class="megamenu_context">                       
                        
                        <div class="left_col {if isset($positions.m_right) && !isset($positions.m_right.HIDE)}with_right{/if}">
                            {if isset($positions.top) && !isset($positions.top.top_hide)}
                                <div id="megamenu_top">
                                    <ul>
                                        {if isset($positions.top.top_manufacturers)}
                                            <span class="title">{l s='Manufacturers' mod='autumnmegamenu'}</span>
                                            {foreach $positions.top.top_manufacturers as $manufacturer}
                                                <li class="top_manufacturer {if $manufacturer@first}first{elseif $manufacturer@last}last{/if}"><a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)}" {$manufacturer.name}">{$manufacturer.name|escape:'htmlall':'UTF-8'}</a></li>
                                            {/foreach}

                                        {elseif isset($positions.top.top_suppliers)}
                                            <span class="title">{l s='Suppliers' mod='autumnmegamenu'}</span>
                                            {foreach $positions.top.top_suppliers as $supplier}
                                                <li class="top_supplier {if $supplier@first}first{elseif $supplier@last}last{/if}"><a href="{$link->getsupplierLink($supplier.id_supplier, $supplier.link_rewrite)}">{$supplier.name|escape:'htmlall':'UTF-8'}</a></li>
                                            {/foreach}

                                        {/if}
                                    </ul>
                                </div>
                            {/if}


                            {if isset($positions.m_left) && !isset($positions.m_left.HIDE)}
                                <div id="megamenu_left">

                                    {if isset($positions.m_left.CAT)}
                                        <ul>
                                           {foreach $positions.m_left.CAT as $category}
                                               <li class="category_title {if ($category@first && $category@total > 1)}first{elseif ($category@last && $category@total > 1)}last{elseif $category@total == 1}one{/if}">
                                                   <a href="{$category.link}">{$category.name|escape:'htmlall':'UTF-8'}</a>

                                               {if isset($category.children)}
                                                   <ul class="category_childlist">
                                                        <ol class="category_sublist">
                                                            {foreach $category.children as $childName}

                                                                <li class="category_child"><a href="{$childName.link}">{$childName.name|escape:'htmlall':'UTF-8'}</a></li>

                                                                 {if isset($childName.children)}
                                                                     <ul class="childstep2">
                                                                         {foreach $childName.children as $childStep2Name => $childStep2Link}
                                                                             <li class="category_child"><a href="{$childStep2Link}">{$childStep2Name|escape:'htmlall':'UTF-8'}</a></li>
                                                                         {/foreach}
                                                                     </ul>
                                                                 {/if}

                                                               {if $childName@iteration is div by 10}
                                                                    </ol>
                                                                    <ol class="category_sublist">
                                                                {/if}  

                                                            {/foreach}
                                                         </ol>
                                                   </ul>
                                               {/if}

                                               </li>
                                           {/foreach}
                                        </ul>

                                    {elseif isset($positions.m_left.PRD)}
                                        <ul>
                                           {foreach $positions.m_left.PRD as $product}
                                                   <li class="m_left_product {if $product@first}first{elseif $product@last}last{/if}" >
                                                       <div class="m_left_prd_img_wrapper">
                                                           <a href="{$product.link|escape:'htmlall':'UTF-8'}" class="m_left_prd_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}">
                                                               <img class="m_left_prd_img" src="{$link->getImageLink($product.link_rewrite, $product.image_id, (isset($image_shape) && $image_shape == 'rect_img') ? 'rect_m_default' : 'medium_default')}" />
                                                           </a>
                                                       </div>
                                                       <div class="m_left_prd_name">
                                                           <a href="{$product.link|escape:'htmlall':'UTF-8'}" class="m_left_prd_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}">
                                                              {$product.name|escape:'htmlall':'UTF-8'}
                                                           </a>
                                                       </div>
                                                   </li>
                                           {/foreach}
                                        </ul>


                                    {elseif isset($positions.m_left.CMSL)}
                                       <ul>
                                           {foreach $positions.m_left.CMSL as $cmsl_name => $cmsl_link}
                                                   <li class="m_left_cmsl">
                                                      <a href="{$cmsl_link}">{$cmsl_name}</a>
                                                   </li>
                                           {/foreach}
                                       </ul>


                                    {elseif isset($positions.m_left.CMSP)}
                                       {foreach $positions.m_left.CMSP as $cmsp_id => $cmsp_content}
                                           <div class="m_left_cmsp">
                                               {$cmsp_content}
                                           </div>
                                       {/foreach}  


                                    {/if}

                                 </div>
                            {/if}

                            {if isset($positions.bottom) && !isset($positions.bottom.bottom_hide)}
                                <div id="megamenu_bottom">
                                    <ul>
                                        {if isset($positions.bottom.bottom_manufacturers)}
                                            <span class="title">{l s='Manufacturers' mod='autumnmegamenu'}</span>
                                            {foreach $positions.bottom.bottom_manufacturers as $manufacturer}
                                                <li class="bottom_manufacturer {if $manufacturer@first}first{elseif $manufacturer@last}last{/if}"><a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)}" {$manufacturer.name}">{$manufacturer.name|escape:'htmlall':'UTF-8'}</a></li>
                                            {/foreach}

                                        {elseif isset($positions.bottom.bottom_suppliers)}
                                            <span class="title">{l s='Suppliers' mod='autumnmegamenu'}</span>
                                            {foreach $positions.bottom.bottom_suppliers as $supplier}
                                                <li class="bottom_supplier {if $supplier@first}first{elseif $supplier@last}last{/if}"><a href="{$link->getsupplierLink($supplier.id_supplier, $supplier.link_rewrite)}">{$supplier.name|escape:'htmlall':'UTF-8'}</a></li>
                                            {/foreach}

                                        {/if}
                                    </ul>
                                </div>
                            {/if}
                         </div>
                         
                                                                       
                        {if isset($positions.m_right) && !isset($positions.m_right.HIDE)}
                            <div class="right_col">
                                <div id="megamenu_right">

                                    {if isset($positions.m_right.RNDF)}
                                        <ul>
                                            {foreach $positions.m_right as $key => $product}
                                                    <li class="m_right_random_prd" >
                                                        <span class="title">{l s='Random Featured' mod='autumnmegamenu'}</span>
                                                        <div class="m_right_prd_img_wrapper">
                                                            <a href="{$product.link|escape:'htmlall':'UTF-8'}" class="m_right_prd_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}">
                                                                <img class="m_right_prd_img" src="{$link->getImageLink($product.link_rewrite, $product.image_id, (isset($image_shape) && $image_shape == 'rect_img') ? 'rect_default' : 'home_default')}" />
                                                            </a>
                                                        </div>
                                                        <div class="m_right_prd_name">
                                                            <a href="{$product.link|escape:'htmlall':'UTF-8'}" class="m_right_prd_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}">
                                                               {$product.name|escape:'htmlall':'UTF-8'}
                                                            </a>
                                                        </div>
                                                    </li>
                                            {/foreach}
                                        </ul>


                                    {elseif isset($positions.m_right.RND)}
                                        <ul>
                                            {foreach $positions.m_right as $key => $product}
                                                    <li class="m_right_random_prd" >
                                                        <span class="title">{l s='Random Special' mod='autumnmegamenu'}</span>
                                                        <div class="m_right_prd_img_wrapper">
                                                            <a href="{$product.link|escape:'htmlall':'UTF-8'}" class="m_right_prd_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}">
                                                                <img class="m_right_prd_img" src="{$link->getImageLink($product.link_rewrite, $product.image_id, (isset($image_shape) && $image_shape == 'rect_img') ? 'rect_default' : 'home_default')}" />
                                                            </a>
                                                        </div>
                                                        <div class="m_right_prd_name">
                                                            <a href="{$product.link|escape:'htmlall':'UTF-8'}" class="m_right_prd_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}">
                                                               {$product.name|escape:'htmlall':'UTF-8'}
                                                            </a>
                                                        </div>
                                                    </li>
                                            {/foreach}
                                        </ul>


                                    {elseif isset($positions.m_right.IMG)}
                                        <div class="m_right_image">
                                            <img src="{$positions.m_right.IMG}" />
                                        </div>


                                    {/if}

                                 </div>
                            </div>
                        {/if}
                        
                        
                    </div>
            
                {/if}
             
             {/if}
                            
        </li>
    
{/foreach}
 
</ul>

<div id="megamenu-responsive">
    <ul id="megamenu-responsive-root">
        <li class="menu-toggle"><p></p>{l s='Navigation' mod='autumnmegamenu'}</li>
        <li class="root">
            {$responsive_menu}
        </li>
        
        
        <!-- STARTING OF AN EXAMPLE CODES FOR CUSTOM LINKS IN MOBILE MENU -- (DELETE THIS LINE TO ACTIVATE THEM)
        
        <li class="root">
            <ul>
                <li>
                    
                    <ul>
                        <li><a href="#" title=""><span>Extra Menu Item</span></a></li>
                        <li><a href="#" title=""><span>Extra Menu Item 2</span></a></li>

                        <li class="parent">
                            <a href="#" title=""><span>Extra Menu Item With Submenus</span></a>
                            <ul>
                                <li><a href="#" title=""><span>Submenu 1</span></a></li>
                                <li><a href="#" title=""><span>Submenu 2<span></a></li>
                            </ul>
                        </li>
                    </ul>
                    
                </li>
            </ul>
        </li>
        
        ENDING OF AN EXAMPLE CODES FOR CUSTOM LINKS IN MOBILE MENU -- (DELETE THIS LINE TO ACTIVATE THEM) -->
        
    </ul>
</div>
