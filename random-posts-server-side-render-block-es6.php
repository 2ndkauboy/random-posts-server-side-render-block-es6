<?php
/**
 * Plugin Name:       Random Posts Server Side Render Block ES6
 * Description:       Example static block scaffolded with Create Block tool.
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       random-posts-server-side-render-block-es6
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_rpssrb_block_init() {
	register_block_type(
		__DIR__ . '/build',
		[
			'render_callback' => 'rpssrb_render_callback',
		]
	);
}
add_action( 'init', 'create_block_rpssrb_block_init' );

/**
 * Render callback for the block.
 *
 * @return string
 */
function rpssrb_render_callback( $atts ) {
	$args = [
		'post_type'      => 'post',
		'orderby'        => 'rand',
		'posts_per_page' => (int) $atts['postsToShow'],
	];

	$query = new WP_Query( $args );

	$output = '';
	if ( $query->have_posts() ) {
		$output .= '<ul>';
		while ( $query->have_posts() ) {
			$query->the_post();
			$output .= sprintf(
				'<li><a href=%s>%s</a></li>',
				get_permalink(),
				get_the_title()
			);
		}
		$output .= '</ul>';
	}

	return $output;
}

/**
 * Bonus: make the callback usable as a traditional shortcode.
 */
add_shortcode( 'rpssrb_random_posts', 'rpssrb_render_callback' );
