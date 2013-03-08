<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Alexey Serikov Kompaniets Online</title>
<link rel="stylesheet" href="/assets/css/jquery.mobile.custom.min.css" />
<link rel="stylesheet" href="/assets/css/jquery.mobile.custom.structure.css" />
<link rel="stylesheet" href="/assets/css/jquery.mobile.custom.theme.css" />
<script src="/assets/js/jquery.js"></script>
<script src="/assets/js/jquery.mobile.custom.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div data-role="page" data-theme="a">
    <div data-role="header" data-divider-theme="b">
    <h1>Alexey Serikov Kompaniets Online</h1>
    </div>
    <div data-role="navbar" position="fixed">
    <ul>
    <li><a href="/mobile/">Home</a>
    <li><a href="/old/">Old site</a></li>
	<li><a href="/mobile/MuMVC/">MuMVC</a>
	<li><a rel="external" href="/assets/pdf/CV_es.pdf">Spanish C.V.</a></li>
	<li><a rel="external" href="/assets/pdf/CV_en.pdf">English C.V.</a></li>
    </ul>
	<ul data-role="navbar" data-inset="true" data-divider-theme="b"> 
	{block name="crumb"}
	<li data-theme="a"><a href="{$link}">{$description}</a></li>
	{/block}
	</ul>
    </div>
    <div data-role="content">
	{$CONTENT}
	<ul data-role="listview" data-inset="true" data-divider-theme="b">
	</ul>
	<ul data-role="listview" data-inset="true" data-divider-theme="d">
		<li data-role="list-divider">Projects</li>
		<li><a href="/mobile/MuMVC/">MuMVC</a></li>
	</ul>
	</div>
	<div data-role="footer" data-position="fixed">
    </div>
</div>
 </body>
 </html>
