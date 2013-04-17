<?php
/*
Plugin Name: LS Social Feed
Plugin URI: http://git.ladasoukup.cz/ls_social_feed
Description: Shortcodes to display social feeds from Facebook, Google+ and Twitter. You can also aggregate these social networks to one feed.
Version: 0.5
Author: Ladislav Soukup (ladislav.soukup@gmail.com)
Author URI: http://www.ladasoukup.cz/
Author Email: ladislav.soukup@gmail.com
License:

  Copyright 2013 Ladislav Soukup (ladislav.soukup@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

class ls_social_feed {
	private $plugin_path;
    private $wpsf;
	private $CFG;
	private $update_warning = false;
	private $gmtOffset;
	private $cache_expire = 7200;
	private $debug = false;
	 
	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/
	
	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {
		$this->plugin_path = plugin_dir_path( __FILE__ );
		
		// Load plugin text domain
		load_plugin_textdomain( 'ls_social_feed', false, $this->plugin_path . '/lang' );
		
		/* admin options */
		require_once( $this->plugin_path . '__admin.php' );
		
		/* load CFG */
		$this->CFG = get_option( 'ls_social_feed' );
		$this->gmtOffset = get_option('gmt_offset') * 3600;
		// if ( $this->debug ) { echo '<hr/>'; echo $url; echo '<br/><pre>'; print_r( $this->CFG ); echo '</pre><hr/>'; }
		
		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		// register_activation_hook( __FILE__, array( $this, 'activate' ) );
		// register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		// register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );
		
		
	} // end constructor
	
	/**
	 * Fired when the plugin is activated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function activate( $network_wide ) {
		
	} // end activate
	
	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function deactivate( $network_wide ) {
		
	} // end deactivate
	
	/**
	 * Fired when the plugin is uninstalled.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function uninstall( $network_wide ) {
		
	} // end uninstall
	
	
	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/
	
	public function getRemoteUrl( $url, $expire = 7200, $skipCache = false ) {
		$url = esc_url_raw( $url );
		$cacheID = 'lssf_' . md5( $url );
		$cacheIDb = 'lssfb_' . md5( $url );
		
		$data = get_transient( $cacheID );
		if ( $skipCache === true ) $data = false;
		if ( $this->debug ) $data = false;
		
		if ( $data === false ) {
			$response = wp_remote_get( $url, array( 'User-Agent' => get_bloginfo('name'), 'sslverify' => false ) );
			if ( $this->debug ) { echo '<hr/>'; echo $url; echo '<br/><pre>'; print_r( $response ); echo '</pre><hr/>'; }
			
			$response_code = wp_remote_retrieve_response_code( $response );
			$response_body = '';
			
			if ( $response_code == 200 ) {
				$response_body = wp_remote_retrieve_body( $response );
				$data = array(
					'code' => $response_code,
					'body' => $response_body,
					'_cachedAt' => date( "Y-m-d H:i:s", time() + $this->gmtOffset ),
					'_cacheID' => $cacheID
				);
			} else {
				$expire = 300;
				$data = get_transient( $cacheIDb ); /* use backup */
			}
			
			set_transient( $cacheID, $data, $expire ); /* store fetched data */
			
			if ( $response_code == 200 ) {
				set_transient( $cacheIDb , $data, ( $expire * 12 ) ); /* store backup - only if data are fetched correctly */
			}
		}
		return( $data );
	}
	
	
	/* FACEBOOK feed */
	public function getSocialFeed_facebook($uid, $items) {
		$uid = trim( urlencode( $uid ) );
		
		$items = absint( $items );
		if ( $items > 50 ) $items = 50;
		if ( $items < 1 ) { $items = 1; }
		
		$data = array( '_type' => 'facebook', 'user' => false, 'feed' => false );
		$FB_token = $this->getFacebookAppToken();
		
		$url = 'https://graph.facebook.com/'. $uid .'?access_token=' . $FB_token;
		$response = $this->getRemoteUrl( $url, $this->cache_expire, false );
		$json = json_decode( $response['body'], true );  //print_r($json);
		$data['user'] = array(
			'title' => esc_html(strip_tags( $json['name'] )),
			'photo' => 'http://graph.facebook.com/'. $uid .'/picture?type=large',
			'about' => esc_html(strip_tags( $json['about'] ))
		);
		
		
		$url = 'https://graph.facebook.com/'. $uid .'/feed/?access_token=' . $FB_token . '&limit=' . $items;
		$response = $this->getRemoteUrl( $url, $this->cache_expire, false );
		$json = json_decode( $response['body'], true );  // print_r($json);
		
		foreach ( (array)$json['data'] as $item ) {
			$attachment = array( 'type' => '', 'image' => '' );
			if ( !empty( $item['picture'] ) ) {
				$attachment['type'] = 'photo';
				$attachment['image'] = $item['picture'];
			}
			
			$data['feed'][] = array(
				'url' => esc_url ( '' . strip_tags( $item['link'] ) ),
				'content' => esc_html(strip_tags( $item['message'] )),
				'date' => gmdate( get_option( 'date_format' ), strtotime( $item['created_time'] ) ),
				'time' => gmdate( get_option( 'time_format' ), strtotime( $item['created_time'] ) ),
				'timestamp' => strtotime( $item['created_time'] ),
				'attachment' => $attachment
			);
		}
		
		return( $data );
	}
	
	public function getFacebookAppToken() {
		$url = "https://graph.facebook.com/oauth/access_token?"
			. "client_id=" . $this->CFG['fb_appid']
			. "&client_secret=" . $this->CFG['fb_appsecret'] 
			. "&grant_type=client_credentials";
		
		$response = $this->getRemoteUrl( $url, $this->cache_expire, false );
		$params = null;
		parse_str( $response['body'], $params );
		
		return( $params['access_token'] );
	}
	
	/* Google plus */
	public function getSocialFeed_gplus($uid, $items) {
		$uid = trim( urlencode( $uid ) );
		$userInfoOK = false;
		
		$items = absint( $items );
		if ( $items > 50 ) $items = 50;
		if ( $items < 1 ) { $items = 1; }
		
		$data = array( '_type' => 'gplus', 'user' => false, 'feed' => false );
		
		$url = 'https://www.googleapis.com/plus/v1/people/'. $uid .'/activities/public?alt=json&maxResults='. $items .'&pp=1&key='.$this->CFG['gplus_key'];
		$response = $this->getRemoteUrl( $url, $this->cache_expire, false );
		$json = json_decode( $response['body'], true );  //print_r($json);
		
		
		foreach ( (array) $json['items'] as $activities_items ) {
			$attachment = array();
			switch ($activities_items['object']['attachments'][0]['objectType']) {
				case 'photo':
					$attachment['type'] = 'photo';
					$attachment['image'] = $activities_items['object']['attachments'][0]['image']['url'];
					break;
				
				case 'article':
					$attachment['type'] = 'article';
					$attachment['image'] = $activities_items['object']['attachments'][0]['image']['url'];
					$attachment['url'] = $activities_items['object']['attachments'][0]['url'];
					break;
				
			}
			
			$data['feed'][] = array(
				'url' => esc_url( strip_tags( $activities_items['url'] ) ),
				'content' => esc_html(strip_tags(str_replace('<br />', ' ', $activities_items['object']['content'] ))),
				'date' => gmdate( get_option( 'date_format' ), strtotime( $activities_items['published'] ) ),
				'time' => gmdate( get_option( 'time_format' ), strtotime( $activities_items['published'] ) ),
				'timestamp' => strtotime( $activities_items['published'] ),
				'attachment' => $attachment
			);
			
			if ($userInfoOK === false) {
				if ($activities_items['actor']['id'] == $gplus_id) {
					$data['user'] = array(
						'title' => esc_html($activities_items['actor']['displayName']),
						'photo' => $activities_items['actor']['image']['url'],
						'slogan' => $activities_items['actor']['tagline']
					);
					$userInfoOK = true;
				}
			}
		}
		
		return( $data );
	}
	
	/* Twitter */
	public function getSocialFeed_twitter($uid, $items) {
		$uid = trim( urlencode( $uid ) );
		$userInfoOK = false;
		
		$items = absint( $items );
		if ( $items > 50 ) $items = 50;
		if ( $items < 1 ) { $items = 1; }
		
		$data = array( '_type' => 'twitter', 'user' => false, 'feed' => false );
		
		$url = 'https://api.twitter.com/1/statuses/user_timeline.json?screen_name='. $uid .'&count='. $items;
		$response = $this->getRemoteUrl( $url, $this->cache_expire, false );
		$json = json_decode( $response['body'], true );  // print_r($json);
		
		foreach ( (array) $json as $item ) {
			$attachment['type'] = 'photo';
			$attachment['image'] = $item['user']['profile_image_url'];
			$data['feed'][] = array(
				'url' => esc_url ( 'http://twitter.com/' . strip_tags( $item['user']['screen_name'] . '/status/' . $item['id_str'] ) ),
				'content' => esc_html(strip_tags( $item['text'] )),
				'date' => gmdate( get_option( 'date_format' ), strtotime( $item['created_at'] ) ),
				'time' => gmdate( get_option( 'time_format' ), strtotime( $item['created_at'] ) ),
				'timestamp' => strtotime( $item['created_at'] ),
				'attachment' => $attachment
			);
			
			if ($userInfoOK === false) {
				if ($activities_items['actor']['id'] == $gplus_id) {
					$data['user'] = array(
						'title' => esc_html(strip_tags( $item['user']['name'] )),
						'photo' => $item['user']['profile_image_url'],
						'slogan' => esc_html(strip_tags( $item['user']['description'] ))
					);
					$userInfoOK = true;
				}
			}
		}
		
		return( $data );
	}
	
	/* Render feed */
	public function renderSocialFeed( $data, $social_network ) {
		$out = '';
		
		if ( is_array( $data['feed'] ) ) {
			foreach ( $data['feed'] as $item ) {
				$class = $social_network;
				if ( $social_network == 'mixed' ) { $class .= ' ' . $item['_type']; }
				
				$out .= '<div class="lsSocialFeedItemContainer '.$class.'">';
				if ( !empty( $item['attachment']['image'] ) ) {
					$out .= '<img class="lsSocialFeedItem_image" src="' . $item['attachment']['image'] . '" />';
				}
				$out .= '<div class="lsSocialFeedItem_content">' . $item['content'] . '</div>';
				$out .= '<div class="lsSocialFeedItem_clear"></div>';
				$out .= '<div class="lsSocialFeedItem_meta">';
				$out .= '<div class="lsSocialFeedItem_network '.$class.'">' . $class . '</div>';
				if ($social_network == 'mixed') { $out .= '<span class="lsSocialFeedItem_author">' . $data['user'][$item['_type']]['title'] . '</span>'; }
				else { $out .= '<span class="lsSocialFeedItem_author">' . $data['user']['title'] . '</span>'; }
				$out .= '<span class="lsSocialFeedItem_date">' . $item['date'] . '</span>';
				$out .= '<span class="lsSocialFeedItem_time">' . $item['time'] . '</span>';
				$out .= '</div>';
				
				if ( !empty( $item['url'] ) ) {
					$out .= '<a class="lsSocialFeedItem_link" href="' . $item['url'] . '" target="_blank">';
					$out .= __( 'read more...', 'ls_social_feed' );
					$out .= '</a>';
				}
				
				$out .= '</div> <!-- // .lsSocialFeedItemContainer -->';
				$out .= '<div class="lsSocialFeedItem_clear"></div>';
				
			}
		}
		
		return $out;
	}
	
} // end class

