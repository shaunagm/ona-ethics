<?php if (is_user_logged_in() ){
	wp_redirect( get_site_url().'/dashboard/');
	die();
} ?>


<?php get_header(); ?>

<h1>Create an Account</h1>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php the_content(); ?>
		<?php echo do_shortcode('[dm_registration_form]'); ?>
	<?php endwhile; else: ?>
		<p><?php _e("We couldn't find any posts that matched your query. Please try again."); ?></p>
	<?php endif; ?>

<?php get_footer(); ?>