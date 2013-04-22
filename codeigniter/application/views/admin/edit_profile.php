<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
	<input type="hidden" name="formAction" value="updateProfile" />
	<fieldset>
		<legend>Profile picture</legend>
		<?php if (!empty($content->photo)): ?>
			<div>
		    	<table class="table table-striped table-bordered">
		    		<thead>
		    			<tr>
			    			<th>Image</th>
			    			<th></th>
		    			</tr>
		    		</thead>
				    <tbody>
						<tr>
							<td>
								<img src="<?=site_url('images/profile/'.$content->photo); ?>" alt="<?=$employeeName; ?>" title="<?=$employeeName; ?>" />
		            		</td>
							<td>
								<a href="<?=site_url('admin/delFile?section=profile&type=profile&id='.$employeeId); ?>">Delete</a>
							</td>
						</tr>
				 	</tbody>
				</table>  
		    </div>
		<?php endif; ?>
		<?php if (empty($content->photo)): ?>
			<div class="control-group <?php if(isset($this->form_validation->profile)): echo 'error'; endif ?>">
				<label class="control-label" for="profile_photo">Select image:</label>
				<div class="controls">
					<input type="file" name="profile" id="profile_photo" value="" required/>
					<?php if (isset($this->form_validation->profile)): ?>
			            <span class="help-inline error"><?=$this->form_validation->profile; ?></span>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</fieldset>
	<fieldset>
		<legend>Profile picture (back)</legend>
		<?php if (!empty($content->photo_back)): ?>
			<div>
		    	<table class="table table-striped table-bordered">
		    		<thead>
		    			<tr>
			    			<th>Image</th>
			    			<th></th>
		    			</tr>
		    		</thead>
				    <tbody>
						<tr>
							<td>
								<img src="<?=site_url('images/profile/'.$content->photo_back); ?>" alt="Back of <?=$employeeName; ?>" title="Back of <?=$employeeName; ?>" />
		            		</td>
							<td>
								<a href="<?=site_url('admin/delFile?section=profile&type=profile-back&id='.$employeeId); ?>">Delete</a>
							</td>
						</tr>
				 	</tbody>
				</table>  
		    </div>
		<?php endif; ?>
		<?php if (empty($content->photo_back)): ?>
			<div class="control-group <?php if(isset($this->form_validation->profileBack)): echo 'error'; endif ?>">
				<label class="control-label" for="profile_photo_back">Select image:</label>
				<div class="controls">
					<input type="file" name="profile_back" id="profile_photo_back" value="" />
					<?php if (isset($this->form_validation->profileBack)): ?>
			            <span class="help-inline error"><?=$this->form_validation->profileBack; ?></span>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</fieldset>
	<fieldset>
		<legend>Profile summary</legend>
		<div class="control-group<?php if(form_error('years_experience')): echo ' error'; endif; ?>">
			<label for="years_experience" class="control-label">Years experience</label>
			<div class="controls">
				<input class="input-mini" type="text" id="years_experience" name="years_experience" value="<?php if(set_value('years_experience')): echo set_value('years_experience'); elseif(!empty($content->years_of_experience)): echo $content->years_of_experience; endif; ?>" required />
				<?php if(form_error('years_experience')): ?>
					<span class="help-inline error"><?=form_error('years_experience'); ?></span>
				<?php endif; ?>
			</div>
		</div>
		<div class="control-group<?php if(form_error('profile')): echo ' error'; endif; ?>">
			<label for="profile" class="control-label">Profile</label>
			<div class="controls">
				<textarea class="span11" rows="5" id="profile" name="profile" required><?php if(set_value('profile')): echo set_value('profile'); elseif(!empty($content->profile)): echo $content->profile; endif; ?></textarea>
				<?php if(form_error('profile')): ?>
					<span class="help-inline error"><?=form_error('profile'); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>CV's</legend>
		<?php if (!empty($cvs)): ?>
			<div>
		    	<table class="table table-striped table-bordered">
		    		<thead>
		    			<tr>
			    			<th>CV</th>
			    			<th></th>
		    			</tr>
		    		</thead>
				    <tbody>
						<?php foreach ($cvs as $cv): ?>
							<tr>
								<td>
									<a href="<?=site_url('cv/'.$cv['name']);?>" title="<?=$cv['name']; ?>"><?=$cv['name']; ?></a>
			            		</td>
								<td>
									<a href="<?=site_url('admin/delFile?section=profile&type=cv&id='.$cv['id']); ?>">Delete</a>
								</td>
							</tr>
						<?php endforeach; ?>
				 	</tbody>
				</table>  
		    </div>
		<?php endif; ?>
		<?php if (count($cvs) < 2): ?>
			<div class="control-group<?php if(isset($this->form_validation->cv)): echo ' error'; endif ?>">
				<label class="control-label" for="cv">Select CV</label>
				<div class="controls">
					<input type="file" name="cv" value=""/>
			        <?php if (isset($this->form_validation->cv)): ?>
			        	<?php foreach ($this->form_validation->cv as $error): ?>
			            	<span class="help-inline error"><?=$error; ?></span>
			            <?php endforeach; ?>
					<?php endif; ?>
					<span class="help-block">Please upload 2 versions of your CV in PDF and Word format.</span>
				</div>
			</div>
		<?php endif; ?>
	</fieldset>
	<div class="control-group">
    	<input class="btn-primary" type="submit" value="Save" />
    </div>	
