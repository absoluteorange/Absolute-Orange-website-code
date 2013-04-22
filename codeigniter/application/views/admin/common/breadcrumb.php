<div>
	<ul>
	<?php $uri = ''; ?>
	<?php for ($i=1; $i<=count($this->uri->segments); $i++):?>
			<?php $uri .= '/'.$this->uri->segment($i); ?>
			<?php if ($this->uri->segment($i) == 'editShowcase'): 
				$uriName = 'Edit '.$showcase['name'];
				$uri .= '?showcase='.$_GET['showcase']; 
			else:
				$uriName = $this->uri->segment($i);
			endif; ?>		
			<?php if ($i == count($this->uri->segments)): ?>
				<li><?=$uriName; ?></li>
			<?php else: ?>
				<li>
					<a href="<?=site_url($uri);?>" title="<?=$uriName; ?>"><?=$uriName; ?></a>
				</li>
			<?php endif; ?>
	
	<?php endfor; ?>
	</ul>
</div>