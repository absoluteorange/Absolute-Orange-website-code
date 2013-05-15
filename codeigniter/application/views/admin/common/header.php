<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
	<meta name="author" content="<?= $author ?>" />
	<meta name="keywords" content="<?= $keywords ?>" />
	<meta name="copyright" content="<?= $copyright ?>" />
	<title><?= $this->config->item('title');?> : <?= $title; ?></title>
	<link rel="shortcut icon" href="<?=site_url('favicon.ico'); ?>" />
	<link href="<?= site_url('styles/bootstrap/bootstrap.css'); ?>" rel="stylesheet" type="text/css" />
</head>
<body>