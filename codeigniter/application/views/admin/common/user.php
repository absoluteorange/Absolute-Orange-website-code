<?php if (isset($userName)): ?>
	<p>Welcome <?=$userName ?>.
		<a href="../admin/clear">
			Not <?=$userName ?>
		</a>
	</p>
<?php endif; ?>