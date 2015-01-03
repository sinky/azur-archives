<?php  
/* 
Plugin Name: Azur Archives
Plugin URI: http://my-azur.de
Version: 0.1
Author: Marco Krage
Author URI: http://my-azur.de
Description: Fancy Archives per Shortcode on any Page
*/
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function azur_get_archives() {
	global $wpdb, $wp_locale;

	$defaults = array(
		'type' => 'monthly', 'limit' => '',
		'format' => 'html', 'before' => '',
		'after' => '', 'show_post_count' => false,
		'order' => 'DESC',
	);
  
  $args = '';
	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	//filters
	$where = apply_filters( 'getarchives_where', "WHERE post_type = 'post' AND post_status = 'publish'", $r );
	$join = apply_filters( 'getarchives_join', '', $r );

	$output = '';

  $orderby = 'post_date DESC ';
  $query = "SELECT * FROM $wpdb->posts $join $where ORDER BY $orderby $limit";
  $key = md5($query);
  $cache = wp_cache_get( 'wp_get_archives' , 'general');
  if ( !isset( $cache[ $key ] ) ) {
    $arcresults = $wpdb->get_results($query);
    $cache[ $key ] = $arcresults;
    wp_cache_set( 'wp_get_archives', $cache, 'general' );
  } else {
    $arcresults = $cache[ $key ];
  }
  if ( $arcresults ) {
    $count = 0;
    foreach ( (array) $arcresults as $arcresult ) {
      if ( $arcresult->post_date != '0000-00-00 00:00:00' ) {
        $year = mysql2date('Y', $arcresult->post_date);
        if ($year != $check) {
          $check = $year; // save month to check variable     
          if($count > 0) {$output .= "</ul>\n"; }
          $count++;
          $output .= "<h2>" . $year . "</h2>\n";   
          $output .= "<ul>\n";
        }   
        $url  = get_permalink( $arcresult );
        if ( $arcresult->post_title )
          $text = strip_tags( apply_filters( 'the_title', $arcresult->post_title, $arcresult->ID ) );
        else
          $text = $arcresult->ID;
        $output .= '<li>';
        $output .= '<time datetime="'.mysql2date('Y-m-d', $arcresult->post_date).'">'.mysql2date('d. F Y', $arcresult->post_date).'</time><div class="turncate">';
        $output .= '<a href="'.$url.'" title="'.$text.'">'.$text.'</a>';
        $output .= '</div>';
        $output .= '</li>';
      }
    }
  }
  $output .= "</ul>\n";

  $html = "<div class='archive'>\n";
  $html .= $output;
  $html .= "</div>\n";
  return $html;
}

function azur_archives($content) {
  $content .= azur_get_archives();
  return $content;
}
add_shortcode( 'azur-archives', 'azur_archives' );
