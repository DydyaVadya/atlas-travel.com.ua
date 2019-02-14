<?php
/**
 * The template for displaying single tm-pg-set.
 *
 * @package Tourizto
 */
?>
<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title h3-style title-decoration__bottom title-decoration__big"><?php the_title(); ?></h1>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php do_action( 'tm-pg-grid-set', get_the_ID() ); ?>
		</div><!-- .entry-content -->
	</article><!-- #post-<?php the_ID(); ?> -->
<?php endwhile; ?>
