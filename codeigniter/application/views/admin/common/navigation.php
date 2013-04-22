<div>
    <ul>
        <?php foreach($navigation as $navigation_item): ?>
            <?php if($navigation_item['title'] != $this->uri->segment(2)): ?>
                 <li>
                 	<a href="<?= $navigation_item['link'] ?>"><?= $navigation_item['title'] ?></a>
                 </li>
            <?php else: ?>
                 <li>
                 	<a class="selected" href="<?= $navigation_item['link'] ?>"><?= $navigation_item['title'] ?></a>
                 </li>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if ($this->uri->segment(2)== "portfolio"): ?>
                <li>
                	<a href="#content">skip to content</a>
                </li>
        <?php endif; ?>
    </ul>
</div>
