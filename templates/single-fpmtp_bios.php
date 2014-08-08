<?php
/**
 * The Template for displaying single Bios posts
 *
 * @package FPMTP
 * @since 0.1.2
 *
 * @todo Get Author Books based on post title
 * @todo Get Speaker Audio based on post title
 *
 * This template is for the X theme, and solely intended for use if there is no corresponding child theme template
 * for Bios custom post type.
 */

?>

<?php get_header(); ?>

    <div class="x-container-fluid max width offset cf">
        <div class="<?php x_main_content_class(); ?>" role="main">


            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <div class="entry-wrap cf">

                    <?php if ( is_singular() ) : ?>

                        <div class="entry-info">
                            <header class="entry-header">
                                <h1 class="entry-title entry-title-portfolio"><?php the_title(); ?></h1>
                                <?php x_renew_entry_meta(); ?>
                            </header>
                            <?php x_get_view( 'global', '_content', 'the-content' ); ?>
                        </div>
                        <div class="entry-extra">
                            <p>This is where books and audio should be linked from.</p>
                        </div>

                    <?php endif; ?>

                </div>
            </article>

        </div>

        <?php get_sidebar(); ?>

    </div>

<?php get_footer(); ?>