<?php $progress_text = $form_progress ? ' data-progresstext="' . $form_progress . '"' : FALSE; ?>
<?php $enc_type = $form_enctype ? ' enctype="' . $form_enctype . '"' : FALSE; ?>
<?php $form_class = $standard ? ' class="standard"' : FALSE;?>
<?php $form_action = is_string( $form ) ? $form : '/' . $this->uri->real_segment();?>
<div class="alert alert-<?=$type?> alert-dismissible" id="<?=$id?>">
	<?php if( $form ){ ?>
	<form action="<?=$form_action?>"<?=$form_class?> method="post"<?=$enc_type?><?=$progress_text?>>
	<?php } ?>
	
		<?php if( $close ){ ?>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?php } ?>
		<?php if( $title ){ ?>
		<h4><?=$title?></h4>
		<?php } ?>
		<div class="alert-body mb10 clearfix"><?=$body?></div>
				
		<?php if( sizeof( $buttons ) ){ ?>
		<div class="alert-footer">
			<?php foreach( $buttons as $button ){ ?>
			<?php $btn_type = isset( $button[ 'type' ] ) ? $button[ 'type' ] : 'button'; ?>
			<?php $btn_role = isset( $button[ 'role' ] ) ? 'btn-' . $button[ 'role' ] : 'btn-default'; ?>
			<?php $btn_dismiss = isset( $button[ 'dismiss' ] ) ? ' data-dismiss="alert"' : FALSE; ?>
			<?php $btn_exec = isset( $button[ 'exec' ] ) ? ' data-exec="' . $button[ 'exec' ] . '"' : FALSE; ?>
			<button type="<?=$btn_type?>" class="btn <?=$btn_role?>"<?=$btn_exec?><?=$btn_dismiss?>><?=$button[ 'label' ]?></button>
			<?php } ?>
		</div>
		<?php } ?>
		
		<?php 
		if( sizeof( $data ) ){
			foreach( $data as $key => $val ){
				if( is_array( $val ) ){
					foreach( $val as $k => $v ){
						echo '<input type="hidden" name="' . $key . '[]" value="' . $v . '" />';
					}
					continue;
				}
				
				echo '<input type="hidden" name="' . $key . '" value="' . $val . '" />';
			}
		}
		?>
		
	<?php if( $form ){ ?>
	</form>
	<?php } ?>

	<script type="text/javascript">
		$(document).ready(function(){
			$( '#<?=$id?>' ).on('close.bs.alert', function(){
				$(this).remove();
				<?php if( $dismiss_url && ! $this->input->is_ajax_request() ){?>
				window.location.href = '<?=$dismiss_url?>';
				<?php } ?>
			});
			helper.registerControl( $( '#<?=$id?>' ) );
		});
	</script>
	
</div>
