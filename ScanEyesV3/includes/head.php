<?php // This is here to make page based scripts work
	if (@!$_GET['page']) { // If a user just types in the website name without page name
		$page = "home";
	}else{
		$page = preg_replace("/[^A-Za-z]/", "", $_GET['page']); // If page is specified, clean it
	}
?>
<head>
	<title>ScanEyes 3.0 Beta</title>
	<link rel="stylesheet" type="text/css" href="styles/bootstrap.css">

	<?php // Scripts built in for faster execution
	if ($page == "importrrtgid" || $page == "importuttgid") {
	?>
		<script language='JavaScript'>
			checked = false;
			function checkedAll () {
				if (checked == false){checked = true}else{checked = false}
				for (var i = 0; i < document.getElementById('talkgroups').elements.length; i++) {
					document.getElementById('talkgroups').elements[i].checked = checked;
				}
			}
		</script>
	<?php
		}
	?>
<?php #if UACode
	if(empty($config['uacode']) == false){ ?>
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		  ga('create', '<?php echo $config['uacode']; ?>', '<?php echo $config['domain']; ?>');
		  ga('require', 'displayfeatures');
		  ga('send', 'pageview');
		</script>
		
<?php } ?>
</head>