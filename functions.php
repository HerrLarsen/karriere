<?php 

// Shortcode: [jobs_single]
// Dieser Shortcode gibt die Inhalte des ACF-Flexible Content Fields aus.
// Verwendung: Shortcode auf der Single-Page-Template der Jobs ausgeben.
// z.b echo do_shortcode("[jobs_single]"); 

function jobs_single_shortcode($atts) {

	// Attributes

	$atts = shortcode_atts(
		array(
			'post_id' => get_the_ID(),
		),
		$atts,
		'jobs_single_shortcode'
	);

	// Get ACF Flexible Content Field value

	$flexible_content = get_field('job_content', $atts['post_id']);

	// Check if the flexible content field has value

	if ($flexible_content) {

		$output = '<div class="jobs-single__wrapper">';

		// Loop through flexible content layouts

		foreach ($flexible_content as $layout) {

			 $output .= '<div class="jobs-single__layout">';

			switch ($layout['acf_fc_layout']) {

				case 'content_layout_headline':
					$output .= '<div class="jobs-single__headline">';
					$output .= '<h3>' .$layout['headline'] . '</h3>';
					$output .= '</div>';
					break;

				case 'content_layout_text':
					$output .= '<div class="jobs-single__text">';
					$output .=  $layout['text'] ;
					$output .= '</div>';
					break;

				case 'content_layout_image':
					$output .= '<div class="jobs-single__image">';
					$image = wp_get_attachment_image($layout['image'], 'large');
					$output .= '<div class="acf-image">' . $image . '</div>';
					$output .= '</div>';
					break;

			}

			$output .= '</div>';

		}

		$output .= '</div>';

	} else {

		$output = '<p>Keine Inhalte definiert.</p>';

	}

	return $output;
}

add_shortcode('jobs_single', 'jobs_single_shortcode');


// Shortcode: [jobs_listing]
// Dieser Shortcode gibt die Inhalte des ACF-Repeater Fields der Options-Seite aus.
// Verwendung: Shortcode auf einer beliebigen Seite.
// z.b echo do_shortcode("[jobs_listing]"); 

// TODO FEATURES: Alternativer Titel, Bilder deaktivieren

function jobs_listing_shortcode($atts) {

	// Get ACF Repeater Field value from Options page
	$repeater_field = get_field('job_blocks', 'options');

	// Check if the repeater field has value
	if ($repeater_field) {

		$output = '<div class="jobs-listing__wrapper">';

		// Loop through repeater field rows
		foreach ($repeater_field as $row) {

			$headline = $row['headline'];
			$subline = $row['subline'];
			$jobs = $row['jobs'];
			$show_images = $row['show_images'];

			$output .= '<div class="jobs-listing__section">';

			if ($headline) { 
				$output .= '<div class="jobs-listing__headline">';
				$output .= '<h3>' . $headline . '</h3>';
				$output .= '</div>';
			}

			if ($subline) { 
				$output .= '<div class="jobs-listing__subline">';
				$output .= '<p>' . $subline . '</p>';
				$output .= '</div>';
			}

			if ($jobs) {

				$output .= '<div class="jobs-listing__items">';

				foreach ($jobs as $job) {

					$job_status = get_post_status($job);

					if ($job_status != 'draft') {

						$output .= '<div class="jobs-listing__item">';

						if ($show_images) {

							$output .= '<div class="jobs-listing__image">';

							$thumbnail = get_the_post_thumbnail($job, 'small');

							if ($thumbnail) {
								$output .= $thumbnail;
							} else {
								$output .= '<div class="jobs-listing__image--placeholder"></div>';
							}

							$output .= '</div>';

						}

						$output .= '<div class="jobs-listing__title">';

						if ( get_field('job_headline',$job) ) {
							$job_headline = get_field('job_headline',$job);
						}  else {
							$job_headline = get_the_title($job);
						}  
						$output .= '<a href="'.get_the_permalink($job).'">' . $job_headline . '</a>';
						
						$output .= '</div>';
						
						$output .=' </div>';
					}

					$output .= '</div>';

				}

			}

			$output .= '</div>';
		}

		$output .= '</div>';
	} else {
		$output = '<p>Kein Inhalt definiert.</p>';
	}

	return $output;
}

add_shortcode('jobs_listing', 'jobs_listing_shortcode');
