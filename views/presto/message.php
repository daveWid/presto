<ul id="message" class="<?php echo $msg->type; ?>">
<?php foreach ($msg->message as $m): ?>
	<li><?php echo $m; ?></li>
<?php endforeach; ?>
</ul>