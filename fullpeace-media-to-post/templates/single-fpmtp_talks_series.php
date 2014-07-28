<?php
/**
 * The Template for displaying single Talks Series posts
 *
 * @package FPMTP
 * @since 0.1.0
 * 
 * @todo Get Series based on post title
 */


get_header(); ?>

    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">
            <?php
            // Start the Loop.
            while ( have_posts() ) : the_post();

?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_post_thumbnail(); ?>

	<header class="entry-header">
        <?php if ( in_array( '_series', get_object_taxonomies( get_post_type() ) ) ) : ?>
            <div class="entry-meta">
                <span class="cat-links"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', FPMTP__I18N_NAMESPACE ) ); ?></span>
            </div><!-- .entry-meta -->
        <?php
        endif;

        if ( is_single() ) :
            the_title( '<h1 class="entry-title">', '</h1>' );
        else :
            the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
        endif;
        ?>

        <div class="entry-meta">
			<span class="post-format">
				<a class="entry-format" href="<?php echo esc_url( get_post_format_link( 'audio' ) ); ?>"><?php echo get_post_format_string( 'audio' ); ?></a>
			</span>

            <?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
                <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', FPMTP__I18N_NAMESPACE ), __( '1 Comment', FPMTP__I18N_NAMESPACE ), __( '% Comments', FPMTP__I18N_NAMESPACE ) ); ?></span>
            <?php endif; ?>

            <?php edit_post_link( __( 'Edit', FPMTP__I18N_NAMESPACE ), '<span class="edit-link">', '</span>' ); ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->

	<div class="entry-content">
        <?php
        the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', FPMTP__I18N_NAMESPACE ) );
        wp_link_pages( array(
            'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', FPMTP__I18N_NAMESPACE ) . '</span>',
            'after'       => '</div>',
            'link_before' => '<span>',
            'link_after'  => '</span>',
        ) );
        ?>
    </div><!-- .entry-content -->

	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
</article><!-- #post-## -->

<?php
          // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) {
                    comments_template();
                }
            endwhile;
            ?>
        </div><!-- #content -->
    </div><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
