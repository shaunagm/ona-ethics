

<?php get_header(); ?>

<div class="main-column" role="content">
	<?php if (have_posts()) : ?>

        <header class="page-header">
            <h1 class="page-title"><?php _e( 'Search Results' ); ?></h1>
        </header><!-- .page-header -->

		<?php get_search_form(); ?>

        <?php //shape_content_nav( 'nav-above' ); ?>

        <?php /* Start the Loop */ ?>
        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			    <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			</article><!-- #post-<?php the_ID(); ?> -->

        <?php endwhile; ?>

        <?php //shape_content_nav( 'nav-below' ); ?>

    <?php else : ?>

        <p>No results</p>

	<?php endif; ?>
</div>

<?php get_footer(); ?>