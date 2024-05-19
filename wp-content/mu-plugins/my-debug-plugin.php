<?php

// Define a function to log array values
function my_plugin_log_array( $array ) {
    if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
        error_log( print_r( $array, true ) );
    }
}
