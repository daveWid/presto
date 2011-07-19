	<!-- Navigation -->
	<ul id="nav">
<?php foreach($links as $uri => $title): ?>
		<li><?php
	echo HTML::anchor($uri, $title, array(
		"class" => ($uri == $active) ? "active" : ""
	)); ?></li>
<?php endforeach; ?>
	</ul>
