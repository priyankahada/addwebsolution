<?php
/*
 * This is the child theme for Twenty Twenty theme.
 *
*/
add_action( 'wp_enqueue_scripts', 'twentytwentychild_enqueue_styles' );
function twentytwentychild_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}
/*
 * Write here your own functions
 */


/* Custom Post Type Start Here For Resource Section */

function resource_post_type() {
 
// Set UI labels for Custom Post Type

    $resourcelabels = array(
        'name'                => _x( 'Resource', 'Post Type General Name', 'twentytwentychild' ),
        'singular_name'       => _x( 'Resource', 'Post Type Singular Name', 'twentytwentychild' ),
        'menu_name'           => __( 'Resource', 'twentytwentychild' ),
        'parent_item_colon'   => __( 'Parent Resource', 'twentytwentychild' ),
        'all_items'           => __( 'All Resource', 'twentytwentychild' ),
        'view_item'           => __( 'View Resource', 'twentytwentychild' ),
        'add_new_item'        => __( 'Add New Resource', 'twentytwentychild' ),
        'add_new'             => __( 'Add New', 'twentytwentychild' ),
        'edit_item'           => __( 'Edit Resource', 'twentytwentychild' ),
        'update_item'         => __( 'Update Resource', 'twentytwentychild' ),
        'search_items'        => __( 'Search Resource', 'twentytwentychild' ),
        'not_found'           => __( 'Not Found', 'twentytwentychild' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentytwentychild' ),
    );
     
// Set other options for Custom Post Type
     
    $resourceargs = array(
        'label'               => __( 'Resource', 'twentytwentychild' ),
        'description'         => __( 'Resource and reviews', 'twentytwentychild' ),
        'labels'              => $resourcelabels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),

        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    );
     
    // Registering your Custom Post Type
    
    register_post_type( 'resource', $resourceargs );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'resource_post_type', 0 );


add_action( 'init', 'create_resource_type_discog_taxonomies', 0 );

function create_resource_type_discog_taxonomies()
{
  // Add new Resource Type taxonomy, make it hierarchical 

  $resourcetypelabels = array(
    'name' => _x( 'Resource Type', 'taxonomy general name' ),
    'singular_name' => _x( 'Resource Type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Resource Type' ),
    'popular_items' => __( 'Popular Resource Type' ),
    'all_items' => __( 'All Resource Type' ),
    'parent_item' => __( 'Parent Resource Type' ),
    'parent_item_colon' => __( 'Parent Resource Type:' ),
    'edit_item' => __( 'Edit Resource Type' ),
    'update_item' => __( 'Update Resource Type' ),
    'add_new_item' => __( 'Add New Resource Type' ),
    'new_item_name' => __( 'New Resource Type' ),
  );
  register_taxonomy('resource_type',array('resource'), array(
    'hierarchical' => true,
    'labels' => $resourcetypelabels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'resource_type' ),
  ));
}


add_action( 'init', 'crunchify_create_resource_topic_custom_taxonomy', 0 );
 
function crunchify_create_resource_topic_custom_taxonomy() {
 
  $resourcetopiclabels = array(
    'name' => _x( 'Resource Topic', 'taxonomy general name' ),
    'singular_name' => _x( 'Resource Topic', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Resource Topic' ),
    'all_items' => __( 'All Resource Topic' ),
    'parent_item' => __( 'Parent Resource Topic' ),
    'parent_item_colon' => __( 'Parent Resource Topic:' ),
    'edit_item' => __( 'Edit Resource Topic' ), 
    'update_item' => __( 'Update Resource Topic' ),
    'add_new_item' => __( 'Add New Resource Topic' ),
    'new_item_name' => __( 'New Resource Topic Name' ),
    'menu_name' => __( 'Resource Topic' ),
  );    
 
  register_taxonomy('resource_topic',array('resource'), array(
    'hierarchical' => true,
    'labels' => $resourcetopiclabels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'resource_topic' ),
  ));
}

/* Custom Post Type End Here For Resource Section*/


