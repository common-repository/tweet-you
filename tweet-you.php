<?php
/*
Plugin Name: Tweet You
Plugin URI: http://benjaminsterling.com/wordpress-plugins/wordpress-tweet-you-plugin/
Description: This plugin adds more buttons to the non-visual editor view for creating/editing posts/pages
Version: 0.2
Author: Benjamin Sterling
Author URI: http://kenzomedia.com
		base of: http://tweet.seaofclouds.com/
License: 

	Copyright 2011  Benjamin Sterling  (email : benjamin.sterling@kenzomedia.com)

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

/**
 *	Load WP-Config File If This File Is Called Directly
 */
if (!function_exists('add_action')) {
	require_once('../../../wp-config.php');
} //  end : if (!function_exists('add_action'))

function widget_tweet_you( $args ){
	extract( $args );
	$options = get_option('widget_tweet_you');
	echo $before_widget;
	echo $before_title . $options['title'] . $after_title;
	echo '<div id="tweet-you"></div> ';
	echo $after_widget;
	
	$blogsurl = get_bloginfo('wpurl') . '/wp-content/plugins/' . basename(dirname(__FILE__));
	if( $options['jquery'] == 1 ){
		echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>';
	echo '<script>window.jQuery || document.write(\'<script src="js/libs/jquery-1.5.1.min.js">\x3C/script>\')</script>';
	}
	echo '<script language="javascript" src="'.$blogsurl.'/jquery.tweet.js" type="text/javascript"></script>';
	
	echo 	'<script type="text/javascript">'.
				'jQuery(window).load(function(){'.
					'jQuery("#tweet-you").tweet({'.
					  'username: "'.$options['user'].'",'.
					  'count: '.$options['count'].','.
					  'intro_text: "'.$options['intro_text'].'",'.
					  'outro_text: "'.$options['outro_text'].'",'.
					  'join_text:  "'.$options['jointext'].'"'.
					'});'.
				'});'.
			'</script>';
}

function controls_tweet_you(){
	$options = $newoptions = get_option('widget_tweet_you');
	if ( $_POST['tweet-you-submit'] ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST['tweet-you-title']));
		$newoptions['user'] = strip_tags(stripslashes($_POST['tweet-you-user']));
		$newoptions['jointext'] = strip_tags(stripslashes($_POST['tweet-you-jointext']));
		$newoptions['count'] = strip_tags(stripslashes($_POST['tweet-you-count']));
		$newoptions['intro_text'] = strip_tags(stripslashes($_POST['tweet-you-intro_text']));
		$newoptions['outro_text'] = strip_tags(stripslashes($_POST['tweet-you-outro_text']));
		$newoptions['jquery']  = strip_tags(stripslashes($_POST['tweet-you-jquery']));
		
		if( empty($newoptions['count']) || !is_numeric($newoptions['count']) ){
			$newoptions['count'] = 5;
		}
	}
	
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_tweet_you', $options);
	}

	$title = $options['title'];
	$user = $options['user'];
	$jointext = $options['jointext'];
	$count = $options['count'];
	$intro_text = $options['intro_text'];
	$outro_text = $options['outro_text'];
	$jquery  = $options['jquery'];
?>
		<p><label for="tweet-you-title">
			<?php _e('Title:'); ?> 
			<input class="widefat" id="tweet-you-title" name="tweet-you-title" type="text" value="<?php echo $title; ?>" />
		</label></p>
		<p><label for="tweet-you-user">
			<?php _e('User:'); ?> 
			<input class="widefat" id="tweet-you-user" name="tweet-you-user" type="text" value="<?php echo $user; ?>" />
		</label></p>
		<p><label for="tweet-you-jointext">
			<?php _e('Join Text:'); ?> 
			<input class="widefat" id="tweet-you-jointext" name="tweet-you-jointext" type="text" value="<?php echo $jointext; ?>" />
		</label></p>
		<p><label for="tweet-you-intro_text">
			<?php _e('Intro Text:'); ?> 
			<input class="widefat" id="tweet-you-intro_text" name="tweet-you-intro_text" type="text" value="<?php echo $intro_text; ?>" />
		</label></p>
		<p><label for="tweet-you-outro_text">
			<?php _e('After Text:'); ?> 
			<input class="widefat" id="tweet-you-outro_text" name="tweet-you-outro_text" type="text" value="<?php echo $outro_text; ?>" />
		</label></p>
		<p><label for="tweet-you-count">
			<?php _e('Count:'); ?> 
			<input class="widefat" id="tweet-you-count" name="tweet-you-count" type="text" value="<?php echo $count; ?>" />
		</label></p>
		<p><label for="tweet-you-jquery">
			<?php _e('jQuery is need, check if you don\'t have jQuery already in your theme:'); ?> 
			<input class="" id="tweet-you-jquery" <?php echo ($jquery == 1) ? 'checked="checked"' : '';?>  name="tweet-you-jquery" type="checkbox" value="1" />
		</label></p>

		<input type="hidden" id="tweet-you-submit" name="tweet-you-submit" value="1" />
<?php
}

function tweet_you_init(){
	add_option('widget_tweet_you');
	update_option($option_name, array('title'=>'Tweeter','user'=>'','count'=>'5','jointext'=>'','intro_text'=>'','outro_text'=>'','jquery'=>''));
	$widget_ops = array('classname' => 'widget_tweet_you', 'description' => __( "Bring your Tweeter in with Tween") );
	register_sidebar_widget(__('Tweet You'), 'widget_tweet_you', $widget_ops);
	register_widget_control(__('Tweet You'), 'controls_tweet_you');
}
add_action("plugins_loaded", "tweet_you_init");
?>