</form>
<?php foreach($profileTables as $table): 
	$capitalisedTable = ucfirst($table); 
	$tableResults = $table."Results";?>
	<?php if (!empty($$tableResults)): ?>
		<div>
	    	<table class="table table-striped table-bordered">
	    		<thead>
	    			<tr>
		    			<th><?=$table ?></th>
		    			<th>Order</th>
		    			<th></th>
		    			<th></th>
	    			</tr>
	    		</thead>
			    <tbody>
			    	<?php foreach ($$tableResults as $item): ?>
					<tr>
						<?php if(isset($editFormId) AND $editFormId == $item['id']): ?>
							<td>
								<form class="form-horizontal" method="post" action="">
									<input type="hidden" name="formAction" value="edit<?=$capitalisedTable; ?>" />
									<input type="hidden" name="editId" value="<?=$item['id']; ?>"/>
									<fieldset>
										<div class="control-group <?php if (form_error('edit'.$capitalisedTable)): echo 'error'; endif; ?>">
											<div class="input-append">
												<textarea class="span6" name="edit<?=$capitalisedTable; ?>" required><?php if(set_value('edit'.$capitalisedTable)): echo set_value('edit'.$capitalisedTable); else: echo $item[$table]; endif; ?></textarea>
												<button class="btn" type="submit">Save</button>
											</div>
											<?php if (form_error('edit'.$capitalisedTable)): ?>
								            	<span class="help-inline error"><?=form_error('edit'.$capitalisedTable); ?></span>
								            <?php endif; ?>
								        </div>
									</fieldset>
								</form>	
							</td>
							<td>
								<form class="form-horizontal" method="post" action="">
									<input type="hidden" name="formAction" value="edit<?=$capitalisedTable; ?>Order" />
									<input type="hidden" name="editId" value="<?=$item['id']; ?>"/>
									<fieldset>
										<div class="control-group <?php if (form_error('edit'.$capitalisedTable.'Order')): echo 'error'; endif; ?>">
											<div class="input-append">
												<input type="text" class="input-mini" value="<?php if(set_value('edit'.$capitalisedTable.'Order')): echo set_value('edit'.$capitalisedTable.'Order'); else: echo $item['sort']; endif; ?>" name="edit<?=$capitalisedTable; ?>Order" />
												<button class="btn" type="submit">Save</button>
											</div>
											<?php if (form_error('edit'.$capitalisedTable.'Order')): ?>
								            	<span class="help-inline error"><?=form_error('edit'.$capitalisedTable.'Order'); ?></span>
								            <?php endif; ?>
								        </div>
									</fieldset>
								</form>	
							</td>
						<?php else: ?>
							<td><?=$item[$table]; ?></td>
							<td><?=$item['sort']; ?></td>
						<?php endif; ?>
	            		<td>
	            			<?php if(isset($editFormId) AND $editFormId == $item['id']): ?>
	            				<a href="<?=site_url('admin/profile'); ?>" title="Cancel">Cancel</a>
							<?php else: ?>
								<a href="<?=site_url('admin/profile?profile='.$employeeName.'&editName='.$table.'&editId='.$item['id']); ?>">Edit</a>
							<?php endif; ?>
						</td>
						<td>
							<a href="<?=site_url('admin/delItem?section=profile&table='.$table.'&id='.$item['id']); ?>">Delete</a>
						</td>
					</tr>
					<?php endforeach; ?>
			 	</tbody>
			</table>  
	    </div>
	<?php endif; ?>
	<form class="form-horizontal" method="post" action="">
		<input type="hidden" name="formAction" value="add<?=$capitalisedTable; ?>" />
		<fieldset>
			<legend>Add <?=$table; ?></legend>
			<div class="control-group <?php if (form_error($table)): echo 'error'; endif; ?>">
				<label class="control-label" for="<?=$table; ?>"><?=$capitalisedTable; ?></label>
				<div class="controls">
					<textarea class="span6" id="<?=$table; ?>" name="<?=$table; ?>"><?php if(set_value($table)): echo set_value($table); endif; ?></textarea>
					<?php if (form_error($table)): ?>
		            	<span class="help-inline error"><?=form_error($table); ?></span>
		            <?php endif; ?>
		            <?php if ($table == 'skills'): echo '<span class="help-block">A skill heading is delimited by a ":" and is therefore required when submitting a skill</span>'; endif ?>
		        </div>
	        </div>
	        <div class="control-group <?php if (form_error('order')): echo 'error'; endif; ?>">
				<label class="control-label" for="<?=$table; ?>Order">Order</label>
				<div class="controls">
					<input class="input-mini" id="<?=$table; ?>Order" name="<?=$table; ?>Order" value="<?php if(set_value($table.'Order')): echo set_value($table.'Order'); endif; ?>" />
					<?php if (form_error($table.'Order')): ?>
		            	<span class="help-inline error"><?=form_error($table.'Order'); ?></span>
		            <?php endif; ?>
		        </div>
	        </div>
		</fieldset>
		<div class="control-group">
	   		<input class="btn-primary" type="submit" value="Add" />
	    </div>
	</form>
<?php endforeach; ?>