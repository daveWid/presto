<!doctype html>
<html>
<head>
	<title><?php echo $title; ?></title>

	<!-- Metadata -->
	<meta charset="<?php echo Kohana::$charset; ?>">
<?php foreach($meta as $key => $value) echo "\t", "<meta", HTML::attributes(array("name" => $key, "content" => $value)), ">\r"; ?>


	<!-- CSS -->
<?php foreach($css as $file => $media) echo "\t", HTML::style($file, array("media" => $media)), "\r"; ?>


	<!-- JavaScript -->
<?php foreach($js as $src) echo "\t", HTML::script($src), "\r"; ?>

	<!--[if lt IE 9]>
	<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
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