

<?php get_header(); ?>

<div class="main-column" role="content">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<h1><?php the_title(); ?></h1>

	<?php the_content(); ?>

	<?php endwhile; else: ?>
			
		<p><?php _e("We couldn't find any posts that matched your query. Please try again."); ?></p>

	<?php endif; ?>
</div>

<?php get_footer(); ?>