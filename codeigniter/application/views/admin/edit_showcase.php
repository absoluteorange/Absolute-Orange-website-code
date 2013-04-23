<form method="post" action="" enctype="multipart/form-data" class="form-horizontal">
   <fieldset>
		<legend>Title</legend>
		<div class="control-group <?php if (form_error('title')): echo 'error'; endif; ?>">
			<label class="control-label" for="title">Title</label>
			<div class="controls">
				<input type="text" name="title" id="title" value="<?php if (set_value('title')): echo set_value('title'); else: echo $title; endif; ?>" required/>
		        <?php if (form_error('title')): ?>
		        	<span class="help-inline error"><?=form_error('title'); ?></span>
		        <?php endif; ?>
	        </div>
	    </div>
	</fieldset>
	<fieldset>
		<legend>Brand images</legend>
		<?php if (!empty($showcaseLogos)): ?>
		    <div>
		    	<table class="table table-striped table-bordered">
		    		<thead>
		    			<tr>
			    			<th>Image</th>
			    			<th></th>
		    			</tr>
		    		</thead>
				    <tbody>
						<?php $count = 1; ?>
	        			<?php foreach ($showcaseLogos as $logo): ?>
		        			<tr>
							    <td>
							    	<img src='/images/showcase/logos/med/<?=$logo['image_url']; ?>' id="<?=$logo['id']; ?>" alt="<?=$logo['image_alt']; ?>" title="<?=$logo['image_alt']; ?>" />
		            			</td>
							    <td>
							    	<a href="<?=site_url('admin/delFile?section=showcase&showcase='.$title.'&type=logo&id='.$logo['id']); ?>">Delete</a>
							    </td>
							</tr>
				            <?php $count++; ?>
			        	<?php endforeach; ?>
				 	</tbody>
				</table>  
		    </div>
		<?php endif; ?>
	    <div class="control-group <?php if (isset($this->form_validation->logo)): echo 'error'; endif; ?>">
	    	<label class="control-label" for="logo">Select image</label>
	        <div class="controls">
		        <input type="file" id="logo" name="logo" value=""/>
		        <?php if (isset($this->form_validation->logo)): ?>
		        	<?php foreach ($this->form_validation->logo as $error):?>
		            	<span class="help-inline error"><?=$error; ?></span>
		            <?php endforeach;?>
				<?php endif; ?>
				<span class="help-block">Images should have a max height of 300px</span>
			</div>
		</div>
		<div class="control-group <?php if (form_error('logo_alt')): echo 'error'; endif; ?>">
	        <label class="control-label" for="logoAlt">Image title</label>
	        <div class="controls">
		        <input type="text" id="logoAlt" value="<?= set_value('logo_alt'); ?>" name="logo_alt" />
		        <?php if (form_error('logo_alt')): ?>
		        	<span class="help-inline error"><?=form_error('logo_alt'); ?></span>
		        <?php endif; ?> 
	    	</div>
	    </div>      
	</fieldset>
	<fieldset>
		<legend>Project details</legend>
		<div class="control-group <?php if (form_error('owner')): echo 'error'; endif; ?>">
	    	<label class="control-label" for="owner">Owner</label>
	    	<div class="controls">
		        <input type="text" id="owner" name="owner" value="<?php if (set_value('owner')): echo set_value('owner'); else: echo $owner; endif; ?>" required/>
		        <?php if (form_error('owner')): ?>
		            <span class="help-inline error"><?=form_error('owner'); ?></span>
		        <?php endif; ?>
	        </div>
		</div>
		<div class="control-group <?php if (form_error('contractor')): echo 'error'; endif; ?>">
	    	<label class="control-label" for="contractor">Contractor</label>
	    	<div class="controls">
		        <input type="text" id="contractor" name="contractor" value="<?php if (set_value('contractor')): echo set_value('contractor'); else: echo $contractor; endif; ?>" required/>
		        <?php if (form_error('contractor')): ?>
		            <span class="help-inline error"><?=form_error('contractor'); ?></span>
		        <?php endif; ?>
	        </div>
		</div>
		<div class="control-group <?php if (form_error('date_started')): echo 'error'; endif; ?>">
	    	<label class="control-label" for="date_started">Start date</label>
	    	<div class="controls">
		        <input type="date" id="date_started" name="date_started" value="<?php if (set_value('date_started')): echo set_value('date_started'); else: echo $dateStarted; endif; ?>" required/>
		        <?php if (form_error('date_started')): ?>
		        	<span class="help-inline error"><?=form_error('date_started') ?></span>
		        <?php endif; ?>
	        </div>
	    </div>
		<div class="control-group <?php if (form_error('date_completed')): echo 'error'; endif; ?>">
	    	<label class="control-label" for="date_completed">Completion date</label>
	    	<div class="controls">
		        <input type="date" id="date_completed" name="date_completed" value="<?php if (set_value('date_completed')): echo set_value('date_completed'); else: echo $dateCompleted; endif; ?>" required/>
		        <?php if (form_error('date_completed')): ?>
		            <span class="help-inline error"><?=form_error('date_completed'); ?></span>
		        <?php endif; ?>
	        </div>
		</div>
		<div class="control-group <?php if (form_error('deliverable')): echo 'error'; endif; ?>">
	    	<label class="control-label" for="deliverable">Deliverable</label>
	    	<div class="controls">
		        <input type="text" id="deliverable" name="deliverable" value="<?php if (set_value('deliverable')): echo set_value('deliverable'); else: echo $deliverable; endif; ?>" required/>
		        <?php if (form_error('deliverable')): ?>
		            <span class="help-inline error"><?=form_error('deliverable'); ?></span>
		        <?php endif; ?>
	        </div>
		</div>
        <div class="control-group <?php if (form_error('link')): echo 'error'; endif; ?>">
	    	<label class="control-label" for="url">URL</label>
	    	<div class="controls">
	    		<input type="text" name="link" id="url" value="<?php if (set_value('link')): echo set_value('link'); else: echo $link; endif; ?>" />
	    		 <?php if (form_error('link')): ?>
	    		 	<span class="help-inline error"><?=form_error('link'); ?></span>
	    		 <?php endif; ?>
	    	</div>
	    </div>
        <div class="control-group <?php if (form_error('description')): echo 'error'; endif; ?>">
	    	<label class="control-label" for="description">Description</label>
	    	<div class="controls">
	    		<textarea class="span11" name="description" id="description" rows="20"><?php if (set_value('description')): echo set_value('description'); else: echo $description; endif; ?></textarea>
		        <?php if (form_error('description')): ?>
		            <span class="help-inline error"><?=form_error('description'); ?></span>
		        <?php endif; ?>
		        <span class="help-block">Please use &lt;h4 class='wrapper-head'&gt;&lt;/h4&gt; and &lt;h5 class='wrapper-head'&gt;&lt;/h5&gt; for headings, </br>
		        &lt;p class='wrapper-body'&gt;&lt;/p&gt; for paragraphs,<br />
		        &lt;ul class='wrapper-body'&gt;&lt;/ul&gt; for lists<br />
		        to ident paragraphs use the &lt;div class='nested-wrapper'&gt;&lt;/div&gt;.</span>
	        </div>
	    </div>
	</fieldset>
	<fieldset>
    	<?php foreach ($showcaseTables as $table): ?>
    		<?php $itemSelected = $table."_selected";?>
    		<?php if (!empty($itemSelected)): ?>
	    		<div>
		    		<table class="table table-striped table-bordered">
		    			<thead>
			    			<tr>
				    			<th>Selected <?=$table ?></th>
				    			<th></th>
			    			</tr>
			    		</thead>
		    			<tbody>
				            <?php foreach ($$itemSelected as $item): ?>
				            	<tr>
				            		<td><?=$item['name'] ?></td>
				            		<td><a href="<?=site_url('admin/delItem?section=showcase&table='.$table.'&id='.$item['id']); ?>">Delete</a></td>
				            	</tr>
				            <?php endforeach; ?>
		    			</tbody>
		    		</table>
	            </div>
	        <?php endif; ?>
            <div class="control-group">
    			<label class="control-label" for="<?=$table; ?>">Select <?=$table; ?></label>
            	<div class="controls">
	            	<select multiple="multiple" id="<?=$table; ?>" name="<?=$table; ?>[]">
		                <?php $selected = false; ?>
		            	<?php foreach ($$table as $table_item): ?>
		                    <?php foreach ($$itemSelected as $item): ?>
		                    	<?php if ($table_item['id'] == $item['id']): ?>
		                            <?php $selected = true; ?>
		                    	<?php endif; ?>
		                    <?php endforeach; ?>
		                    <option value="<?=$table_item['id']; ?>" id="<?=$table ?>_<?=$table_item['id']?>"
		                    <?php if ($selected == true): ?>
		                    	selected="selected"
		                        <?php $selected = false; ?>
		                    <?php endif; ?>
		                    ><?=$table_item['name'] ?></option>
	            		<?php endforeach; ?>
	            	</select>
	            	<span class="help-block"><a href="<?=site_url('admin/showcases/editShowcase/'.$table.'?showcase='.$title); ?>" title="">Manage <?=$table; ?></a></span>
	        	</div>
            </div>
      	<?php endforeach; ?>
      </fieldset>
	  <fieldset>
			<legend>Screenshots / photos</legend>
			<?php if (!empty($showcaseImages)): ?>
				<div>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>
									Image
								</th>
								<th>
									Order
								</th>
								<th>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php $count = 1; ?>
		            		<?php foreach ($showcaseImages as $image): ?>
								<tr>
									<td>
										<a class="image_<?=$image['id']; ?>" title="<?=$image['image_alt']; ?>" id="image_<?=$count; ?>" rel="lightbox" href="/images/showcase/<?=$image['image_url']; ?>">
					                    	<img src="<?=site_url('images/showcase/thumbnails/small/'.$image['image_url'])?>" alt="<?=$image['image_alt']; ?>" title="<?=$image['image_alt']; ?>" />
					                	</a>
									</td>
									<td>
										<?php if (!isset($image['order_index'])): 
					                		$image_order = $count; 
					                	else: 
					                		$image_order = $image['order_index']; 
						                endif; ?>
						                <input type="text" class="input-mini" value="<?=$image_order; ?>" name="screenshot_order_<?=$image['id']; ?>" />
						                <?php $count ++ ?>
						            </td>
						            <td>
										<a href="<?=site_url('admin/delFile?section=showcase&showcase='.$title.'&type=screenshot&id='.$image['id']); ?>">Delete</a>
									</td>
					            </tr>
					        <?php endforeach; ?>
						</tbody>
					</table>      
	            </div>
	        <?php endif; ?>
            <div class="control-group <?php if (isset($this->form_validation->screenshot)): echo 'error'; endif; ?>">
	    		<label class="control-label" for="screenshot">Select image</label>
	            <div class="controls">
		        	<input type="file" id="screenshot" name="screenshot" value=""/>
		        	<?php if (isset($this->form_validation->screenshot)): ?>
		            	<?php foreach ($this->form_validation->screenshot as $error):?>
		            		<span class="help-inline error"><?=$error; ?></span>
		            	<?php endforeach;?>
		            <?php endif; ?>
					<span class="help-block">Images should have a max height of 600px</span>
	            </div>
	        </div>
            <div class="control-group <?php if (isset($this->form_validation->screenshot_thumb)): echo 'error'; endif; ?>">
	    		<label class="control-label" for="screenshot_thumb">Select thumbnail</label>
	            <div class="controls">
		            <input type="file" id="screenshot_thumb" name="screenshot_thumb" value=""/>
		            <?php if (isset($this->form_validation->screenshot_thumb)): ?>
		            	<?php foreach ($this->form_validation->screenshot_thumb as $error):?>
		            		<span class="help-inline error"><?=$error; ?></span>
		            	<?php endforeach;?>
					<?php endif; ?>
					<span class="help-block">Images should have a max height of 300px</span>
				</div>
		    </div>
	        <div class="control-group <?php if (form_error('screenshot_alt')): echo 'error'; endif; ?>">
	            <label class="control-label" for="screenshot_alt">Image title</label>
	            <div class="controls">
		            <input type="text" id="screenshot_alt" value="<?=set_value('screenshot_alt'); ?>" name="screenshot_alt" />
		            <?php if (form_error('screenshot_alt')): ?>
			            <span class="help-inline error"><?=form_error('screenshot_alt'); ?></span>
			        <?php endif; ?>
		        </div>
	        </div>
        </fieldset>
        <fieldset>
        <legend>Related links</legend>
        <?php if(!empty($relatedLinks)): ?>
        <div>
            <table class="table table-striped table-bordered">
            	<thead>
            		<tr>
            			<th>
            				Link
            			</th>
            			<th>
            			</th>
            		</tr>
            	</thead>
            	<tbody>
            		<?php foreach ($relatedLinks as $link): ?>
	            		<tr>
	            			<td>
	            				<a title="<?=$link['name']; ?>" href="<?=$link['url']; ?>" id="relatedlink_<?=$link['id']; ?>"><?=$link['name']; ?></a>
	            			</td>
	            			<td>
	            				<a href="<?=Site_url('admin/delLink?section=showcase&showcase='.$title.'&id='.$link['id']); ?>">Delete</a>
	            			</td>
	            		</tr>
	            	 <?php endforeach; ?>
            	</tbody>
			</table>
		</div>
		<?php endif; ?>
        <div class="control-group <?php if(form_error('related_link')): echo 'error'; endif; ?>">
            <label class="control-label" for="related_link">Link</label>
            <div class="controls">
            	<input type="url" name="related_link" id="related_link" value="<?=set_value('related_link'); ?>" placeholder="http://" />
            	<?php if(form_error('related_link')): ?>
                	<span class="help-inline error"><?=form_error('related_link'); ?></span>
            	<?php endif; ?>
            </div>
        </div>
        <div class="control-group <?php if(form_error('related_link_title')): echo 'error'; endif; ?>">
            <label class="control-label" for="related_link_title">Link title</label>
            <div class="controls">
				<input type="text" name="related_link_title" id="related_link_title" value="<?=set_value('related_link_title'); ?>" />
	            <?php if(form_error('related_link_title')): ?>
                	<span class="help-inline error"><?=form_error('related_link_title'); ?></span>
            	<?php endif; ?>
            </div>
        </div>
    </fieldset>
    <div class="control-group">
   		<input class="btn-primary" type="submit" value="Save" />
    </div>
</form>