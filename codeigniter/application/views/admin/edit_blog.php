<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
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
		<?php if (!empty($blogLogos)): ?>
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
	        			<?php foreach ($blogLogos as $logo): ?>
		        			<tr>
							    <td>
							    	<img src='/images/blog/logos/small/<?=$logo['image_url']; ?>' id="<?=$logo['id']; ?>" alt="<?=$logo['image_alt']; ?>" title="<?=$logo['image_alt']; ?>" />
		            			</td>
							    <td>
							    	<a href="<?=site_url('admin/delFile?section=blog&blog='.$title.'&type=logo&id='.$logo['id']); ?>">Delete</a>
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
				<span class="help-block">Images should have a max height of 100px</span>
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
		<legend>Blog details</legend>
		<div class="control-group <?php if (form_error('url')): echo 'error'; endif; ?>">
			<label class="control-label" for="url">Title formated for URL</label>
			<div class="controls">
				<input type="text" name="url" id="url" value="<?php if (set_value('url')): echo set_value('url'); else: echo $url; endif; ?>" required/>
		        <?php if (form_error('url')): ?>
		        	<span class="help-inline error"><?=form_error('url'); ?></span>
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
		<div class="control-group <?php if (form_error('description')): echo 'error'; endif; ?>">
	    	<label class="control-label" for="description">Description</label>
	    	<div class="controls">
	    		<textarea class="span11" name="description" id="description" rows="20"><?php if (set_value('description')): echo set_value('description'); else: echo $description; endif; ?></textarea>
		        <?php if (form_error('description')): ?>
		            <span class="help-inline error"><?=form_error('description'); ?></span>
		        <?php endif; ?>
	        </div>
	    </div>
	</fieldset>
	<fieldset>
		<legend>Screenshots / photos</legend>
		<?php if (!empty($blogImages)): ?>
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
	            		<?php foreach ($blogImages as $image): ?>
							<tr>
								<td>
									<a class="image_<?=$image['id']; ?>" title="<?=$image['image_alt']; ?>" id="image_<?=$count; ?>" rel="lightbox" href="/images/blog/<?=$image['image_url']; ?>">
				                    	<img src="<?=site_url('images/blog/thumbnails/med/'.$image['image_url'])?>" alt="<?=$image['image_alt']; ?>" title="<?=$image['image_alt']; ?>" />
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
									<a href="<?=site_url('admin/delFile?section=blog&blog='.$title.'&type=screenshot&id='.$image['id']); ?>">Delete</a>
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
	            	<span class="help-inline error"><?=$this->form_validation->screenshot; ?></span>
				<?php endif; ?>
				<span class="help-block">Images should have a max height of 600px</span>
            </div>
        </div>
            <div class="control-group <?php if (isset($this->form_validation->screenshot_thumb)): echo 'error'; endif; ?>">
    		<label class="control-label" for="screenshot_thumb">Select thumbnail</label>
            <div class="controls">
	            <input type="file" id="screenshot_thumb" name="screenshot_thumb" value=""/>
	            <?php if (isset($this->form_validation->screenshot_thumb)): ?>
	            	<span class="help-inline error"><?=$this->form_validation->screenshot_thumb; ?></span>
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
		            				<a href="<?=Site_url('admin/delLink?section=blog&blog='.$title.'&id='.$link['id']); ?>">Delete</a>
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