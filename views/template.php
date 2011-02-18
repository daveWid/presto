<!doctype html>
<html>
<head>
	<title><?php echo $title; ?></title>

	<!-- Metadata -->
	<meta charset="<?php echo Kohana::$charset; ?>">
<?php foreach($meta as $key => $value) echo "\t", "<meta", HTML::attributes(array("name" => $key, "content" => $value)), ">\r"; ?>


	<!-- CSS -->
<?php foreach($css as $file => $type) echo "\t", HTML::style($file, array("type" => $type)), "\r"; ?>


	<!-- JavaScript -->
<?php foreach($js as $src) echo "\t", HTML::script($src), "\r"; ?>


</head>

<body>
	<!-- Header -->
	<header>

	</header>

	<div id="content">
		<?php echo $content, "\r"; ?>
	</div>

	<!-- Footer -->
	<footer>

	</footer>

</body>
</html>