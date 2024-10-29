<?php

defined( 'ABSPATH' ) || exit;

/**
* Utility functions.
*/

function jn_admin_tailor_get_pattern_url( $name ) {
  return JN_ADMIN_TAILOR_URL . '/patterns/' . $name;
}

function jn_admin_tailor_get_pattern_name( $name ) {
  $info                   = pathinfo( $name );
  $name_without_extension = $info['filename'];
  $name_with_spaces       = str_replace( '-', ' ', $name_without_extension );
  $formatted_name         = ucwords( $name_with_spaces );

  return $formatted_name;
}
