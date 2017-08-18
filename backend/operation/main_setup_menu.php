<?php
	
	if(!isset($_SESSION['master_id'])) $_SESSION['master_id'] = 1;
	if(!isset($_SESSION['currency_id'])) $_SESSION['currency_id'] = 1;
	
	//echo '<pre>'; print_r(var_dump( $_SESSION  ));
	
?>
<div class="global_setup">
	<div>Общие параметры операции:</div><br>
	
	<div>
	<b>Хозяин : </b>
		<select class="global_setup" id="global_master_id" style="width:100px;">
			<?php foreach($masters as $index => $value){?>
				<?php if($index == $_SESSION['master_id']){ ?>
					<option value="<?php echo $index; ?>" selected><?php echo $value['name']; ?></option>
				<?php }else{ ?>
					<option value="<?php echo $index; ?>"><?php echo $value['name']; ?></option>
				<?php } ?>
			<?php } ?>
		</select>
	</div><br>
	
	<div>
	<b>Валюта : </b>
		<select class="global_setup" id="global_currency_id" style="width:100px;">
			<?php foreach($currencys as $index => $value){?>
				<?php if($index == $_SESSION['currency_id']){ ?>
					<option value="<?php echo $index; ?>" selected><?php echo $value['title']; ?></option>
				<?php }else{ ?>
					<option value="<?php echo $index; ?>"><?php echo $value['title']; ?></option>
				<?php } ?>
			<?php } ?>
		</select>
	</div>
</div>

<style>
	div.global_setup{
		float: right;
		font-size: 12px;
		margin-right: 10px;
		margin-top: -25px;
		z-index: 99999;
		background-color: white;
		border: 3px solid gray;
		padding: 5px;
		position: relative;
	}
	.global_setup select{
		margin-top: -3px;
	}
</style>

<script>
	jQuery(document).on('change','.global_setup', function(){
		
		var name = $(this).attr('id');
		name = name.replace('global_', '');
		
		var post = 'key=set_session';
		post = post + '&index='+name;
		post = post + '&value='+$(this).val();
		
		jQuery.ajax({
				type: "POST",
				url: "/backend/ajax/ajax_session.php",
				dataType: "text",
				data: post,
				beforeSend: function(){
				},
				success: function(msg){
					//console.log(msg);
					//console.log(post);
				}
		});
	
	});
</script>
