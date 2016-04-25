<?php 
	$modal_type = $type ? " modal-$type" : FALSE;
	$progress_text = $form_progress ? ' data-progresstext="' . $form_progress . '"' : FALSE;
	$enc_type = $form_enctype ? ' enctype="' . $form_enctype . '"' : FALSE; 
	
	$dimension = FALSE;
	switch( $screen ){
		case 'full':
			$dimension = ' style="width:96%; height:96%"';
			break;
		case 'standard':
			break;
		default:
			$screen_data = explode( 'x', $screen );
			if( $screen_data >= 2 ){
				$dimension = ' style="width:' . $screen_data[ 0 ] . '; height:' . $screen_data[ 1 ] . '"';
			}
			break;
	}
	
	$form_action = is_string( $form ) ? $form : '/' . $this->uri->real_segment();
	$form_class = $standard ? ' class="standard"' : FALSE;
?>

<div class="modal fade<?=$modal_type?>" id="<?=$id?>">
	<div class="modal-dialog"<?=$dimension?>>
		<div class="modal-content">
			<?php if( $form ){ ?>
			<form action="<?=$form_action?>"<?=$form_class?> method="post"<?=$enc_type?><?=$progress_text?>>
			<?php } ?>
			
				<?php if( $title ){ ?>
				<div class="modal-header">
					<?php if( $close ){ ?>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<?php } ?>
					<h4 class="modal-title"><?=$title?></h4>
				</div>
				<?php } ?>
				<div class="modal-body"><?=$body?></div>
				<?php if( sizeof( $buttons ) ){ ?>
				<div class="modal-footer">
					<?php foreach( $buttons as $button ){ ?>
					<?php $btn_type = isset( $button[ 'type' ] ) ? $button[ 'type' ] : 'button'; ?>
					<?php $btn_role = isset( $button[ 'role' ] ) ? 'btn-' . $button[ 'role' ] : 'btn-default'; ?>
					<?php $btn_dismiss = $button[ 'dismiss' ] ? ' data-dismiss="modal"' : FALSE; ?>
					<?php $btn_exec = isset( $button[ 'exec' ] ) ? ' data-exec="' . $button[ 'exec' ] . '"' : FALSE; ?>
					<button type="<?=$btn_type?>" class="btn pull-right <?=$btn_role?>"<?=$btn_exec?><?=$btn_dismiss?>><?=$button[ 'label' ]?></button>
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
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			$( '#<?=$id?>' ).on('hidden.bs.modal', function(){
				$(this).remove();
				$( 'body' ).css( 'margin', 0 ).css( 'padding', 0 );
				<?php if( $dismiss_url && ! $this->input->is_ajax_request() ){?>
				window.location.href = '<?=$dismiss_url?>';
				<?php } ?>
			});
			$( '#<?=$id?>' ).modal( { keyboard: false } );
			$( '#<?=$id?>' ).modal( 'show' );
			helper.registerControl( $( '#<?=$id?>' ) );
		});
	</script>
	
</div>
