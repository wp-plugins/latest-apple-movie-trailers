<?php
/*
Plugin Name: Latest Apple Movie Trailers
Plugin URI: http://www.sebs-studio.com/freebies/wp-plugins
Description: Displays latest movie trailers from http://trailers.apple.com/ along with poster and description.
Author URI: http://www.sebs-studio.com/
Author: Sebs Studio (Sebastien)
Version: 1.1
Tags: Posters, Trailers, Movies, Promotion, Entertainment, RSS, Feed, Apple, Films
License: GPL3
*/

function display_apple_movie_trailers(){
	require_once(ABSPATH.WPINC.'/rss.php');
	$rss = fetch_rss("http://trailers.apple.com/trailers/home/rss/newtrailers.rss"); /* Fetches the apple movie trailer feed. */
	if(get_option('lamt_poster_size') == 'poster-xlarge.jpg'){ $content = '<table width="800px"'; }else{ $content = '<table width="600px"'; }
	$content .= ' cellpadding="2px" cellspacing="2px">';
	if(!empty($rss)){
		$items = array_slice($rss->items, 0, get_option('lamt_display_many')); /* Displays the amount of movie trailers added recently. 10 is Default! */
		foreach($items as $item){
			$content .= '<tr><td valign="top">';
			$video_link = clean_url($item['link'], $protocolls = null, 'display');
			$content .= '<a target="_blank" href="'.clean_url($item['link'], $protocolls=null, 'display').'" title="'.htmlentities($item['title']).'"><img class="latest_yt" src="'.$video_link.'images/'.get_option('lamt_poster_size').'" border="0" /></a></td>'; /* Displays Movie Poster. */
			/* Default by Apple's Feed sets poster as a smaller image.*/
			$content .= '<td>&nbsp;</td>';
			$content .= '<td valign="top"><a target="_blank" href="'.clean_url($item['link'], $protocolls=null, 'display').'" title="'.htmlentities($item['title']).'"><span style="font-size:16px; font-weight:900; text-decoration:underline;">'.htmlentities($item['title']).'</span></a><br />';
			$content .= 'Added: <em><b>'.date('M j, Y', strtotime($item['pubdate'])).'</b></em><br />'; /* Date of movie trailer added. */
			$content .= '<span style="font-size:12px;">'.$item['description'].'</span><br /><br />';
			$content .= 'Share: <ul id="share">';
			$content .= '<li id="share-facebook"><a href="http://www.facebook.com/share.php?u='.clean_url($item['link'], $protocolls=null, 'display').'" target="_block" title="Share this on Facebook">Facebook</a></li>';
			$content .= '<li id="share-twitter"><a href="http://twitter.com/home?status=See the trailer for &quot;'.htmlentities($item['title']).'&quot; '.clean_url($item['link'], $protocolls=null, 'display').'" target="_block" title="Share this on Twitter">Twitter</a></li>';
			$content .= "</ul>\n";
			$content .= "</td></tr>\n";
		}
	}
	else{
		$content = "<tr>\n";
		$content .= "<td>Apple Movie Trailers Feed not found!</td>\n";
		$content .= "<td>Please try again later</td>\n";
		$content .= "</tr>\n";
	}
	$content .= '</table>';
	return $content; /* Displays the Apple Movie Trailers Feed. */
}
/* Add [apple_trailers] to your post or page to display latest movie trailers. */
if(function_exists('display_apple_movie_trailers')){
	/* Only works if plugin is active. */
	add_shortcode('apple_trailers', 'display_apple_movie_trailers');
}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'apple_latest_trailers_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'apple_latest_trailers_remove' );

function apple_latest_trailers_install() {
	/* Creates new database field */
	add_option("lamt_display_many", '10', '', 'yes');
	add_option("lamt_poster_size", 'poster.jpg', '', 'yes');
}

function apple_latest_trailers_remove() {
	/* Deletes the database field */
	delete_option('lamt_display_many');
	delete_option('lamt_poster_size');
}

if(is_admin()){
	function apple_latest_trailers_menu(){
		add_options_page('Latest Apple Movie Trailers', 'Latest Apple Movie Trailers', 'manage_options', __FILE__, 'apple_latest_trailers_settings');
	}
	add_action('admin_menu', 'apple_latest_trailers_menu');
}

function apple_latest_trailers_settings(){
	$display_many = get_option('lamt_display_many');
	$poster_size = get_option('lamt_poster_size');
?>
<div class="wrap">
<h2>Latest Apple Movie Trailers</h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<table class="form-table">
<tr valign="top">
<th scope="row">Display How Many?</th>
<td>
<select name="lamt_display_many" size="1">
<?php
for($show=1; $show<=20; $show++){
	echo '<option value="'.$show.'"';
	if($display_many == $show){ echo ' selected="selected"'; }
	echo '>'.$show.'</option>';
}
?>
</select>
</td>
</tr> 
<tr valign="top">
<th scope="row">Poster Size:</th>
<td>
<select name="lamt_poster_size" size="1">
<option value="poster.jpg"<?php if($poster_size == 'poster.jpg'){ echo ' selected="selected"'; } ?>>Normal</option>
<option value="poster-large.jpg"<?php if($poster_size == 'poster-large.jpg'){ echo ' selected="selected"'; } ?>>Large</option>
<option value="poster-xlarge.jpg"<?php if($poster_size == 'poster-xlarge.jpg'){ echo ' selected="selected"'; } ?>>Extra Large</option>
</select>
</td>
</tr>

</table>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="lamt_display_many, lamt_poster_size" />
<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
</form>
<h3>Preview</h3>
<?php echo display_apple_movie_trailers(); ?>
</div>
<?php
}

?>