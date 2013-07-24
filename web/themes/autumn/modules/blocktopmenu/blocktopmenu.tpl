{if $MENU != ''}
	
        
	<!-- Menu -->
        
        <script>
	 // DOM ready
	 $(function() {
	   
         // Create the dropdown base
         $("<select />",{ "class":"responsive-menu-select" }).appendTo("#responsive-menu");
      
         // Create default option "Go to..."
         $("<option />", {
            "selected": "selected",
            "value"   : "",
            "text"    : "{l s='Menu' mod='blocktopmenu'}"
         }).appendTo("#responsive-menu select");   
         
        $("#sf-top-menu a").each(function(index, item){
            
            $(item).children().remove();
            
            var depth = $(item).parents('ul').length;
            
            var indention = "";
            for (var i=1; i<depth; i++){
                indention += "&#8594;";
            };
            
            var html = indention + $(item).text();
            
            $("<option />", { "value":$(item).attr("href"), "html":html }).appendTo("#responsive-menu select");
            
        });

        $("#responsive-menu select").change(function() {
          window.location = $(this).find("option:selected").val();
        });

        });
	</script>
        
        
        <div id="responsive-menu" class="responsive-menu-container">
                
        </div>
        
        <div id="sf-top-menu" class="sf-contener clearfix">
            
                <ul id="sf-menu-ul" class="sf-menu clearfix">
			{$MENU}
			{if $MENU_SEARCH}
				<li class="sf-search noBack" style="float:right">
					<form id="searchbox" action="{$link->getPageLink('search')}" method="get">
						<p>
							<input type="hidden" name="controller" value="search" />
							<input type="hidden" value="position" name="orderby"/>
							<input type="hidden" value="desc" name="orderway"/>
							<input type="text" name="search_query" value="{if isset($smarty.get.search_query)}{$smarty.get.search_query|escape:'htmlall':'UTF-8'}{/if}" />
						</p>
					</form>
				</li>
			{/if}
		</ul>
		<div class="sf-right">&nbsp;</div>
         </div>
	<!--/ Menu -->
{/if}