$ls_social_feed = new ls_social_feed();


/*--------------------------------------------*
 * Shortcodes
 *---------------------------------------------*/
add_shortcode( 'lssf_facebook', 'lssf_sh_facebook' );
function lssf_sh_facebook( $atts ) {
	global $ls_social_feed;
	
	$out = '';
	$data = $ls_social_feed->getSocialFeed_facebook( $atts['id'], $atts['items'] );
	$out = $ls_social_feed->renderSocialFeed( $data, 'facebook' );
	
	return( $out );
}

add_shortcode( 'lssf_gplus', 'lssf_sh_gplus' );
function lssf_sh_gplus( $atts ) {
	global $ls_social_feed;
	
	$out = '';
	$data = $ls_social_feed->getSocialFeed_gplus( $atts['id'], $atts['items'] );
	$out = $ls_social_feed->renderSocialFeed( $data, 'gplus' );
	
	return( $out );
}

add_shortcode( 'lssf_twitter', 'lssf_sh_twitter' );
function lssf_sh_twitter( $atts ) {
	global $ls_social_feed;
	
	$out = '';
	$data = $ls_social_feed->getSocialFeed_twitter( $atts['id'], $atts['items'] );
	$out = $ls_social_feed->renderSocialFeed( $data, 'twitter' );
	
	return( $out );
}



