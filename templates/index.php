<?php
script('yadisbo', 'script');
style('yadisbo', 'style');
?>

<div id="app">
	<div id="app-content">
		<div id="app-content-wrapper">
			<div class="db-content">
				<?php print_unescaped($this->inc('content/index')); ?>
			</div>
		</div>
	</div>
</div>