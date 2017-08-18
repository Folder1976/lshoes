<!-- Sergey Kotlyarov 2016 folder.list@gmail.com -->
<link rel="stylesheet" type="text/css" href="/<?php echo TMP_DIR;?>backend/libs/category_tree/type-for-get.css">
<link rel="stylesheet" type="text/css" href="/<?php echo TMP_DIR;?>backend/product/product.css">
<script type="text/javascript" src="/<?php echo TMP_DIR;?>backend/libs/category_tree/script-for-get.js"></script>
<script type="text/javascript" src="/<?php echo TMP_DIR;?>backend/product/category_tree.js"></script>


<?php
$file = explode('/', __FILE__);
if(strpos($_SERVER['PHP_SELF'], $file[count($file)-1]) !== false){
	header("Content-Type: text/html; charset=UTF-8");
	die('Прямой запуск запрещен!');
}

$key = 'operation_id';
$table = 'operation_products';
$type_id = 3;

include "class/operation.class.php";
$Operation = new Operation();

//include "class/users.class.php";
$Users = new Users();
$users = $Users->getUsers();

include "class/shops.class.php";
$Shops = new Shops();
$shops = $Shops->getShops();

include "class/customer.class.php";
$Customer = new Customer();
$postav_list = $postavs = $Customer->getCustomers(4);

include "class/brand.class.php";
$Brand = new Brand();
$brand_list = $Brand->getBrands();

include "class/size.class.php";
$Size = new Size();
$size_group_list = $size_groups = $Size->getSizeGroups();

$tmp = $Size->getSizes();
$sizes_on_groups = array();
foreach($tmp as $row){
	$sizes_on_groups[$row['group_id']][]= $row;
}

	include "class/attributes.class.php";
	$Attributes = new Attributes();
	$attributes_group_list = $Attributes->getAttributeGroups();

include "class/warehouse.class.php";
$Warehouse = new Warehouse();
$warehouses_s = $Warehouse->getWarehouses();

foreach($warehouses_s as $row){
	$warehouses[$row['shop_id']][$row['warehouse_id']] = $row;
}

$types = $Operation->getTypes();

?>
<br>
<h1>Операция : <b>Движение между магазинами</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php if(!isset($_GET['operation_id'])){ ?>
	<a href="javascript:;" id="add_new_operation" class="add_new_operation">Создать новую операцию</a>
	<span class="msg_note">! Создайте операцию чтоб начать добавлять товары</span>
<?php }else{ ?>
	
	<a href="javascript:;" id="create_new_operation" class="add_new_operation">Сохранить и начать новую операцию</a>

<?php } ?>
</h1>

<style>
	h1{
		background-color: <?php echo $types[$type_id]['color']; ?>;
		padding: 10px;
		margin-bottom: 0px;
	}
	.product_image:hover{
		margin-left: -40px;
	}
	.size-box{
		float: left;
		padding: 3px;
		border: 1px solid gray;
		    margin-left: -1px;
			margin-top: -1px;
	}
	.size-box input{
		max-width: 30px;
		text-align: center;
		font-weight: bold;
		font-size: 13px;
		color: red;
		background-color: <?php echo $types[$type_id]['color']; ?>;
	}
	.size-box input:invalid {
		background-color: white;
	}
	.size-box span{
		font-weight: bold;
		font-size: 12px;
	}
	.size_wrapper{
		max-width: 400px;
		overflow: hidden;
	}
	.size_wrapper:hover{
		overflow: visible;
		background-color: #E5D0BC;
	}
	.add_new_operation{
		border: 1px solid gray;
		padding: 5px;
		background-color: green;
		color: white;
		text-decoration: none;
	}
</style>
<div style="width: 90%">
<div class="table_body">
	
