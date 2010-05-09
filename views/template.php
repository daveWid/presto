<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo $title; ?></title>

	<!-- CSS -->
<?php foreach($css as $file => $media) echo "\t",  HTML::style($file, array('media' => $media)), "\r"; ?>

	<!-- JS -->
<?php foreach($js as $file) echo "\t",  HTML::script($file), "\r"; ?>

<!--[if IE]><script src="/media/js/html5.js"></script><![endif]-->
</head>
<body>

<?php echo $content; ?>

</body>
</html>