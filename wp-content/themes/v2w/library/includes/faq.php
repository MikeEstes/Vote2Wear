<?php

add_action('init', 'register_faq_post_type');

function register_faq_post_type() {

	register_post_type('faq', array(
		'label' => 'FAQs',
		'description' => 'Frequently Asked Questions',
		'public' => true,
		'supports' => array('title', 'editor', 'page-attributes')
	));

};