<?php
//=====================================================================================================================
//=====================================================================================================================
//=====================================================================================================================
//=====================================================================================================================
	if(isset($_GET['operation_id'])){
		$operation_header = $Operation->getOperation($_GET['operation_id']);
		$operation_products = $Operation->getOperationProducts($_GET['operation_id']);
		//$operation_postav = $Operation->getOperationPostav($_GET['operation_id']);
		
		$operation_products_grp = array();
		
		if($operation_products){
			foreach($operation_products as $row){
				
				// Групируем товары по Ид и закупу. Цена закупа может разниться от размера
				$operation_products_grp[$row['product_id'].'_'.$row['operation_zakup']]['product'] = $row;
				$operation_products_grp[$row['product_id'].'_'.$row['operation_zakup']]['sizes'][$row['size_id']] = $row;
				
			}
		}
	
	}
	//echo '<pre>'; print_r(var_dump( $_SESSION  ));
	
	include 'product/category_tree.php';
?>

	<input type="hidden" id="type_id" value="<?php echo $type_id;?>">
	<input type="hidden" id="user_id" value="<?php echo $_SESSION['default']['user_id'];?>">
				
	<table class="text">
    <tr>
		<td class="right">
			<b>Номер операции</b>
		</td>
		<td class="left">
				   
			<input type="text"
				   class="header_edit"
				   id="operation_id"
				   style="width:300px;"
				   placeholder="Номер операции (автоматически)"
				   value="<?php echo isset($operation_header['operation_id']) ? $operation_header['operation_id'] : ''; ?>"
				   disabled
				   >
		</td>
		<td style="width: 30px;">&nbsp;</td>
		<td class="right">
			<b>Дата создания</b>
		</td>
		<td class="left">
			<input type="text"
				   class="header_edit"
				   id="date"
				   style="width:300px;"
				   placeholder="Дата создания (автоматически)"
				   value="<?php echo isset($operation_header['date']) ? $operation_header['date'] : ''; ?>"
				   disabled
				   >
		</td>
	</tr>
	
	<tr>
		<td class="right">
			<b>Пользователь</b>
		</td>
		<td class="left">
			<input type="text"
				   class="header_edit"
				   id="user_id"
				   style="width:300px;"
				   placeholder="Пользователь (автоматически)"
				   value="<?php echo isset($operation_header['operation_id']) ? $users[$operation_header['user_id']]['firstname'].' '.$users[$operation_header['user_id']]['lastname'] : ''; ?>"
				   disabled
				   >
		</td>
		<td style="width: 30px;">&nbsp;</td>
		<td class="right">
			<b>Дата изменения</b>
		</td>
		<td class="left">
			<input type="text"
				   class="header_edit"
				   id="edit_date"
				   style="width:300px;"
				   placeholder="Дата изменения (автоматически)"
				   value="<?php echo isset($operation_header['edit_date']) ? $operation_header['edit_date'] : ''; ?>"
				   disabled
				   >
		</td>
	</tr>
	
	<tr>
		<td class="right">
			<b>Сумма</b>
		</td>
		<td class="left">
			<input type="text"
				   class="header_edit"
				   id="summ"
				   style="width:300px;"
				   placeholder="Сумма (автоматически)"
				   value="<?php echo isset($operation_header['operation_id']) ? number_format($operation_header['summ'],2,'.','') : ''; ?>"
				   disabled
				   >
		</td>
		<td style="width: 30px;">&nbsp;</td>
		<td class="right">
			<b>Поставщик</b>
		</td>
		<td class="left">
			<select class="header_edit edit_postav" id="customer_id" style="width:300px;">
				<option value="0">* * *</option>
				<?php foreach($postavs as $index => $value){?>
					<?php if(isset($operation_header['customer_id']) AND $index == (int)$operation_header['customer_id']){ ?>
						<option value="<?php echo $index; ?>" selected><?php echo $value['name']; ?></option>
					<?php }else{ ?>
						<option value="<?php echo $index; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				<?php } ?>
			</select>
			<a href="/backend/index.php?route=postav/postav.index.php" target="_blank">
				<img src="/backend/img/jleditor_ico.png" title="редактировать" width="16" height="16">		
			</a>
			
		</td>
	</tr>
	<tr>
		<td class="right">
			<b>Куда (Склад)</b>
		</td>
		<td class="left">
			<input type="hidden"
				   class="header_edit"
				   id="from_warehouse_id"
				   value="0">
			<select class="header_edit" id="to_warehouse_id" style="width:300px;">
				<option value="0">* * *</option>
				<?php foreach($shops as $shop_id => $shop){?>
					<optgroup label="маг. <?php echo $shop['name']; ?>">
						<?php foreach($warehouses[$shop_id] as $index => $value){?>
							<?php if(isset($operation_header['to_warehouse_id']) AND $index == (int)$operation_header['to_warehouse_id']){ ?>
								<option value="<?php echo $index; ?>" selected><?php echo $value['name']; ?></option>
							<?php }else{ ?>
								<option value="<?php echo $index; ?>"><?php echo $value['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</optgroup>
				<?php } ?>
			</select>
			<a href="/backend/index.php?route=shops/shops.index.php" target="_blank">
				<img src="/backend/img/jleditor_ico.png" title="редактировать" width="16" height="16">		
			</a>
			
		</td>
		<td style="width: 30px;">&nbsp;</td>
		<td class="right">
			<b>Коментарий</b>
		</td>
		<td class="left">
			<input type="text"
				   class="header_edit"
				   id="comment"
				   style="width:100%;"
				   placeholder="Коментарий к операции"
				   value="<?php echo isset($operation_header['comment']) ? $operation_header['comment'] : ''; ?>"
				   >
		</td>
	</tr>
</table>
	

<?php
//=====================================================================================================================
//=====================================================================================================================
?>

<!--script type="text/javascript" src="/<?php echo TMP_DIR;?>backend/js/backend/ajax_edit_attributes.js"></script-->
<!-- Блок поиска -->
<?php //include 'operation/find_result.php'; ?>
<style>
    .find_result_style{
        display: none;
        position: absolute;
        margin-top: 80px;
        width: 98%;
		margin-left: 1%;
		margin-right: 1%;
        height: 600px;
        overflow-x: hidden;
        overflow-y: auto;
        text-align: center;
        background-color: #FFFFFF;
		border: 2px solid gray;
        z-index: 999;
		background-color: #ABBAA9;
    }
	.find_result_shirm_add, .find_result_shirm{
		z-index: 1000;
		background-color: black;
		opacity: 0.5;
		color: white;
		font-size: 24px;
	    padding-top: 145px;
		height: 100%;
	}
	.link:hover{
		cursor: pointer;
		background-color: #b7ddf2;
	}
	.add_new_product{
		background-color: #C4F2A9;
	}
    .find_result_back{width: 100%;height: 100%;opacity: 0.7;display: none;position: fixed;background-color: gray;top:0;left:0;z-index: 997;}
</style>
<div class="find_result_back"></div>
<div class="find_result find_result_style">
	<h2 style="margin: 4px;"><a href="javascript:;" class="find_result_close">[Закрыть]</a></h2>

	<div class="add_new_product">
		<table class="text">
		<tr>
			<th>#</th>
			<th>Статус</th>
			<th>Категория</th>
			<th>Индекс</th>
			<th>ШтрихКод</th>
			<th>Картинка</th>
			<th style="min-width: 150px;">Атрибуты</th>
			<th>Размеры</th>
			<th>Бренд
				<div class="attribute_list"></div>
			</th>
			<th>Закуп</th>
			<th>Розница</th>
			<th>Сорт</th>
			<th>*</th>
		</tr>
			<?php
				include 'product/sub.add.product.php';
			?>
		</table>
		
		<script>
			jQuery(document).on('click','.add_new_product .add', function(){
				
				$('.find_result_shirm_add').show();
				
				 var enable_tmp = 0;
				  
				 if (jQuery('#status').prop('checked')) {
					  enable_tmp = 1;
				 }
		 
				var post = 'key=add';
				post = post + '&id=0';
				post = post + '&mainkey=product_id';
				post = post + '&model='+jQuery('#model').val();
				post = post + '&code='+jQuery('#code').val();
				post = post + '&category_id='+jQuery('#category_id_new').val();
				post = post + '&sort_order='+jQuery('#sort_order').val();
				post = post + '&price='+jQuery('#price').val();
				post = post + '&price_invert='+jQuery('#price').val();
				post = post + '&zakup='+jQuery('#zakup').val();
				post = post + '&size_group_id='+jQuery('#size_group_id').val();
				post = post + '&manufacturer_id='+jQuery('#manufacturer_id').val();
				post = post + '&table=product';
				post = post + '&status='+enable_tmp;
				
				
				jQuery.ajax({
					type: "POST",
					url: "/backend/ajax/ajax_edit_product.php",
					dataType: "text",
					data: post,
					beforeSend: function(){
					},
					success: function(product_id){
						 
						jQuery.each($('#product_attribute_wrappernew div a'), function(index, value){
							saveAttribute(product_id, 0, $(value).data('attribute_id'), '');
						});
						
						//Добавляем продукт
						var operation_id = $('#operation_id').val();
						
						if(operation_id > 0){
							var post = 'key=add_new_product';
							post = post + '&operation_id='+operation_id;
							post = post + '&product_id='+product_id;
							
							post = post + '&zakup='+jQuery('#zakup').val();
							post = post + '&price_invert='+jQuery('#price').val();;
							
							$.each($('.add_size_wrapper_new input'), function(value, index){
								
								post_1 = post;
								post_1 = post_1 + '&size_id='+$(index).data('size_id');
								post_1 = post_1 + '&quantity='+$(index).val();
								
								console.log(post_1);
								
								if($(index).val() > 0){	
									jQuery.ajax({
										type: "POST",
										url: "/backend/operation/ajax_edit_operation.php",
										dataType: "text",
										data: post_1,
										beforeSend: function(){
										},
										success: function(msg){
											
											//console.log( msg );
											
											//$('.find_result_shirm_add').hide();	
											
										}
									});
								}
									
							});
							
							/*
							jQuery.ajax({
								type: "POST",
								url: "/backend/operation_prihod/ajax_edit_operation.php",
								dataType: "text",
								data: post,
								beforeSend: function(){
								},
								success: function(msg){
									
									console.log( msg );
									
									
									
									
									$('.find_result_shirm_add').hide();	 
								}
							});
							*/
							jQuery('#model').val('');
							jQuery('#code').val('');
							$('.find_result_shirm_add').hide();	
						}
						
					}
					
				});
				 
			 });
		</script>
		
	</div>
	<div class="find_result_wrapper"></div>
</div>
<div class="find_result_style find_result_shirm">Поиск вариантов . . .</div>
<div class="find_result_style find_result_shirm_add">Создаю товар и добавляю в операцию . . .</div>

<script>
	jQuery(document).on('keyup','#find_model', function(){
		
		find_product();
	});
	
	jQuery(document).on('keyup','#find_code', function(){
    
		find_product();
	});
	
	jQuery(document).on('focus','#find_model', function(){
		
		find_product();
	});
	
	jQuery(document).on('focus','#find_code', function(){
    
		find_product();
	});
	
	function find_product(){
		$('.find_result_shirm').show();
		
		var post = 'key=find_product';
		post = post + '&model='+$('#find_model').val();
		post = post + '&code='+$('#find_code').val();
		post = post + '&manufacturer_id='+$('#find_manufacturer_id').val();
		post = post + '&shop_id='+$('#find_shop_id').val();
		post = post + '&warehouse_id='+$('#find_warehouse_id').val();
		post = post + '&operation_id='+$('#find_operation_id').val();
		
		if($('#find_model').val() == '' && $('#find_code').val() == ''){
			$('.find_result_shirm').hide();
			$('.find_result').show();
		}else{
			jQuery.ajax({
				type: "GET",
				url: "/backend/operation/ajax_edit_operation.php",
				dataType: "text",
				data: post,
				beforeSend: function(){
				},
				success: function(msg){
					
					//console.log( msg );
					$('.find_result_shirm').hide();
					$('.find_result_wrapper').html(msg);
					$('.find_result').show();
					//$('.find_result_back').show();
				}
			});
		}
		
		$('#size_group_id').trigger('change');
		
    }

	jQuery(document).on('change','#size_group_id', function(){
		
		var post = 'key=get_sizes';
		post = post + '&size_group_id='+$(this).val();
		
		jQuery.ajax({
				type: "GET",
				url: "/backend/operation/ajax_edit_operation.php",
				dataType: "text",
				data: post,
				beforeSend: function(){
				},
				success: function(msg){
					//console.log(msg);
					
					$('.add_size_wrapper_new').html(msg);
					
				}
			});
	
	
	});
	
	jQuery(document).on('click','.find_result_close', function(){
		$('.find_result').hide();
		$('.find_result_back').hide();
		location.reload();
	});
	
	jQuery(document).on('click','#create_new_operation', function(){
	
		location.href = '/backend/index.php?route=<?php echo $_GET['route']; ?>';
   
	});
	
	jQuery(document).on('click','#add_new_operation', function(){
        //debugger;
		
			var post = 'key=add_new_operation';
			post = post + '&type_id='+$('#type_id').val();
			post = post + '&user_id='+$('#user_id').val();
			post = post + '&to_warehouse_id='+$('#to_warehouse_id').val();
			post = post + '&from_warehouse_id='+$('#from_warehouse_id').val();
			post = post + '&customer_id='+$('#customer_id').val();
			post = post + '&comment='+$('#comment').val();
			post = post + '&user_id='+$('#user_id').val();
			
			jQuery.ajax({
				type: "POST",
				url: "/backend/operation/ajax_edit_operation.php",
				dataType: "text",
				data: post,
				beforeSend: function(){
				},
				success: function(msg){
					
					console.log(msg);
					
					location.href = "/backend/index.php?route=operation_prihod/prihod.index.php&operation_id="+msg;
				}
			});
		
    });
	
	jQuery(document).on('click','.add_product_to_operation', function(){
        	
		var operation_id = $('#operation_id').val();
		var product_id = $(this).parent('tr').attr('id');
		
		if(operation_id > 0){
			var post = 'key=add_new_product';
			post = post + '&operation_id='+operation_id;
			post = post + '&product_id='+product_id;
			post = post + '&zakup='+$('#add_zakup'+product_id).val();
			post = post + '&price_invert='+$('#add_price_invert'+product_id).val();
			
			
			
			$.each($('.add_size_wrapper'+product_id+' input'), function(value, index){
				
				post_1 = post;
				post_1 = post_1 + '&size_id='+$(index).data('size_id');
				post_1 = post_1 + '&quantity='+$(index).val();
				
				console.log(post_1);
				
				if($(index).val() > 0){	
					jQuery.ajax({
						type: "POST",
						url: "/backend/operation/ajax_edit_operation.php",
						dataType: "text",
						data: post_1,
						beforeSend: function(){
						},
						success: function(msg){
							
							console.log( msg );
							
						}
					});
				}
					
			});
			
			$('.res'+product_id).hide();
		}
    });
 
</script>


<!-- Конец Блок поиска -->
<?php if(isset($_GET['operation_id'])){ ?> 

<table class="text" style="margin-top: 15px;">
    <tr>
        <th>id</th>
        <th>Индекс</th>
		<th>ШтрихКод</th>
		<th>Фото</th>
        <th style="max-width: 50%;">К-во</th>
        <th>Закуп</th>
		<th>Сумма</th>
		<th>Розница</th>
        <th>&nbsp;</th>
    </tr>

    <tr style="background-color: <?php echo $types[$type_id]['color']; ?>;">
        <td class="mixed">новый</td>
        <td class="mixed"><input type="text" id="find_model" style="width:150px;" value="" placeholder="Индекс"></td>
        <td class="mixed"><input type="text" id="find_code" style="width:150px;" value="" placeholder="Код"></td>
        <td class="mixed" colspan="5">
			<b>Расширенный фильтр : </b>
			
			<input type="hidden" id="category_id_find" style="width:10px;" value="">
			<a href="javascript:;" class="category_tree select_category" data-id="category_id_find">Категория [дерево]</a> (<span class="selected_category" id="name_category_id_find">Все...</span>)
			<input type="hidden" name="category"  id="category_id_find" class="selected_category_id" value="0">
				
			
			<!--input type="text" id="find_manufacturer_id" style="width:50px;" value=""-->
			<select class="header_edit" id="find_manufacturer_id" style="width:100px;">
				<option value="">Фирма</option>
				<?php foreach($brand_list as $index => $value){?>
					<option value="<?php echo $index; ?>"><?php echo $value['name']; ?></option>
				<?php } ?>
			</select>

			<!--input type="text" id="find_shop_id" style="width:50px;" value=""-->
			<select class="header_edit" id="find_shop_id" style="width:100px;">
				<option value="">Магазин</option>
				<?php foreach($shops as $index => $value){?>
					<?php if(isset($_SESSION['find_shop_id']) AND $index == (int)$_SESSION['find_shop_id']){ ?>
						<option value="<?php echo $index; ?>" selected><?php echo $value['name']; ?></option>
					<?php }else{ ?>
						<option value="<?php echo $index; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				<?php } ?>
			</select>

			
			<!--input type="text" id="find_warehouse_id" style="width:50px;" value=""-->
			<select class="header_edit" id="find_warehouse_id" style="width:100px;">
				<option value="">Склад</option>
				<?php foreach($shops as $shop_id => $shop){?>
					<optgroup label="маг. <?php echo $shop['name']; ?>">
						<?php foreach($warehouses[$shop_id] as $index => $value){?>
							<?php if(isset($_SESSION['find_warehouse_id']) AND $index == (int)$_SESSION['find_warehouse_id']){ ?>
								<option value="<?php echo $index; ?>" selected><?php echo $value['name']; ?></option>
							<?php }else{ ?>
								<option value="<?php echo $index; ?>"><?php echo $value['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</optgroup>
				<?php } ?>
			</select>
		
			
		</td>
	</tr>
    <td>
        <td colspan="8">&nbsp;</td>
    </td>

<?php if(isset($operation_products_grp) AND count($operation_products_grp)>0){ ?>
<?php foreach($operation_products_grp as $index => $ex){ ?>
    <tr id="<?php echo $index;?>" style="height: 65px;">
        <td class="mixed"><?php echo $index;
		
			//echo '<pre>'; print_r(var_dump( $ex['product']  ));		
		
		
		?></td>
        <td class="left"><!--input type="text" class="edit" id="model<?php echo $index;?>" style="width:150px;" value="<?php echo $ex['product']['model']; ?>"-->
			
			<a href="/backend/index.php?route=product/product.index.php&product_id=<?php echo  $ex['product']['product_id'];?>" target='_blank'>
                <img src="/backend/img/jleditor_ico.png" title="редактировать" width="16" height="16">
            </a>
			<?php echo $ex['product']['model']; ?></td>
        <td class="left"><!--input type="text" class="edit" id="code<?php echo $index;?>" style="width:150px;" value="<?php echo $ex['product']['code']; ?>"-->
			<?php echo $ex['product']['code']; ?>
		</td>
        <td class="mixed" style="width: 80px;">
			<img class="product_image" src="/image/<?php echo $ex['product']['image']; ?>">
		</td>
		<td class="left size_wrapper">
				<?php $size_group_id = $ex['product']['size_group_id'] ; ?>
				<?php $row_summ = 0;?>
				
				<?php if($size_group_id > 0){ ?>
					<b><?php echo $size_groups[$size_group_id]['name']; ?></b><br>
					
					<?php foreach($sizes_on_groups[$size_group_id] as $size_id => $value){?>
				   
				   <?php //echo '<pre>'; print_r(var_dump( $ex  )); ?>
				   <?php //echo '<pre>'; print_r(var_dump( $value  )); ?>
						
						<?php if(isset($ex['sizes'][$value['size_id']])){
							$quantity = (int)$ex['sizes'][$value['size_id']]['operation_quantity'];
						}else{
							$quantity = 0;
						}
						?>
						
						<div class="size-box">
							<span><?php echo $value['name'];?></span>
							<input type="text"
								   required
								   class="size"
								   id="size*<?php echo $ex['product']['product_id'].'*'.$value['size_id']; ?>"
								   data-row_id="<?php echo $index;?>"
								   data-size_id="<?php echo $value['size_id']; ?>"
								   data_product_id="<?php echo $ex['product']['product_id']; ?>"
								   
								   value="<?php echo $quantity;?>">
						</div>
					
						<?php $row_summ += (int)$quantity;?>
					
					<?php } ?>
                <?php }else{ ?>
				
					<?php  $size = array_shift($ex['sizes']);
						$zakup = $size['zakup'];
					?>
					<div class="size-box">
							<span>Без размера</span>
							<input type="text"
								   required
								   class="size"
								   id="size*<?php echo $ex['product']['product_id'].'*0'; ?>"
								   data-row_id="<?php echo $index;?>"
								   data-size_id="0"
								   data_product_id="<?php echo $ex['product']['product_id']; ?>"
								   value="<?php echo $size['operation_quantity'];?>">
						</div>
					<?php $row_summ += (int)$size['operation_quantity'];?>
				<?php } ?>
        </td>
		<td class="right"><input type="text" class="edit zakup right" id="zakup<?php echo $index;?>" style="width:70px;" value="<?php echo number_format($ex['product']['operation_zakup'],2,'.',''); ?>"></td>
        <td class="right" id="summ_<?php echo $index;?>"><?php echo number_format(($row_summ * $ex['product']['operation_zakup']),2,'.','');?></td>
        <td class="right"><input type="text" class="edit price_invert right" id="price_invert<?php echo $index;?>" style="width:70px;" value="<?php echo number_format($ex['product']['price_invert'],2,'.',''); ?>"></td>
        
		<td>        
            <a href="javascript:;" class="dell" data-id="<?php echo $index;?>">
                <img src="/<?php echo TMP_DIR; ?>backend/img/cancel.png" title="удалить" width="16" height="16">
            </a>
           </td>              
    </tr>
<?php } ?>
<?php } ?>
</table>
<?php } ?>

<input type="hidden" id="table" value="<?php echo $table; ?>">

</div>

</div>


<script>
	 //======================================================================   
    
    jQuery(document).on('click','.dell', function(){
        
		var operation_id = $('#operation_id').val();
		var row_id = $(this).data('id');
		var res = row_id.split('_');
		var product_id = res[0];
		var zakup = res[1];
		
		if (confirm('Вы действительно желаете удалить эту строку?')){
			if(operation_id > 0){
				var post = 'key=dell_row';
				post = post + '&operation_id='+operation_id;
				post = post + '&product_id='+product_id;
				post = post + '&zakup='+zakup;
				
				jQuery.ajax({
					type: "POST",
					url: "/backend/operation/ajax_edit_operation.php",
					dataType: "text",
					data: post,
					beforeSend: function(){
					},
					success: function(msg){
						
						$('#summ').val(msg+'.00');
						
						console.log( msg );
						
						jQuery('#'+row_id).hide();
					}
				});
			}
		}
    });   
    jQuery(document).on('change','.size', function(){
        
		var operation_id = $('#operation_id').val();
		var row_id = $(this).data('row_id');
		var res = row_id.split('_');
		var product_id = res[0];
		var zakup = res[1];
		var size_id = $(this).data('size_id');
		var quantity = $(this).val();
		
		if(operation_id > 0){
			var post = 'key=edit_quantity';
			post = post + '&operation_id='+operation_id;
			post = post + '&product_id='+product_id;
			post = post + '&zakup='+zakup;
			post = post + '&size_id='+size_id;
			post = post + '&quantity='+quantity;
			
			jQuery.ajax({
				type: "POST",
				url: "/backend/operation/ajax_edit_operation.php",
				dataType: "text",
				data: post,
				beforeSend: function(){
				},
				success: function(msg){
					
					$('#summ').val(msg+'.00');
					
					console.log( msg );
				}
			});
		}
    });
 
	  jQuery(document).on('change','.zakup', function(){
        
		var operation_id = $('#operation_id').val();
		var row_id = $(this).parent('td').parent('tr').attr('id');
		var res = row_id.split('_');
		var product_id = res[0];
		var zakup = res[1];
		var new_zakup = $(this).val();
		
		tmp = new_zakup.split('.');
		new_zakup = tmp[0];
		
		
		if(operation_id > 0){
			var post = 'key=edit_zakup_prihod';
			post = post + '&operation_id='+operation_id;
			post = post + '&product_id='+product_id;
			post = post + '&zakup='+zakup;
			post = post + '&new_zakup='+new_zakup;
			
			jQuery.ajax({
				type: "POST",
				url: "/backend/operation/ajax_edit_operation.php",
				dataType: "text",
				data: post,
				beforeSend: function(){
				},
				success: function(msg){
					$('#summ').val(msg+'.00');
					
					$('#'+row_id).children('td').first().html(product_id+'_'+new_zakup);
					$('#'+row_id).attr('id', product_id+'_'+new_zakup);
					
					console.log( msg );
				}
			});
		}
    });
 
	  jQuery(document).on('change','.price_invert', function(){
        
		var operation_id = $('#operation_id').val();
		var row_id = $(this).parent('td').parent('tr').attr('id');
		var res = row_id.split('_');
		var product_id = res[0];
		var zakup = res[1];
		var price_invert = $(this).val();
		
		if(operation_id > 0){
			var post = 'key=edit_price_invert';
			post = post + '&operation_id='+operation_id;
			post = post + '&product_id='+product_id;
			post = post + '&zakup='+zakup;
			post = post + '&price_invert='+price_invert;
			
			jQuery.ajax({
				type: "POST",
				url: "/backend/operation/ajax_edit_operation.php",
				dataType: "text",
				data: post,
				beforeSend: function(){
				},
				success: function(msg){
					
					console.log( msg );
				}
			});
		}
    });
 
	jQuery(document).on('change','.header_edit', function(){
        
		var operation_id = $('#operation_id').val();
		
		if(operation_id > 0){
			var post = 'key=edit';
			post = post + '&id='+operation_id;
			post = post + '&mainkey=operation_id';
			post = post + '&'+jQuery(this).attr('id')+'='+jQuery(this).val();
			post = post + '&table=operation';
			
			
			jQuery.ajax({
				type: "POST",
				url: "/backend/ajax/ajax_edit_universal.php",
				dataType: "text",
				data: post,
				beforeSend: function(){
				},
				success: function(msg){
					console.log( msg );
				}
			});
		}
    });
 
 
 //============================================================
 /*
 
 
    jQuery(document).on('click','.add', function(){
		
		//console.log('11 '+name);   
        var id = 0;
        var name = jQuery('#name').val();
        var country_id = jQuery('#country_id').val();
        var enable_tmp = 0;
        var sort = jQuery('#sort_order').val();
        var description = jQuery('#description').val();
        var table = jQuery('#table').val();
        
        if (jQuery('#enable').prop('checked')) {
             enable_tmp = 1;
        }
     
        if (name != "") {
            jQuery.ajax({
                type: "POST",
                url: "/<?php echo TMP_DIR; ?>backend/ajax/ajax_guideuniversal.php",
                dataType: "text",
                data: "id="+id+"&description="+description+"&country_id="+country_id+"&name="+name+"&enable="+enable_tmp+"&sort_order="+sort+"&key=add_manufacturer",
                beforeSend: function(){
                },
                success: function(msg){
                    console.log( msg );
                    location.reload();
                }
            });
        }
        
    });
    
    jQuery(document).on('click','.dell', function(){
        var id = jQuery(this).data('id');
        var table = jQuery('#table').val();
        
        if (confirm('Вы действительно желаете удалить бренд?')){
            jQuery.ajax({
                type: "POST",
                url: "/<?php echo TMP_DIR; ?>backend/ajax/ajax_guideuniversal.php",
                dataType: "text",
                data: "id="+id+"&key=dell_manufacturer",
                beforeSend: function(){
                },
                success: function(msg){
                    console.log( msg );
                    jQuery('#'+id).hide();
                }
            });
        }
    });
    */
    //======================================================================
	
</script>
