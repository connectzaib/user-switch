<?php
/*
Plugin Name: User Switch Form
Description: Gives Select Field with user's list and login button to login to the selected user without password.
Version: 1.0
Author: Zaib Makda
Author URI: https://github.com/connectzaib
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Register JavaScript and CSS files
function zb_user_switch_enqueue_scripts() {
    wp_register_script( 'user-switch', plugin_dir_url( __FILE__ ) . 'user-switch.js', array( 'jquery' ), '1.0', true );
    wp_localize_script('user-switch','UOBJ',array('adminurl'=> admin_url()));
    // wp_register_style( 'user-switch', plugin_dir_url( __FILE__ ) . 'user-switch.css', array(), '1.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'zb_user_switch_enqueue_scripts' );

add_shortcode( 'zb_user_switch_form', 'zb_user_switch_form' );
function zb_user_switch_form() {
    // Enqueue JavaScript and CSS files on the page where the shortcode is used
    wp_enqueue_script( 'user-switch' );
    // wp_enqueue_style( 'user-switch' );
    
    $users = get_users(['orderby' => 'user_login','fields'=>['ID','user_login'] ]);
    if(empty($users)){
        return "No usr found or error while getting user's";
    }
    $html = "<select id='zb-user-select'>";
    foreach ($users as $userObj) {
        $checked = "";
        if(is_user_logged_in()){
        $user_id = get_current_user_id();
        if($user_id){
            $checked = selected($user_id,$userObj->id,false);
        }
        }
        $html .= "<option value='$userObj->id' $checked>$userObj->user_login</option>";
    }
    $html .= "</select><br/>";
    $html .= "<button id='zb-user-login'>Log In</button>";
    return $html;
}


add_action("wp_ajax_zb_switch_user","zaib_handle_ajax");
add_action("wp_ajax_nopriv_zb_switch_user","zaib_handle_ajax");//for the users that is not logged in.
function zaib_handle_ajax() {
  $user_id = $_POST['user_id']  ;
  $user = get_user_by( 'id', $user_id ); 
  if( $user ) {
    wp_set_auth_cookie( $user_id );
    $curr_user=  new WP_User( $user_id);
    // do_action( 'wp_login', $user->user_login );//Will conflict with woocommerce
    do_action( 'wp_login', $user->user_login,$curr_user );
    
  }
  wp_send_json_success();
}