function get_data() {


    $title    = $_POST['title'];
    $resource_type = $_POST['resource_type'];
    $resource_topic = $_POST['resource_topic'];

   
    if(isset($_POST['title']) && empty($_POST['title']) && isset($_POST['resource_type']) && !empty($_POST['resource_type']) && isset($_POST['resource_topic']) && empty($_POST['resource_topic'])){

        $myposts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'resource',
            'orderby'=>'title',
            'order'=>'ASC',
            'tax_query' => array(
                array(
                'taxonomy' => 'resource_type',
                'field' => 'term_id',
                'terms' => $resource_type,
                'include_children' => false,
                'operator' => 'IN',
            ))
        ));


    }else if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['resource_type']) && empty($_POST['resource_type']) && isset($_POST['resource_topic']) && empty($_POST['resource_topic'])){
        $myposts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'resource',
            'orderby'=>'title',
            'order'=>'ASC',
            's'  => $title,

        ));
    }else if(isset($_POST['title']) && empty($_POST['title']) && isset($_POST['resource_type']) && empty($_POST['resource_type']) && isset($_POST['resource_topic']) && !empty($_POST['resource_topic'])){
        $myposts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'resource',
            'orderby'=>'title',
            'order'=>'ASC',
            'tax_query' => array(
                array(
                'taxonomy' => 'resource_topic',
                'field' => 'term_id',
                'terms' => $resource_topic
            ))
        ));
    }else if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['resource_type']) && !empty($_POST['resource_type']) && isset($_POST['resource_topic']) && !empty($_POST['resource_topic'])){

        $myposts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'resource',
            'orderby'=>'title',
            'order'=>'ASC',
            's'  => $title,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'resource_topic',
                    'field' => 'term_id',
                    'terms' => $resource_topic
                ),
                array(
                    'taxonomy' => 'resource_type',
                    'field' => 'term_id',
                    'terms' => $resource_type,
                    'operator' => 'IN'
                ),
            )
        ));
    }else if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['resource_type']) && !empty($_POST['resource_type']) && isset($_POST['resource_topic']) && empty($_POST['resource_topic'])){
       
        $myposts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'resource',
            'orderby'=>'title',
            'order'=>'ASC',
            's'  => $title,
            'tax_query' => array(
                array(
                    'taxonomy' => 'resource_type',
                    'field' => 'term_id',
                    'terms' => $resource_type,
                    'operator' => 'IN'
                ),
            )
        ));
    }else if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['resource_type']) && empty($_POST['resource_type']) && isset($_POST['resource_topic']) && !empty($_POST['resource_topic'])){

        $myposts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'resource',
            'orderby'=>'title',
            'order'=>'ASC',
            's'  => $title,
            'tax_query' => array(
                array(
                    'taxonomy' => 'resource_topic',
                    'field' => 'term_id',
                    'terms' => $resource_topic
                ),
            )
        ));
    }else if(isset($_POST['title']) && empty($_POST['title']) && isset($_POST['resource_type']) && !empty($_POST['resource_type']) && isset($_POST['resource_topic']) && !empty($_POST['resource_topic'])){

        $myposts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'resource',
            'orderby'=>'title',
            'order'=>'ASC',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'resource_topic',
                    'field' => 'term_id',
                    'terms' => $resource_topic
                ),
                array(
                    'taxonomy' => 'resource_type',
                    'field' => 'term_id',
                    'terms' => $resource_type,
                    'operator' => 'IN'
                ),
            )
        ));
    }else{
        $myposts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'resource',
            'orderby'=>'title',
            'order'=>'ASC',
        ));

    }
    

    if(is_array($myposts) && !empty($myposts)) { ?>
    
        <?php foreach($myposts as $data){ ?>
        <div class="col-md-4"><div class="custom-box"><?php echo $data->post_title; ?></div></div>
          
        <?php }?>
   
    <?php
    } else {
    echo "No Result found.";
    }
    exit;
}


add_action( 'wp_ajax_nopriv_get_data', 'get_data' );
add_action( 'wp_ajax_get_data', 'get_data' );

add_action( 'wp_ajax_nopriv_search_keyword', 'search_keyword' );
add_action( 'wp_ajax_search_keyword', 'search_keyword' );

function search_keyword() {
    $keyword    = $_POST['keyword'];
  
    $args = array(    
    'post_type' => 'resource',
    's' => $keyword

    );
 
    $loop = new WP_Query( $args ); 
 
    if ( $loop->have_posts() ) {
    
    ?>
    <ul id="resources-list">

    <?php 
        while ( $loop->have_posts() ) : $loop->the_post();     
    ?>        
        <li onClick="selectresourcetype('<?php echo get_the_title(); ?>');"><?php echo get_the_title(); ?></li>
    <?php endwhile; ?>
    </ul>  
    <?php 
        wp_reset_postdata(); 

} 
  exit;
}
