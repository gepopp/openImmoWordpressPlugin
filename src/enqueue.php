<?php
add_action('admin_enqueue_scripts', function (){
	wp_enqueue_script( 'axios', 'https://unpkg.com/axios/dist/axios.min.js' );
	wp_enqueue_script( 'qs', 'https://unpkg.com/qs/dist/qs.js' );
});