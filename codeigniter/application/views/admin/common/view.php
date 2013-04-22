<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th><?=$tableName; ?></th>
			<?php if ($tableName == 'skills'): ?>
				<th>Expertise</th>
			<?php endif; ?>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($items as $item): ?>
			<tr>
				<td>	
					<?php if(isset($editFormId) AND $editFormId == $item['id']):?>
						<?php $form_fieldName = 'edit'.ucfirst($fieldName); ?>
						<form class="form-horizontal" method="post" action="">
							<input type="hidden" name="formAction" value="edit<?=ucfirst($tableName); ?>" />
							<input type="hidden" name="editId" value="<?=$item['id']; ?>"/>
							<fieldset>
								<div class="control-group <?php if (form_error($form_fieldName)): echo 'error'; endif; ?>">
									<div class="input-append">
										<input type="text" class="" value="<?php if(set_value($form_fieldName)): echo set_value($form_fieldName); else: echo $item[$fieldName]; endif; ?>" name="<?=$form_fieldName; ?>" required />
										<button class="btn" type="submit">Save</button>
									</div>
									<?php if (form_error($form_fieldName)): ?>
						            	<span class="help-inline error"><?=form_error($form_fieldName); ?></span>
						            <?php endif; ?>
						        </div>
							</fieldset>
						</form>	
					<?php else: 
						echo $item[$fieldName]; 
					endif; ?>
				</td>
				<?php if ($tableName == 'skills'): ?>
					<td>
					<?php if(isset($editFormId) AND $editFormId == $item['id']): ?>
						<?php $form_fieldName = 'editExpertise'; ?>
						<form class="form-horizontal" method="post" action="">
							<input type="hidden" name="formAction" value="<?= $form_fieldName?>" />
							<input type="hidden" name="editId" value="<?=$item['id']; ?>"/>
							<fieldset>
								<div class="control-group <?php if (form_error($form_fieldName)): echo 'error'; endif; ?>">
									<div class="input-append">
										<input type="text" class="" value="<?php if(set_value($form_fieldName)): echo set_value($form_fieldName); else: echo $item['expertise']; endif; ?>" name="<?=$form_fieldName; ?>" required />
										<button class="btn" type="submit">Save</button>
									</div>
									<?php if (form_error($form_fieldName)): ?>
						            	<span class="help-inline error"><?=form_error($form_fieldName); ?></span>
						            <?php endif; ?>
						        </div>
							</fieldset>
						</form>	
					<?php else:
						echo $item['expertise'];
					endif; ?>
					</td>
				<?php endif; ?>
				<td>
					<?php if (isset($editFormId) AND $editFormId == $item['id']):
						$href = site_url('admin/showcases/editShowcase/'.$tableName.'?showcase='.$_GET['showcase']); ?>
						<a href="<?=$href; ?>" id="">Cancel</a>
					<?php else: 
					 		if ($tableName == 'showcase'):	
					 			$href=site_url('admin/showcases/editShowcase?'.$tableName.'='.$item[$fieldName].'&id='.$item['id']);					 
					 		elseif ($tableName == 'blog'):
					 			$href=site_url('admin/blogs/editBlog?'.$tableName.'='.$item[$fieldName].'&id='.$item['id']);	
					 		else:
					 			$href=site_url('admin/showcases/editShowcase/'.$tableName.'?showcase='.$_GET['showcase'].'&editName='.$item[$fieldName].'&editId='.$item['id']);
					 		endif; ?>
					 		<a href="<?=$href; ?>" id="<?=$tableName; ?>-editItem-<?=$item['id']?>">Edit</a>
					<?php endif; ?>
		        </td>
		        <td>
		            <a href="<?=site_url('admin/delItem?section='.$section.'&table='.$tableName.'&id='.$item['id']); ?>" id="<?=$tableName; ?>-delItem-<?=$item['id']?>">Delete</a>
		        </td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
<?php $form_fieldName = 'add'.ucfirst($fieldName); ?>
<form class="form-horizontal" method="post" action="">
	<input type="hidden" name="formAction" value="add<?=ucfirst($tableName); ?>" />
	<fieldset>
		<legend>Add <?=$tableName; ?></legend>
		<div class="control-group <?php if (form_error($form_fieldName)): echo 'error'; endif; ?>">
			<div class="input-append">
				<?php if ($tableName == 'skills'): ?>
					<input type="text" name="<?=$form_fieldName?>" value="<?=set_value($form_fieldName); ?>" placeholder="skills" required />
					<input type="text" name="addExpertise" value="<?=set_value('addExpertise'); ?>" placeholder="expertise" required />
				<?php else: ?>
	            	<input type="text" name="<?=$form_fieldName; ?>" value="<?=set_value($form_fieldName); ?>" required />
	            <?php endif; ?>
				<button class="btn" type="submit">Add</button>
				<?php if (form_error($form_fieldName)): ?>
	            	<span class="help-inline error"><?=form_error($form_fieldName); ?></span>
	            <?php endif; ?>
			</div>
		</div>
		<?php if ($tableName == 'skills'): ?>
	    	<span class="help-block">For example skill = 'web development' and expertise = 'web developer'.</span>
		<?php endif; ?>
	</fieldset>
</form>	