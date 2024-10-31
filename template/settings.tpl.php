<div class="wrap">
	
	<div id="icon-options-general" class="icon32"></div>
	<h2>QBeez Short Urls (Wordpress Plugin)</h2>
	
	<div id="poststuff">
	
		<div id="post-body" class="metabox-holder columns-2">
		
			<!-- main content -->
			<div id="post-body-content">
				
				<div class="meta-box-sortables ui-sortable">
					
					<div class="postbox">
					
						<h3><span>Plugin settings</span></h3>
						<div class="inside">
                        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="qbeez_shorturl_settings" style="margin-top:2em;margin-left:1em;">
                        
                            
                            <?php echo __('<h4>Step 1: Select your favorite QBeez Domain</h4>') ?>
                            <select name="ApiUrlList" size="1" onchange="this.form.ApiUrl.value = this.form.ApiUrlList.value;" class="regular-text">
                            
                            <?php foreach ($apiUrls as $item): ?>
                            
                            <option value="<?php echo $item['url'] ?>"><?php echo $item['name'] ?></option>
                            
                            <?php endforeach ?>
                            </select>
                            
                            <br /><br />
                            <?php echo __('<h4>Step 2: Edit the api to your needs (Note: the output must always be .json)</h4>') ?>
                            
                            <input type="text" name="ApiUrl" value="<?php echo $opt['ApiUrl'] ?>" class="large-text" />

                            <p>
                                If you have your own personal QBeez API key then you can replace the key with your own. All QBeez created with your key will be visisble in your account dashboard.<br />
                                For more info about our API you can visit; <a href="http://qbeez.nl/api_documentation.php" target="_blank">http://qbeez.nl/api_documentation.php</a>
                            </p>


                            <?php echo __('<h4>Step 3: Do you want this plugin to post the short url in your post?</h4>') ?>
                            

                            <input type="radio" name="Display" value="Y" <?php echo $opt['Display'] == 'Y' ? 'checked="checked"' : '' ?> /> <?php echo __('Yes') ?>
                            <input type="radio" name="Display" value="N" <?php echo $opt['Display'] == 'N' ? 'checked="checked"' : '' ?> /> <?php echo __('No') ?>
                            
                            
                            <br /><br /><br />
                            <input type="submit" name="save" class="button-primary" value="<?php echo __('Save my QBeez settings') ?>" />
                        
                        </form>
						</div> <!-- .inside -->
					
					</div> <!-- .postbox -->
					
				</div> <!-- .meta-box-sortables .ui-sortable -->
				
			</div> <!-- post-body-content -->
			
			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">
				
                <div class="meta-box-sortables ui-sortable">
					
					<div class="postbox">
					
						<h3><span>About this plugin</span></h3>
						<div class="inside">
							Please read more about this small plugin on <a href="http://qbeez.nl/27" target="_blank">our website</a>. <br /><br /> By using this plugin you automaticly agree or <a href="http://qbeez.nl/27" target="_blank">terms of use</a> and <a href="http://qbeez.nl/27" target="_blank">privacy policy</a>.<br /><br />
                            
                            &copy; Copyright 2012 - <? echo date("Y");?> QBeez
						</div> <!-- .inside -->
					
					</div> <!-- .postbox -->
					
				</div> <!-- .meta-box-sortables .ui-sortable -->
                
				<div class="meta-box-sortables ui-sortable">
					
					<div class="postbox">
					
						<h3><span>Anti Malware</span></h3>
						<div class="inside">
							QBeez uses data from a number of independent sources to determine whether or not destination sites propagate spam, viruses, or other malware.
						</div> <!-- .inside -->
					
					</div> <!-- .postbox -->
					
				</div> <!-- .meta-box-sortables .ui-sortable -->
				
			</div> <!-- #postbox-container-1 .postbox-container -->
			
		</div> <!-- #post-body .metabox-holder .columns-2 -->
		
		<br class="clear">
	</div> <!-- #poststuff -->
	
</div>