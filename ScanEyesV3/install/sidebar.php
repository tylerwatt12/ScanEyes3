<?php
?>
<div class="col-lg-2 col-md-5 col-sm-12">
<ul class="nav nav-pills nav-stacked">
	<li <?php if($_GET['instpage'] == 1){?>class="active"<?php } ?>>
		<a href="#">Step1</a>
	</li>
	<li <?php if($_GET['instpage'] == 2){?>class="active"<?php } ?>>
		<a href="#">Step2</a>
	</li>
</ul>
<br>
	<div class="progress">
		<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
		60%
		</div>
	</div>
</div>