add_shortcode( 'lssf', 'lssf_sh_feed' );
function lssf_sh_feed( $atts ) {
	global $ls_social_feed;
	
	$out = ''; $data = array(); $tmp = array();
	if ( !empty($atts['gplus']) ) { $tmp[] = $ls_social_feed->getSocialFeed_gplus( $atts['gplus'], $atts['items'] ); }
	if ( !empty($atts['twitter']) ) { $tmp[] = $ls_social_feed->getSocialFeed_twitter( $atts['twitter'], $atts['items'] ); }
	if ( !empty($atts['facebook']) ) { $tmp[] = $ls_social_feed->getSocialFeed_facebook( $atts['facebook'], $atts['items'] ); }
	
	foreach( $tmp as $array ) {
		if ( is_array($array) ) {
			foreach ( $array['feed'] as $item ) {
				$item['_type'] = $array['_type'];
				$data['feed'][] = $item;
			}
			$data['user'][$array['_type']] = $array['user'];
		}
	}
	usort( $data['feed'], 'lssf_sh_feed_sort' );
	$data['feed'] = array_slice($data['feed'], 0, $atts['items']);
	
	$out = $ls_social_feed->renderSocialFeed( $data, 'mixed' );
	return($out);
}
function lssf_sh_feed_sort($v1, $v2){
        if ($v1['timestamp'] == $v2['timestamp']) return 0;
        return ($v1['timestamp'] > $v2['timestamp']) ? -1 : 1;
}