<?php

include('../../config.php');
include('../config.php');

include "../class/operation.class.php";
$Operation = new Operation();
	
include "../class/product.class.php";
$Product = new Product();
	
//include "../class/attributes.class.php";
//$Attributes = new Attributes();

include "../class/category.class.php";
$Category = new Category();

include "../class/size.class.php";
$Size = new Size();
$size_group_list = $size_groups = $Size->getSizeGroups();

$tmp = $Size->getSizes();
$sizes_on_groups = array();
foreach($tmp as $row){
	$sizes_on_groups[$row['group_id']][]= $row;
}
	
	$key = 'exit';
    $table = '';
    $id = '';
	$mainkey = 'id';
	$radio_name = '';
    $data = array();
	$find = array('*1*', '@*@');
	$replace = array('=', '&');
    
foreach($_POST as $index => $value){
    
    //echo '++++    '.$index.'='.$value;
 
	
    if($index == 'key'){
        $key = $value;
    }elseif($index == 'table'){
        $table = $value;
    }elseif($index == 'id'){
        $id = str_replace($find,$replace,$value);
    }elseif($index == 'language_id'){
        $language_id = $value;
    }elseif($index == 'mainkey'){
        $mainkey = $value;
    }elseif($index == 'radio_name'){
        $radio_name = $value;
    }else{
        $data[$index] = str_replace($find,$replace,$value);
    }
}

