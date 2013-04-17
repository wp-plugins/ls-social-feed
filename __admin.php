<?php
if(!class_exists('NHP_Options')){
	require_once( dirname( __FILE__ ) . '/options/options.php' );
}


function ls_social_feed_NHP_options(){
	$args = array();
	
	//Set it to dev mode to view the class settings/info in the form - default is false
	$args['dev_mode'] = false;
	
	//Add HTML before the form
	$args['intro_text'] = '';
	
	//Setup custom links in the footer for share icons
	$args['share_icons']['twitter'] = array(
		'link' => 'http://twitter.com/LadaSoukup',
		'title' => 'Folow me on Twitter', 
		'img' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_322_twitter.png'
	);
	
	//Choose to disable the import/export feature
	//$args['show_import_export'] = false;
	
	//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
	$args['opt_name'] = 'ls_social_feed';
	
	//Custom menu icon
	$args['menu_icon'] = NHP_OPTIONS_URL.'img/menu_icon.png';
	
	//Custom menu title for options page - default is "Options"
	$args['menu_title'] = 'LS Social Feed';
	
	//Custom Page Title for options page - default is "Options"
	$args['page_title'] = 'LS Social Feed';
	
	//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "nhp_theme_options"
	$args['page_slug'] = 'ls_social_feed';
	
	//Custom page capability - default is set to "manage_options"
	//$args['page_cap'] = 'manage_options';
	
	//page type - "menu" (adds a top menu section) or "submenu" (adds a submenu) - default is set to "menu"
	$args['page_type'] = 'submenu';
	
	//parent menu - default is set to "themes.php" (Appearance)
	//the list of available parent menus is available here: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	$args['page_parent'] = 'options-general.php';
	
	//custom page location - default 100 - must be unique or will override other items
	$args['page_position'] = 28.45779653269764354066;
	
	//Custom page icon class (used to override the page icon next to heading)
	//$args['page_icon'] = 'icon-themes';
	
	//Want to disable the sections showing as a submenu in the admin? uncomment this line
	$args['allow_sub_menu'] = false;
			
	
	$sections = array();
	
	$sections[] = array(
			'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_320_facebook.png',
			'title' => 'Facebook',
			'desc' => __( 'You need an active Facebook App to fetch Facebook feed.', 'ls_social_feed' ),
			'fields' => array(
				array(
					'id' => 'fb_appid',
					'title' => __( 'App Id', 'ls_social_feed' ),
					'type' => 'text'
				),
				array(
					'id' => 'fb_appsecret',
					'title' => __( 'App Secret', 'ls_social_feed' ),
					'type' => 'text'
				)
			)
	);
	
	$sections[] = array(
			'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_322_twitter.png',
			'title' => 'Google +',
			'desc' => __( 'You need an active Facebook App to fetch Facebook feed.', 'ls_social_feed' ),
			'fields' => array(
				array(
					'id' => 'gplus_key',
					'title' => __( 'Goolge+ API Key', 'ls_social_feed' ),
					'type' => 'text'
				)
			)
	);
	
	$tabs = array();
	
	ob_start() ?>
	<div>
		<?php _e( 'If You like this plugin, please donate and support development. Thank You :)', 'ls_social_feed' ); ?>
	</div>
	<div>&nbsp;</div>
	<div>
		<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=P6CKTGSXPFWKG&lc=CZ&item_name=Ladislav%20Soukup&item_number=LS%20Social%20Feed&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank" style="text-decoration: none;">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif">
		</a>
		&nbsp;
		<a href="http://flattr.com/thing/1263240/LS-Social-Feed" target="_blank"><img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a>
	</div>
	<div>&nbsp;</div>
	<div>
		<a href="http://profiles.wordpress.org/ladislavsoukupgmailcom" targe="_blank"><?php _e( 'More free plugins...', 'ls_social_feed' ); ?></a>
		&nbsp;|&nbsp;<a href="http://git.ladasoukup.cz/" targe="_blank"><?php _e( 'More projects - GIT', 'ls_social_feed' ); ?></a>
	</div>
	
	<?php $tab_html = ob_get_clean();
	
	$tabs['ls_social_feed__info'] = array(
		'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_012_heart.png',
		'title' => __( 'About this plugin', 'ls_social_feed' ),
		'content' => $tab_html
	);
	
	
	
	global $ls_social_feed_NHP_options;
	$ls_social_feed_NHP_options = new NHP_Options($sections, $args, $tabs);
	
} //function
add_action('init', 'ls_social_feed_NHP_options', 0);
?>