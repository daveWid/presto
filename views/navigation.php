	<!-- Navigation -->
	<nav>
		<ul>
<?php
	foreach($links as $uri => $title)
	{
		echo "\t\t\t", "<li>", HTML::anchor($uri, $title, array(
			"class" => ($uri == $active) ? "active" : ""
		)), "</li>\r";
	}
?>
		</ul>
	</nav>
