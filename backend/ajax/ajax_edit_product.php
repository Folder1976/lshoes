<?php

include('../../config.php');
include('../config.php');
include '../class/product.class.php';
$Product = new Product($mysqli, DB_PREFIX);

include "../class/attributes.class.php";
$Attributes = new Attributes();	
	
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



if($key == 'add' and (int)$id > 0){
	
	$sql = "UPDATE " . DB_PREFIX . $table . " SET ";
		$sql .= "`model` = '".$data['model']."',
				`code` = '".$data['code']."',
				`price` = '".$data['price']."',
				`zakup` = '".$data['zakup']."',
				`size_group_id` = '".$data['size_group_id']."',
				`sort_order` = '".$data['sort_order']."',
				`manufacturer_id` = '".$data['manufacturer_id']."',
				`status` = '".$data['status']."'";
	$sql .=	" WHERE `$mainkey` = '" . (int)$id . "'";
//echo $sql;
	$mysqli->query($sql) or die('sadlkjgfljsad bf;j '.$sql);

	$sql = "DELETE FROM " . DB_PREFIX . "product_to_category WHERE `$mainkey` = '" . (int)$id . "'";
	$mysqli->query($sql) or die('sadlkjgfljsad bf;j '.$sql);

	$sql = "DELETE FROM " . DB_PREFIX . "product_attribute WHERE `$mainkey` = '" . (int)$id . "'";
	$mysqli->query($sql) or die('sadlkjgfljsad bf;j '.$sql);

	
	$sql = "INSERT INTO " . DB_PREFIX . "product_to_category SET product_id='".(int)$id."', category_id='".$data['category_id']."'";
	$mysqli->query($sql) or die('sadsdfgad bf;j '.$sql);
	
	echo $id;

}elseif($key == 'add'){
    
	$sql = "INSERT INTO " . DB_PREFIX . $table . " SET ";
	$sql .= "`model` = '".$data['model']."',
				`code` = '".$data['code']."',
				`price` = '".$data['price']."',
				`sort_order` = '".$data['sort_order']."',
				`zakup` = '".$data['zakup']."',
				`size_group_id` = '".$data['size_group_id']."',
				`manufacturer_id` = '".$data['manufacturer_id']."',
				`status` = '".$data['status']."'";
//echo $sql;
	$mysqli->query($sql) or die('sawreq444j '.$sql);
	
	$product_id = $mysqli->insert_id;
	
	$sql = "INSERT INTO " . DB_PREFIX . "product_description SET product_id='$product_id', language_id='1'";
	$mysqli->query($sql) or die('sad54yfdfsg '.$sql);
	
	$sql = "INSERT INTO " . DB_PREFIX . "product_to_category SET product_id='$product_id', category_id='".$data['category_id']."'";
	$mysqli->query($sql) or die('sadsdfgad bf;j '.$sql);
	
	$sql = "INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id='$product_id', store_id='0', layout_id='0'";
	$mysqli->query($sql) or die('sad54dsfd bf;j '.$sql);
	
	$sql = "INSERT INTO " . DB_PREFIX . "product_to_store SET product_id='$product_id', store_id='0'";
	$mysqli->query($sql) or die('sad5rbf;j '.$sql);
	
	echo $product_id;
	
}elseif($key == 'edit'){
    
	$sql = "UPDATE " . DB_PREFIX . $table . " SET ";
		$sql .= "`model` = '".$data['model']."',
				`code` = '".$data['code']."',
				`price` = '".$data['price']."',
				`zakup` = '".$data['zakup']."',
				`sort_order` = '".$data['sort_order']."',
				`size_group_id` = '".$data['size_group_id']."',
				`manufacturer_id` = '".$data['manufacturer_id']."',
				`status` = '".$data['status']."'";
	$sql .=	" WHERE `$mainkey` = '" . (int)$id . "'";
//echo $sql;
	$mysqli->query($sql) or die('sadlkjgfljsad bf;j '.$sql);

	$sql = "DELETE FROM " . DB_PREFIX . "product_to_category WHERE `$mainkey` = '" . (int)$id . "'";
	$mysqli->query($sql) or die('sadlkjgfljsad bf;j '.$sql);
	
	$sql = "INSERT INTO " . DB_PREFIX . "product_to_category SET ";
	$sql .= "`category_id` = '".$data['category_id']."', `$mainkey` = '" . (int)$id . "'";
echo $sql;
	$mysqli->query($sql) or die('sadlkjgfljsad bf;j '.$sql);
		
}elseif($key == 'dell' AND isset($id) AND is_numeric($id)){
	
	$Product->dellProduct((int)$id);
		//$Product->dellImages();
	
}elseif($key == 'dell_filters' AND isset($id) AND is_numeric($id)){

	$sql = "DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '".(int)$id."' ";
	
	$mysqli->query($sql) or die('sad54yfljsosdfhg;adbf;j '.$sql);

}elseif($key == 'dell_attribute'){

	$sql = "DELETE FROM " . DB_PREFIX . "product_attribute WHERE
				product_id = '".(int)$data['product_id']."'
				AND attribute_id = '".(int)$data['attribute_id']."' ";
	
	$mysqli->query($sql) or die('sad54yfljsosdfhg;adbf;j '.$sql);

}elseif($key == 'add_attribute_to_tovar'){

	$data['name'] = trim($data['name']);

	//Определим по названию	
	if($data['attribute_id'] == 0){
		
		$sql = 'SELECT * FROM `'.DB_PREFIX.'attribute_group` AG
					LEFT JOIN `'.DB_PREFIX.'attribute_group_description` AGD ON AG.attribute_group_id = AGD.attribute_group_id
					WHERE `enable` = "1" AND `name` LIKE "'.$data['name'].'"';
		
		if($data['attribute_group_id'] > 0){
			$sql .= ' AND AG.attribute_group_id = "'.$data['attribute_group_id'].'"';
		}
		
		$sql .=  ' LIMIT 1;';
		
		$r = $mysqli->query($sql) or die($sql);
		
		$return = array();
		if($r->num_rows > 0){
			
			$row = $r->fetch_assoc();
			$data['attribute_id'] = (int)$row['attribute_id'];
			
		}elseif($data['attribute_group_id'] > 0){
			
			$data['language_id'] = 1;
			$data['filter_name'] = '';
			$data['sort_order'] = 0;
			$data['enable'] = 1;
			$data['attribute_type'] = 1;
			
			$data['attribute_id'] = $Attributes->addAttribute($data);
			
		}
		
		
	}
	
	if((int)$data['attribute_id'] > 0){
		
		if(is_numeric($data['product_id']) AND (int)$data['product_id'] > 0){
	
			$sql = 'INSERT INTO ' . DB_PREFIX . 'product_attribute SET
						product_id="'.$data['product_id'].'",
						attribute_id="'.$data['attribute_id'].'",
						language_id = "1",
						`text`="",
						`value`="",
						attribute_value_id=0
						';
						
			$mysqli->query($sql) or die('sad54yfljsosdfhg;adbf;j '.$sql);
	
		}
	
		$attribute_info = $Attributes->getAttribute((int)$data['attribute_id']); 
	
		if($attribute_info){
			
			$data_attribute = array(
						'product_id' => $data['product_id'],
						'attribute_id' => $attribute_info['attribute_id'],
						'group_name' => $attribute_info['group_name'],
						'name' => $attribute_info['name']
								);
			$Attributes->echoAttributeList1($data_attribute);
			
		}
	
	}
	

}elseif($key == 'get_attribute_list'){
	

	$attribute_list = $Attributes->getAttributes((int)$data['attribute_group_id']); ?>
	
	<div class="div_attribute_list_close">
		<a href="javascript:;" class="attribute_list_close">
			<img src="/backend/img/cancel.png" title="удалить" width="18" height="18">
		</a>
	</div>
	
	<?php foreach($attribute_list as $index => $row){ ?>
		
		<a href="javascript:;"
			class="select_attribute"
			data-product_id="<?php echo $data['product_id']; ?>"
			data-attribute_id="<?php echo (int)$index; ?>">
			<?php echo $row['name']; ?></a><br>
		
	<?php }
	
	
	
}

?>