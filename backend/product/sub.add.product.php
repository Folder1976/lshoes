<?php


?>
        <tr id="new">
			<td class="mixed">новый</td>
				<td class="mixed"><input type="checkbox" id="status" checked>
					<input type="hidden" id="edit_product_id" value="">
				</td>
				<td>
					<a href="javascript:;" class="category_tree select_category" id="category_path_new" data-id="category_id_new">выбрать [дерево]</a> (<span class="selected_category" id="name_category_id_new">Все...</span>)
					<input type="hidden" name="category"  id="category_id_new" class="selected_category_id" value="0">
				</td>
				<td class="mixed"><input type="text" id="model" style="width:100%;"
					value=""></td>
				<td class="mixed"><input type="text" id="code" style="width:100%;"
					value=""></td>
				<td>Позже</td>	
				<td id="product_attribute_wrappernew">
					<select class="product_attribute_group" id="product_attribute_groupnew">
						<option value="0">Выбрать</option>
						<?php foreach($attributes_group_list as $index => $row){ ?>
							<option value="<?php echo $row['attribute_group_id']; ?>"><?php echo $row['name']; ?></option>
						<?php } ?>
					</select>
					<input type="text" placeholder="Новый атрибут" class="product_attribute" id="product_attributenew">
				</td>
				<td style="width:120px;">
					<select class="edit" id="size_group_id" style="width:100px;">
						<option value="0">* Без размеров *</option>
						<?php foreach($size_group_list as $index => $value){?>
							<option value="<?php echo $index; ?>"><?php echo $value['name']; ?></option>
						<?php } ?>
	                </select>
					<a href="/backend/index.php?route=size/size.main.index.php" target="_blank"><b>+</b></a>
					<div class="add_size_wrapper_new"></div>
				</td>
				<td class="total_quantity" id="total_quantity_new"></td>
				<td style="width:120px;">
					<select class="edit" id="manufacturer_id" style="width:100px;">
						<option value="0">Выбрать</option>
						<?php foreach($brand_list as $index => $value){?>
							<option value="<?php echo $index; ?>"><?php echo $value['name']; ?></option>
						<?php } ?>
	                </select>
					<a href="/backend/index.php?route=brands/brands.index.php" target="_blank"><b>+</b></a>
				</td>
				<td class="mixed" style="max-width: 60px;"><input type="text" id="zakup" style="width:100%;"
					value="0.00"></td>
				<td class="mixed" style="max-width: 60px;"><input type="text" id="price" style="width:100%;"
					value="0.00"></td>
				<td class="mixed" style="max-width: 60px;"><input type="text" id="sort_order" style="width:70px;" value="0"></td>
				
				<td>        
				<a href="javascript:" class="add">
					<img src="/<?php echo TMP_DIR; ?>backend/img/add.png" title="Добавить" width="16" height="16">
				</a>
			</td>              
		</tr>
		<td>
			<td colspan="14">&nbsp;</td>
		</td>
        
<script>

    $(document).on('click', '.attribute_list_close', function(){

		$('.attribute_list').hide();
		$('.attribute_list').html('');

	});
	
		
	$(document).on('focus', '.product_attribute', function(){
		
		var product_id = $(this).parent('td').parent('tr').attr('id');
		var attribute_group_id = $('#product_attribute_group'+product_id).val();
		
		attributes_form(product_id, attribute_group_id);
		 
	});
	
	$(document).on('change', '.product_attribute_group', function(){
		
		var product_id = $(this).parent('td').parent('tr').attr('id');
		var attribute_group_id = $('#product_attribute_group'+product_id).val();
		
		attributes_form(product_id, attribute_group_id);
		 
	});
	
		
	jQuery(document).on('click','.dell_attribute', function(){
       
		var post = 'key=dell_attribute';
		post = post + '&product_id='+jQuery(this).data('product_id');
		post = post + '&attribute_id='+jQuery(this).data('attribute_id');
		
		$(this).parent('div').hide();
		
		if(jQuery(this).data('product_id') != 'new'){
			jQuery.ajax({
				type: "POST",
				url: "/backend/ajax/ajax_edit_product.php",
				dataType: "text",
				data: post,
				beforeSend: function(){
				},
				success: function(msg){
					//console.log( msg );
				}
			});
		}
    });
	
	jQuery(document).on('change','.product_attribute', function(){
        
		var product_id = jQuery(this).parent('td').parent('tr').attr('id');
		var attribute_group_id = $('#product_attribute_group'+product_id).val();
		var value = $(this).val();
	    
		if(value.length > 0 &&  attribute_group_id < 1){
			$('#product_attribute_group'+product_id).css('background-color','#FF899B');
			
			setTimeout(function(product_id){
				$('#product_attribute_group'+product_id).css('background-color','none');
			},1000);
			
		}
		
		saveAttribute(product_id, attribute_group_id, 0, value);
        
    });
	
	jQuery(document).on('click','.select_attribute', function(){
        
		var product_id = jQuery(this).data('product_id');
		var attribute_id = jQuery(this).data('attribute_id');
		var value = jQuery(this).html();
	    
		saveAttribute(product_id, 0, attribute_id, value);
        
    });
	
	
	function saveAttribute(product_id, attribute_group_id, attribute_id, value){
		
		var post = 'key=add_attribute_to_tovar';
		post = post + '&product_id='+product_id;
		post = post + '&attribute_group_id='+attribute_group_id;
		post = post + '&attribute_id='+attribute_id;
	    post = post + '&name='+value;
	    
        jQuery.ajax({
            type: "POST",
            url: "/backend/ajax/ajax_edit_product.php",
            dataType: "text",
            data: post,
            beforeSend: function(){
            },
            success: function(msg){
                //console.log( msg );
				
				$('#product_attribute_wrapper'+product_id).append(msg);
            }
        });
		
		$('.attribute_list_close').trigger('click');
	}
	
	function saveAttributeAsNew(product_id, attribute_group_id, attribute_id, value){
		
		var post = 'key=add_attribute_to_tovar';
		post = post + '&product_id='+product_id;
		post = post + '&attribute_group_id='+attribute_group_id;
		post = post + '&attribute_id='+attribute_id;
	    post = post + '&name='+value;
	    
        jQuery.ajax({
            type: "POST",
            url: "/backend/ajax/ajax_edit_product.php",
            dataType: "text",
            data: post,
            beforeSend: function(){
            },
            success: function(msg){
                //console.log( msg );
            }
        });
		
	}
	
	
	function attributes_form(product_id, attribute_group_id){
		
		var position = $('#product_attribute' + product_id).position();
		$('.attribute_list').css('top',(position.top+200)+'px' );
		$('.attribute_list').show();
		
		var post = 'key=get_attribute_list';
		post = post + '&attribute_group_id='+attribute_group_id;
		post = post + '&product_id='+product_id;
		
		jQuery.ajax({
            type: "POST",
            url: "/backend/ajax/ajax_edit_product.php",
            dataType: "text",
            data: post,
            beforeSend: function(){
            },
            success: function(msg){
                
				$('.attribute_list').html( msg );
				
            }
        });

	}
	
</script>