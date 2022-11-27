<?php

/**
 * Template Name: Home Page
 *
 * @package WordPress
 * 
*/

get_header();
$postid = get_the_ID();

?>



<div class="main-block">
    <h1><?php the_title(); ?></h1>

</div>


    

<?php get_footer(); ?>
