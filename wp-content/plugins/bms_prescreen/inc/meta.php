<?php
/**
 * Plugin meta for single post or page type.
 *
 * @package    BMS Prescreen Rest API
 * @author     Kiki Schuelling <cs@villaester.de>
 * @copyright  Copyright (c) 2021 BMS Consulting
 * @link       https://villaester.de

 */?>
 <div class="shfs_meta_control">

	<p><?php esc_html_e('The script in the following textbox will be inserted to the &lt;head&gt; section', 'header-and-footer-scripts'); ?>.</p>
	<p>
		<textarea name="_inpost_head_script[synth_header_script]" rows="5" style="width:98%;font-family:monospace;"><?php if(!empty($meta['synth_header_script'])) echo $meta['synth_header_script']; ?></textarea>
	</p>
</div>