if(isset($_GET['key']) AND $_GET['key'] == 'find_product'){
	
    $filters = array();
	
	if(isset($_GET['model']) AND $_GET['model'] != ''){
		$filters['filter_model'] = $_GET['model'];
	}
	
	if(isset($_GET['code']) AND $_GET['code'] != ''){
		$filters['filter_code'] = $_GET['code'];
	}
	
	if(isset($_GET['shop_id']) AND $_GET['shop_id'] > 0){
		$filters['filter_shop'] = $_GET['shop_id'];
	}
	
	if(isset($_GET['category_id']) AND $_GET['category_id'] > 0){
		$filters['filter_category'] = $_GET['category_id'];
	}
		
	if(isset($_GET['manufacturer_id']) AND $_GET['manufacturer_id'] > 0){
		$filters['filter_manufacturer'] = $_GET['manufacturer_id'];
	}
	
	if(isset($_GET['warehouse_id']) AND $_GET['warehouse_id'] > 0){
		$filters['filter_warehouse'] = $_GET['warehouse_id'];
	}
	
	$filters['start'] = 0;
	$filters['limit'] = 20;
	
	$products = $Product->getProducts($filters); ?>
	<h2 style="margin-top: 10px;">Результат поиска &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="javascript:;" class="find_result_close">[Закрыть]</a></h2>
		<!--input type="hidden" id="operation_id"  value="<?php echo $_GET['operation_id'];?>"-->
	<div style="width: 100%;margin-left: 0px;">
		<div class="table_body">
		<table class="text" style="width: 94%;margin-left: 3%;margin-right: 3%;">
			<tr>
				<th>#</th>
				<th>Категория</th>
				<th>Индекс</th>
				<th>ШтрихКод</th>
				<th>Фото</th>
				<th colspan="2">Количество</th>
				<th>Закуп</th>
				<th>Цена</th>
				<th>* * *</th>
			</tr>
			
			<?php foreach($products as $index => $ex){ ?>
			
			
			<tr class="link  res<?php echo $ex['product_id']; ?>"
						id="<?php echo $ex['product_id']; ?>"
						style="height: 65px;">
				<td><?php echo $ex['product_id']; ?></td>
				<?php $category_info = $Category->getCategory($ex['category_id']);?>
				<td><?php echo $category_info['path']; ?></td>
				<td class="mixed"><?php echo $ex['model']; ?></td>
				<td class="mixed"><?php echo $ex['code']; ?></td>
				<td><img class="product_image" src="<?php echo '/image/'.$ex['image'];?>"></td>
				
				<td class="left add_size_wrapper<?php echo $ex['product_id']; ?>">
					<?php
					/*
						header("Content-Type: text/html; charset=UTF-8");
						echo '<pre>'; print_r(var_dump( $ex  ));
						die();
						*/
					
					?>
					
					<?php $quantity = 0; ?>
					<?php $size_group_id = $ex['size_group_id'] ; ?>
						
					<?php if($size_group_id > 0){ ?>
						<b><?php echo $size_groups[$size_group_id]['name']; ?></b><br>
						
						<?php foreach($sizes_on_groups[$size_group_id] as $size_id => $value){?>
					   
							
							
							<div class="size-box">
								<span><?php echo $value['name'];?></span>
								<input type="text"
									   required
									   class="add_size"
									   id="add_size*<?php echo $ex['product_id'].'*'.$value['size_id']; ?>"
									   data-row_id="<?php echo $index;?>"
									   data-size_id="<?php echo $value['size_id']; ?>"
									   data_product_id="<?php echo $ex['product_id']; ?>"
									   
									   value="<?php echo $quantity;?>">
							</div>
						
						<?php } ?>
					<?php }else{ ?>
					
						<?php  //$size = array_shift($ex['sizes']);
							//$zakup = $size['zakup'];
						?>
						<div class="size-box">
								<span>Без размера</span>
								<input type="text"
									   required
									   class="add_size"
									   id="add_size*<?php echo $ex['product_id'].'*0'; ?>"
									   data-row_id="<?php echo $index;?>"
									   data-size_id="0"
									   data_product_id="<?php echo $ex['product_id']; ?>"
									   value="<?php echo $quantity;?>">
							</div>
					<?php } ?>
				</td>
				<!--td class="mixed"><?php echo number_format($ex['last_zakup'], 2, '.', ''); ?></td>
				<td class="mixed"><?php echo number_format($ex['last_price_invert'], 2, '.', ''); ?></td-->
				
				<td class="total_quantity" id="total_quantity<?php echo $ex['product_id']; ?>"></td>
				
				<td class="right"><input type="text" class="edit add_zakup right" id="add_zakup<?php echo $ex['product_id'];?>" style="width:70px;" value="<?php echo number_format($ex['last_zakup'],2,'.',''); ?>"></td>
				<td class="right"><input type="text" class="edit add_price_invert right" id="add_price_invert<?php echo $ex['product_id'];?>" style="width:70px;" value="<?php echo number_format($ex['last_price_invert'],2,'.',''); ?>"></td>
     		
				<td class="add_product_to_operation add_td">+</td>
			</tr>
			<?php } ?>
			
		</table>
		</div>
	</div>
	<style>
		.add_td{
			background-color: #BBF78A;
			color: black;
			font-weight: bold;
			font-size: 32px;
			cursor: pointer;
			text-align: center;
		}
		
	</style>
		
	
<?php
}elseif($key == 'get_sizes' OR (isset($_GET['key']) AND $_GET['key'] == 'get_sizes')){

		if(!isset($_GET['product_id'])) $_GET['product_id'] = 0;
		if(!isset($ex['product_id'])) $ex['product_id'] = 0;
		
		$size_group_id = (int)$_GET['size_group_id'] ;
		
		$index = 0;
		$quantity = 0
		?>
						
					<?php if($size_group_id > 0){ ?>
						<b><?php echo $size_groups[$size_group_id]['name']; ?></b><br>
						
						<?php foreach($sizes_on_groups[(int)$size_group_id] as $size_id => $value){?>
					   
							
							
							<div class="size-box">
								<span><?php echo $value['name'];?></span>
								<input type="text"
									   required
									   class="add_size"
									   id="add_size*<?php echo $ex['product_id'].'*'.$value['size_id']; ?>"
									   data-row_id="<?php echo $index;?>"
									   data-size_id="<?php echo $value['size_id']; ?>"
									   data_product_id="<?php echo $ex['product_id']; ?>"
									   
									   value="<?php echo $quantity;?>">
							</div>
						
						<?php } ?>
					<?php }else{ ?>
					
						<?php  //$size = array_shift($ex['sizes']);
							//$zakup = $size['zakup'];
						?>
						<div class="size-box">
								<span>Без размера</span>
								<input type="text"
									   required
									   class="add_size"
									   id="add_size*<?php echo $ex['product_id'].'*0'; ?>"
									   data-row_id="<?php echo $index;?>"
									   data-size_id="0"
									   data_product_id="<?php echo $ex['product_id']; ?>"
									   value="<?php echo $quantity;?>">
							</div>
					<?php } 
	
}elseif($key == 'add_new_product'){

	
	$data['operation_id'] = $data['operation_id'];
	$data['product_id'] = $data['product_id'];
	$data['size_id'] = $data['size_id'];
	$data['quantity'] = $data['quantity'];
	$data['currency_id'] = $data['currency_id'];
	$data['master_id'] = $data['master_id'];
	$data['zakup'] = $data['zakup'];
	$data['price_invert'] = $data['price_invert'];
	
	echo $Operation->addProduct($data);

}elseif($key == 'add_new_operation'){
	
	echo $Operation->addOperation($data);

	
}elseif($key == 'edit_zakup_prihod'){
    
	
	$sql = 'UPDATE ' . DB_PREFIX . 'operation_product
				SET
				zakup = "'.$data['new_zakup'].'"
				WHERE
				operation_id = "'.$data['operation_id'].'" AND
				product_id = "'.$data['product_id'].'" AND
				zakup = "'.$data['zakup'].'" 
				';
	//echo $sql;
	$mysqli->query($sql) or die('sa1423'.$sql);
	
	echo $Operation->updateOperationSumm($data['operation_id']);

	
}elseif($key == 'get_product'){

	$product = $Product->getProduct((int)$data['product_id']);
	
	$product['category'] = $Category->getCategory($product['category_id']);
	
	$product['attributes'] = $Product->getAttributes((int)$data['product_id']);
		
	echo json_encode($product);


}elseif($key == 'edit_price_invert'){
    
	
	$sql = 'UPDATE ' . DB_PREFIX . 'operation_product
				SET
				price_invert = "'.$data['price_invert'].'"
				WHERE
				operation_id = "'.$data['operation_id'].'" AND
				product_id = "'.$data['product_id'].'" AND
				zakup = "'.$data['zakup'].'" 
				';
	//echo $sql;
	$mysqli->query($sql) or die('sa1423'.$sql);
	
	//Он не влияет на сумму операции
	//echo $Operation->updateOperationSumm($data['operation_id']);

	
}elseif($key == 'dell_row'){
    
	/*
	$data['operation_id']
	$data['product_id']
	$data['zakup']
	*/		
	$Operation->dellProductRow($data);
	
	echo $Operation->updateOperationSumm($data['operation_id']);

	
}elseif($key == 'edit_quantity'){
	
	/* $data['operation_id']
	 * $data['product_id']
	 * $data['zakup']
	 * $data['quantity']
	 * $data['size_id']
	 */
	$Operation->updateProductQuantity($data);
	
	echo $Operation->updateOperationSumm($data['operation_id']);
}

?>