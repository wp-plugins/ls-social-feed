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
		'lssf_about' => __( 'About this plugin', 'ls_social_feed' ),
		'lssf_importexport' => __( 'Import/Export settings', 'ls_social_feed' )
	)
));

$lssf_options_panel->OpenTab( 'lssf_opt_fb' );
$lssf_options_panel->Title( __("Facebook", "ls_social_feed") );
$lssf_options_panel->addParagraph( __("You need an active Facebook App to fetch Facebook feed.", "ls_social_feed") );
$lssf_options_panel->addText( 'fb_appid', array( 'name'=> __( 'App ID', 'ls_social_feed' ), 'std'=> 'text', 'desc' => '' ) );
$lssf_options_panel->addText( 'fb_appsecret', array( 'name'=> __( 'App Secret', 'ls_social_feed' ), 'std'=> 'text', 'desc' => '' ) );
$lssf_options_panel->CloseTab();

$lssf_options_panel->OpenTab( 'lssf_opt_gplus' );
$lssf_options_panel->Title( __("Google+", "ls_social_feed") );
$lssf_options_panel->addParagraph( __("You need an active Facebook App to fetch Facebook feed.", "ls_social_feed") );
$lssf_options_panel->addText( 'gplus_key', array( 'name'=> __( 'Google+ API Key', 'ls_social_feed' ), 'std'=> 'text', 'desc' => '' ) );
$lssf_options_panel->CloseTab();


$lssf_options_panel->OpenTab( 'lssf_about' );
$lssf_options_panel->Title( __( 'About this plugin', 'ls_social_feed' ) );
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
$lssf_options_panel->addParagraph( $tab_html );
$lssf_options_panel->CloseTab();


$lssf_options_panel->OpenTab( 'lssf_importexport' );
$lssf_options_panel->Title( __( 'Import/Export settings', 'ls_social_feed' ) );
$lssf_options_panel->addImportExport();
$lssf_options_panel->CloseTab();
$lssf_options_panel->CloseTab();

?>