<?php
require_once( plugin_dir_path( __FILE__ ) . "/admin-page-class/admin-page-class.php" );

$lssf_config = array(    
	'menu'           => 'settings',             //sub page to settings page
	'page_title'     => 'LS Social Feed',       //The name of this page 
	'capability'     => 'edit_themes',         // The capability needed to view the page 
	'option_group'   => 'ls_social_feed',       //the name of the option to create in the database
	'id'             => 'ls_social_feed',            // meta box id, unique per page
	'fields'         => array(),            // list of fields (can be added by field arrays)
	'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
	'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
);

$lssf_options_panel = new BF_Admin_Page_Class( $lssf_config );

$lssf_options_panel->OpenTabs_container('ls_social_feed');

$lssf_options_panel->TabsListing( array(
	'links' => array(
		'lssf_opt_fb' =>  __( 'Facebook', 'ls_social_feed' ),
		'lssf_opt_gplus' =>  __( 'Google+', 'ls_social_feed' ),
		'lssf_opt_templates' =>  __( 'Templates', 'ls_social_feed' ),
		'lssf_about' => __( 'About this plugin', 'ls_social_feed' ),
		'lssf_importexport' => __( 'Import/Export settings', 'ls_social_feed' )
	)
));

$lssf_options_panel->OpenTab( 'lssf_opt_fb' );
$lssf_options_panel->Title( __("Facebook", "ls_social_feed") );
$lssf_options_panel->addParagraph( __("You need an active Facebook App to fetch Facebook feed.", "ls_social_feed") );
$lssf_options_panel->addText( 'fb_appid', array( 'name'=> __( 'App ID', 'ls_social_feed' ), 'std'=> '', 'desc' => '' ) );
$lssf_options_panel->addText( 'fb_appsecret', array( 'name'=> __( 'App Secret', 'ls_social_feed' ), 'std'=> '', 'desc' => '' ) );
$lssf_options_panel->CloseTab();

$lssf_options_panel->OpenTab( 'lssf_opt_gplus' );
$lssf_options_panel->Title( __("Google+", "ls_social_feed") );
//$lssf_options_panel->addParagraph( __("", "ls_social_feed") );
$lssf_options_panel->addText( 'gplus_key', array( 'name'=> __( 'Google+ API Key', 'ls_social_feed' ), 'std'=> '', 'desc' => '' ) );
$lssf_options_panel->CloseTab();

$lssf_def_template_path = plugin_dir_path( __FILE__ ) . '/tpl-default.html';
$lssf_def_template = @file_get_contents( $lssf_def_template_path );
$lssf_options_panel->OpenTab( 'lssf_opt_templates' );
$lssf_options_panel->Title( __("Templates", "ls_social_feed") );
$lssf_options_panel->addCode( 'main_template', array( 'name' => __( 'Global Template (HTML)', 'ls_social_feed'), 'syntax' => 'html', 'std' => $lssf_def_template, 'desc' => __( '','ls_social_feed' ) ) );

$templates_help = '<h3>' . __("Template Variables", "ls_social_feed") . '</h3>';
$templates_help .= '<div><b>%%item_text%%</b> - ' . __("Feed item text", "ls_social_feed") . '</div>';
$templates_help .= '<div><b>%%meta_author%%</b> - ' . __("Meta data - author", "ls_social_feed") . '</div>';
$templates_help .= '<div><b>%%meta_avatar%%</b> - ' . __("Meta data - author avatar", "ls_social_feed") . '</div>';
$templates_help .= '<div><b>%%meta_info%%</b> - ' . __("Meta data - author description", "ls_social_feed") . '</div>';
$templates_help .= '<div><b>%%meta_date%%</b> - ' . __("Meta data - date posted", "ls_social_feed") . '</div>';
$templates_help .= '<div><b>%%meta_time%%</b> - ' . __("Meta data - time posted", "ls_social_feed") . '</div>';
$templates_help .= '<div><b>%%feed_network%%</b> - ' . __("Network name", "ls_social_feed") . ' (facebook / twitter / gplus)</div>';
$templates_help .= '<div><b>%%class%%</b> - ' . __("Class name constructed from network type including 'mixed' for aggregated feed items", "ls_social_feed") . '</div>';
$templates_help .= '<div><b>%%att_type%%</b> - ' . __("Attachement - type", "ls_social_feed") . '</div>';
$templates_help .= '<div><b>%%att_image%%</b> - ' . __("Attachement - image url address", "ls_social_feed") . '</div>';
$templates_help .= '<div><b>%%item_url%%</b> - ' . __("Url to read more or url to source post", "ls_social_feed") . '</div>';
$templates_help .= '<div><b>%%str_readmore%%</b> - ' . __("Read more text via WordPress translatable text", "ls_social_feed") . '</div>';

$templates_help .= '<h3>' . __("Template Shortcodes", "ls_social_feed") . '</h3>';
$templates_help .= '<div><b>[isset %%variable%%]content[/isset]</b><br/>- ' . __("If %%variable%% is not empty, content will be displayed (all above variables can be used as %%variable%% or content)", "ls_social_feed") . '</div><div>&nbsp;</div>';
$templates_help .= '<div><b>[isempty %%variable%%]content[/isempty]</b><br/>- ' . __("If %%variable%% is empty, content will be displayed (all above variables can be used as %%variable%% or content)", "ls_social_feed") . '</div><div>&nbsp;</div>';
$templates_help .= '<div><b>[truncate %%chars%%]content[/truncate]</b><br/>- ' . __("Truncate content to number of %%chars%%. Truncate will not break words.", "ls_social_feed") . '</div><div>&nbsp;</div>';

$lssf_options_panel->addParagraph( $templates_help );
$lssf_options_panel->CloseTab();

$lssf_options_panel->OpenTab( 'lssf_about' );
$lssf_options_panel->Title( __( 'Shortcodes - help', 'ls_social_feed' ) );
ob_start() ?>
	<div>
		<h3><?php _e( 'Aggregated feed from all supported networks.', 'ls_social_feed' ); ?></h3>
		<pre>[lssf facebook="LadaSoukup" twitter="LadaSoukup" gplus="101145411178741720361" items=5]</pre>
		<div></div>
	</div>
	<div>&nbsp;</div>
	
	<div>
		<h3><?php _e( 'Facebook', 'ls_social_feed' ); ?></h3>
		<pre>[lssf_facebook id="LadaSoukup" items=2]</pre>
		<div></div>
	</div>
	<div>&nbsp;</div>
	
	<div>
		<h3><?php _e( 'Twitter', 'ls_social_feed' ); ?></h3>
		<pre>[lssf_twitter id="LadaSoukup" items=4]</pre>
		<div></div>
	</div>
	<div>&nbsp;</div>
	
	<div>
		<h3><?php _e( 'Google+', 'ls_social_feed' ); ?></h3>
		<pre>[lssf_gplus id="101145411178741720361" items=2]</pre>
		<div></div>
	</div>
	<div>&nbsp;</div>
	
	<hr/>
	
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
$lssf_options_panel->addParagraph( $tab_html );
$lssf_options_panel->CloseTab();


$lssf_options_panel->OpenTab( 'lssf_importexport' );
$lssf_options_panel->Title( __( 'Import/Export settings', 'ls_social_feed' ) );
$lssf_options_panel->addImportExport();
$lssf_options_panel->CloseTab();
$lssf_options_panel->CloseTab();

?>