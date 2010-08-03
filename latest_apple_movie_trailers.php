<?php
/*
Plugin Name: Latest Apple Movie Trailers
Plugin URI: http://www.sebs-studio.com/freebies/wp-plugins
Description: Displays latest movie trailers from http://trailers.apple.com/ along with poster and description.
Author URI: http://www.sebs-studio.com/
Author: Sebs Studio (Sebastien)
Version: 1.0
Tags: Posters, Trailers, Movies, Promotion, Entertainment, RSS, Feed, Apple, Films
License: GPL3
*/

function display_apple_movie_trailers(){
	require_once(ABSPATH.WPINC.'/rss.php');
	$rss = fetch_rss("http://trailers.apple.com/trailers/home/rss/newtrailers.rss"); /* Fetches the apple movie trailer feed. */
	$content = '<table width="600px" cellpadding="2px" cellspacing="2px">';
	if(!empty($rss)){
		$items = array_slice($rss->items, 0, 10); /* Displays the amount of movie trailers added recently. 10 is Default! */
		foreach($items as $item){
			$content .= '<tr><td valign="top">';
			$video_link = clean_url($item['link'], $protocolls = null, 'display');
			$content .= '<a target="_blank" href="'.clean_url($item['link'], $protocolls=null, 'display').'" title="'.htmlentities($item['title']).'"><img class="latest_yt" src="'.$video_link.'images/poster.jpg" border="0" /></a></td>'; /* Displays Movie Poster. */
			/* Default by Apple's Feed sets poster as a smaller image.*/
			//$content .= '<a target="_blank" href="'.clean_url($item['link'], $protocolls=null, 'display').'" title="'.htmlentities($item['title']).'"><img class="latest_yt" src="'.$video_link.'images/poster.jpg"  width="65" height="97" border="0" /></a></td>'; /* Displays Movie Poster. */
			$content .= '<td>&nbsp;</td>';
			$content .= '<td valign="top"><a target="_blank" href="'.clean_url($item['link'], $protocolls=null, 'display').'" title="'.htmlentities($item['title']).'"><span style="font-size:16px; font-weight:900; text-decoration:underline;">'.htmlentities($item['title']).'</span></a><br />';
			$content .= 'Added: <em><b>'.date('M j, Y', strtotime($item['pubdate'])).'</b></em><br />'; /* Date of movie trailer added. */
			$content .= '<span style="font-size:12px;">'.$item['description'].'</span>';
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
add_shortcode('apple_trailers', 'display_apple_movie_trailers');

?>