<?php

add_action( 'wp_ajax_nopriv_serversidefunction', 'serversidefunction' );
add_action( 'wp_ajax_serversidefunction', 'serversidefunction' );

function serversidefunction() {
    $responseData = array("voll cooler AJAX Kram!!!");
    echo json_encode($responseData);
}
