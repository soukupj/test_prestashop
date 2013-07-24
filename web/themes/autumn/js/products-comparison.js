/*
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
*/

$('document').ready(function(){
	reloadProductComparison();
});

reloadProductComparison = function() {
	$('a.cmp_remove').click(function(){

		var idProduct = $(this).attr('rel').replace('ajax_id_product_', '');

		$.ajax({
  			url: 'index.php?controller=products-comparison&ajax=1&action=remove&id_product=' + idProduct,
 			async: false,
                        cache: false,
  			success: function(){
                            return true;
                        }
		});	
	});

	$('input:checkbox.comparator').click(function(){
	
		var idProduct = $(this).attr('value').replace('comparator_item_', '');
		var checkbox = $(this);
		
		if(checkbox.is(':checked'))
{
			$.ajax({
	  			url: 'index.php?controller=products-comparison&ajax=1&action=add&id_product=' + idProduct,
	 			async: true,
                                cache: false,
	  			success: function(data){
	  				if (data === '0'){
                                            checkbox.attr('checked', false);
                                            alert(max_item);
                                        }
	  			},
	    		error: function(){
	    			checkbox.attr('checked', false);
	    		}
			});	
		}
		else
		{
			$.ajax({
	  			url: 'index.php?controller=products-comparison&ajax=1&action=remove&id_product=' + idProduct,
	 			async: true,
                                cache: false,
	  			success: function(data){
	  				if (data === '0')
	  					checkbox.attr('checked', true);
	    		},
	    		error: function(){
	    			checkbox.attr('checked', true);
	    		}
			});	
		}
	});
}