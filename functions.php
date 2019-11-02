// This code create custom post type
function easytuts_eventposts_post() {
  $labels = array(
    'name'               => _x( 'Eventposts', 'post type general name' ),
    'singular_name'      => _x( 'Eventpost', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'eventpost' ),
    'add_new_item'       => __( 'Add New Eventpost' ),
    'edit_item'          => __( 'Edit Eventpost' ),
    'new_item'           => __( 'New Eventpost' ),
    'all_items'          => __( 'All Eventposts' ),
    'view_item'          => __( 'View Eventpost' ),
    'search_items'       => __( 'Search Eventposts' ),
    'not_found'          => __( 'No eventpost found' ),
    'not_found_in_trash' => __( 'No eventpost found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'EventPosts'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds eventposts and eventpost specific data',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor','taxonomies', 'thumbnail', 'excerpt', 'comments' ),
    'has_archive'   => true,
  );
  register_post_type( 'eventposts', $args ); 
}
add_action( 'init', 'easytuts_eventposts_post' );
// end--This code create custom post type


// This code create Categories under custom post type
function easytuts_taxonomies_eventposts_posttype() {
  $labels = array(
    'name'              => _x( 'Eventposts Categories', 'taxonomy general name' ),
    'singular_name'     => _x( 'Eventpost Category', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Eventpost Categories' ),
    'all_items'         => __( 'All Eventposts Categories' ),
    'parent_item'       => __( 'Parent Eventpost Category' ),
    'parent_item_colon' => __( 'Parent Eventpost Category:' ),
    'edit_item'         => __( 'Edit Eventpost Category' ), 
    'update_item'       => __( 'Update Eventpost Category' ),
    'add_new_item'      => __( 'Add New Eventpost Category' ),
    'new_item_name'     => __( 'New Eventpost Category' ),
    'menu_name'         => __( 'Eventpost Categories' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'leagues', 'eventposts', $args );
}
add_action( 'init', 'easytuts_taxonomies_eventposts_posttype', 0 );
// end--This code create Categories under custom post type

// This code check is there same title exist and insert post into custom post type. Also call Generate_Featured_Image function
function easytuts_taxonomies_eventposts() {

    $postTitle = 'post 33333';
    global $wpdb;

    $query = $wpdb->prepare(
        'SELECT ID FROM ' . $wpdb->posts . '
        WHERE post_title = %s
        AND post_type = \'eventposts\'',
        $postTitle
    );
    $wpdb->query( $query );

    if ( $wpdb->num_rows ) {
        $post_id = $wpdb->get_var( $query );
        $meta = get_post_meta( $post_id, 'eventposts', TRUE );
        $meta++;
        update_post_meta( $post_id, 'eventposts', $meta );
    } 

	else{

		$my_post = array(
		     'post_title'   => $postTitle,
		     // 'post_date'    => 12-12-21,
		     'post_content' => 'This is my post content.',
		     'post_status'  => 'publish',
		     'post_type'    => 'eventposts',
		     'post_excerpt' => 'This is my post excerpt.',
		  );

		$post_id = wp_insert_post($my_post);


		Generate_Featured_Image( 'https://www.thenational.ae/image/policy:1.860575:1559198783/AR_1205_Sharjah_Mosque-40.jpg', $post_id );
		// add_post_meta($post_id, fgfdfgdfgdfg, 65464, true);
		// add_post_meta($post_id, 'META-KEY-2', 'META_VALUE-2', true);
		}
}
// end--This code check is there same title exist and insert post into custom post type. Also call Generate_Featured_Image function






// $post_id is Numeric ID... You can also get the ID with:
// wp_insert_post();
//this code Generate_Featured_Image from link
function Generate_Featured_Image( $image_url, $post_id  ){
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    $filename = basename($image_url);
    if(wp_mkdir_p($upload_dir['path']))
      $file = $upload_dir['path'] . '/' . $filename;
    else
      $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
    $res2= set_post_thumbnail( $post_id, $attach_id );
}
//end--this code Generate_Featured_Image from link


// function hcf_register_meta_boxes() {
//     add_meta_box( 'hcf-1', __( 'Custom Field No 1', 'hcf' ), 'hcf_display_callback', 'eventposts' );

//      add_meta_box( 'hcf-2', __( 'Custom Field No 2', 'hcf' ), 'hcf_display_callback2', 'eventposts' );
// }
// add_action( 'add_meta_boxes', 'hcf_register_meta_boxes' );

// /**
//  * Meta box display callback.
//  *
//  * @param WP_Post $post Current post object.
//  */
// function hcf_display_callback( $post ) {
//     echo "Hello Custom Field 1";
// }
         
// function hcf_display_callback2( $post ) {
//     echo "Hello Custom Field 2";
// }


// function echo_meta() {
// $my_meta = get_post_meta( $post->ID, 'hcf', true ); echo $my_meta["hcf"];
// }
// echo_meta();


//this code create an admin option to insert post in admin panel tool menu.

add_action( 'admin_action_wpse10500', 'wpse10500_admin_action' );
function wpse10500_admin_action()
{
    // Do your stuff here
    wp_redirect( $_SERVER['HTTP_REFERER'] );
    exit();
}
add_action( 'admin_menu', 'wpse10500_admin_menu' );
function wpse10500_admin_menu()
{
    add_management_page( 'WPSE 10500 Test page', 'Insert events as post', 'administrator', 'wpse10500', 'wpse10500_do_page' );
}
function wpse10500_do_page()
{
?>
    <form method="POST">
        <input type="hidden" name="action" value="wpse10500" />
        <!-- <input type="text" name="action2"/> -->
			<select name="trademarktm" >
			<option value='insert_yes'>YES</option>
			<option value="insert_no">NO</option>
			</select>
        <input type="submit" value="Do it!" />
    </form>

<?php
if($_POST['trademarktm'] == 'insert_yes') { 
	 easytuts_taxonomies_eventposts();
}
?>

<?php
}
//end--this code create an admin option to insert post in admin panel tool menu.
