<?php
script('yadisbo', 'tinymce/tinymce.min');
script('yadisbo', 'script');
style('yadisbo', 'style');
?>
<input type="hidden" name="nextNonce" id="nextNonce" value="<?php p(\OC::$server->getContentSecurityPolicyNonceManager()->getNonce()) ?>" />
<div id="app">
	<div id="app-content">
		<div id="app-content-wrapper">
			<div class="yadb-content">
				<?php
					print_unescaped($this->inc('content/index'));
				?>
			</div>
		</div>
	</div>
</div>
