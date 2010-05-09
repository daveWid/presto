<ul id="message" class="<?php echo $msg->type; ?>">
<?php
	if( is_array( $msg->message ) ):
		foreach( $msg->message as $m ): ?>
	<li><?php echo $m; ?></li>
<?php
		endforeach;
	else: ?>
	<li><?php echo $msg->message; ?></li>
<?php endif; ?>
</ul>