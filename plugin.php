<?php
/*
Plugin Name: Debug Bar - MailHog support
Plugin URI: https://github.com/ArtOfWP/debug-bar-mailhog
Description: Adds MailHog Support to Debug Bar
Version: 1.0
Author: Andreas Nurbo
Author URI: http://www.artofwp.com
License: GPLv2
*/
/**
 *    Debug Bar MailHog - Adds MailHog Support to Debug Bar
 *  Copyright (C) 2016  Andreas Nurbo
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 **/

/**
 * Show admin notice & de-activate itself if debug-bar plugin not active.
 */
add_action( 'admin_init', 'dbmhog_has_parent_plugin' );

if ( ! function_exists( 'dbmhog_has_parent_plugin' ) ) {
    /**
     * Check for parent plugin.
     */
    function dbmhog_has_parent_plugin() {
        if ( is_admin() && ( ! class_exists( 'Debug_Bar' ) && current_user_can( 'activate_plugins' ) ) ) {
            add_action( 'admin_notices', create_function( null, 'echo \'<div class="error"><p>\' . sprintf( __( \'Activation failed: Debug Bar must be activated to use the <strong>Debug Bar MailHog</strong> Plugin. %sVisit your plugins page to activate.\', \'debug-bar-mailhog\' ), \'<a href="\' . admin_url( \'plugins.php#debug-bar\' ) . \'">\' ) . \'</a></p></div>\';' ) );

            deactivate_plugins( plugin_basename( __FILE__ ) );
            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }
        }
    }
}

require_once 'src/class-debug-bar-mailhog.php';
new Debug_Bar_MailHog();