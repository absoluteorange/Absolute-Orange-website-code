<?php if (isset($users)): ?>
	<form id="selectUser" action="" method="post">
		<fieldset>
			<legend>Please select your user name:</legend>
			<select name="userId">
				<?php foreach ($users as $user): ?>
					<option value="<?=$user['user_id']?>"><?= $user['name']?></option>
				<?php endforeach; ?>
			</select>
			<input type="submit" value="submit" />
		</fieldset>
	</form>
<?php else: ?>
	Welcome <?=$userName; ?>
<?php endif; ?>