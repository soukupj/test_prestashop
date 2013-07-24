{$categoryViewTypeHeader}

<div id="category_view_changer">
    <span class="category_view_changer_label">{l s='View:'}</span>
    <a href="#" class="list_view {if $category_view_type == "list_view"}active{/if}" title="{l s='List View'}"></a>
    <a href="#" class="grid_view {if $category_view_type == "grid_view"}active{/if}" title="{l s='Grid View'}"></a>
</div>