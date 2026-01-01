<?php
global $ALL_MESSAGE_KEYS;

foreach ( $ALL_MESSAGE_KEYS as $key ) {
	if(isset($_SESSION[$key])) {
	?>
		<div class="<?=$key?>">
			<div class="message">
				<?= $_SESSION[$key] ?>
			</div>
		</div>
	<?php
		unset( $_SESSION[$key]);
	}
}
?>
