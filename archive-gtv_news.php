<?php
/**
 * GTV News — Archive Template v12.9
 * Template: archive-gtv_news.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

$q = new WP_Query( array(
    'post_type'      => 'gtv_news',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'paged'          => $paged,
) );

$archive_hero_id = get_option( 'gtv_news_archive_hero_id', 0 );

if ( $archive_hero_id && get_post_type( $archive_hero_id ) === 'hero_banner' ) :
    $title       = get_the_title( $archive_hero_id );
    $lead        = get_field( 'primary_lead_text', $archive_hero_id );
    $lead_sec    = get_field( 'secondary_lead_text', $archive_hero_id );
    $parent_text = get_field( 'parent_page_name', $archive_hero_id );
    $parent_url  = get_field( 'parent_page_url', $archive_hero_id );
    $img_id      = get_post_thumbnail_id( $archive_hero_id );
    $img_large   = $img_id ? wp_get_attachment_image_src( $img_id, 'large' ) : null;
    $img_full    = $img_id ? wp_get_attachment_image_src( $img_id, 'full' ) : null;
    $alt         = $img_id ? ( get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: $title ) : '';
    $caption     = $img_id ? wp_get_attachment_caption( $img_id ) : '';
?>

<div id="gtv-news-hero" style="background-color:#F8F2FF; border-bottom:1px solid #e0d0f0; padding-bottom:30px; font-family:'HelveticaNeue','Helvetica','Arial',sans-serif;">
    <div style="height:8px; background-color:#663399; width:100%; margin-bottom:30px;"></div>
    <div style="max-width:1200px; width:100%; margin:0 auto; padding:0 20px; box-sizing:border-box;">

        <nav aria-label="Breadcrumb">
            <ul style="font-size:14px; color:#505a5f; margin:0 0 25px 0; padding:0; list-style:none; display:flex; flex-wrap:wrap;">
                <li style="display:flex; align-items:center; margin-right:5px;">
                    <a href="<?php echo home_url(); ?>" style="color:#505a5f; text-decoration:underline;">Home</a>
                    <span style="margin:0 8px; color:#b1b4b6;" aria-hidden="true">&gt;</span>
                </li>
                <?php if ( $parent_text && $parent_url ) : ?>
                <li style="display:flex; align-items:center; margin-right:5px;">
                    <a href="<?php echo esc_url( $parent_url ); ?>" style="color:#505a5f; text-decoration:underline;"><?php echo esc_html( $parent_text ); ?></a>
                    <span style="margin:0 8px; color:#b1b4b6;" aria-hidden="true">&gt;</span>
                </li>
                <?php endif; ?>
                <li><span aria-current="page"><strong><?php echo esc_html( $title ); ?></strong></span></li>
            </ul>
        </nav>

        <div style="display:flex; flex-direction:row; justify-content:space-between; align-items:flex-start; gap:40px;">

            <div style="flex:1 1 auto; min-width:0;">
                <h1 style="font-size:48px; font-weight:700; margin:0 0 25px 0; color:#0b0c0c; line-height:1.1; letter-spacing:-0.5px;"><?php echo esc_html( $title ); ?></h1>
                <?php if ( $lead ) : ?>
                    <p style="font-size:20px; color:#333333; line-height:1.6; margin:0;"><?php echo nl2br( esc_html( $lead ) ); ?></p>
                <?php endif; ?>
                <?php if ( $lead_sec ) : ?>
                    <p style="font-size:20px; color:#333333; line-height:1.6; margin-top:20px;"><?php echo nl2br( esc_html( $lead_sec ) ); ?></p>
                <?php endif; ?>
            </div>

            <?php if ( $img_large ) : ?>
            <div style="flex:0 0 380px; max-width:380px;">
                <a href="#" class="gtv-lightbox-trigger" aria-label="Expand image"
                   data-full="<?php echo esc_url( $img_full[0] ); ?>"
                   data-caption="<?php echo esc_attr( $caption ); ?>"
                   style="display:block; cursor:zoom-in;">
                    <img src="<?php echo esc_url( $img_large[0] ); ?>"
                         alt="<?php echo esc_attr( $alt ); ?>"
                         style="width:100%; height:280px; object-fit:cover; border-radius:4px; display:block; box-shadow:0 10px 20px rgba(0,0,0,0.08);">
                </a>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        var hero   = document.getElementById('gtv-news-hero');
        var header = document.querySelector('.site-header');
        if (hero && header) {
            header.insertAdjacentElement('afterend', hero);
        }
    });
})();
</script>

<?php else : ?>

    <div class="gtv-single-banner gtv-archive-banner">
        <div class="gtv-single-banner__inner gtv-single-banner__inner--wide">
            <h1 class="gtv-single-title gtv-single-title--light">News Stories</h1>
        </div>
    </div>

<?php endif; ?>

<div class="gtv-news-wrapper" style="padding-top: 40px;">

    <?php if ( $q->have_posts() ) : ?>

        <div class="gtv-archive-grid">

            <?php while ( $q->have_posts() ) : $q->the_post();
                $thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
            ?>
                <article class="gtv-story-card">
                    <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                        <div class="gtv-story-card__image-wrap">
                            <?php if ( $thumb ) : ?>
                                <img class="gtv-story-card__img"
                                     src="<?php echo esc_url( $thumb ); ?>"
                                     alt="<?php echo esc_attr( get_the_title() ); ?>"
                                     loading="lazy">
                            <?php else : ?>
                                <div class="gtv-story-card__no-img" aria-hidden="true"></div>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="gtv-story-card__body">
                        <h2 class="gtv-story-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="gtv-story-card__meta">
                            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                <?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
                            </time>
                        </div>
                        <a href="<?php the_permalink(); ?>"
                           class="gtv-story-card__cta"
                           aria-label="Read story: <?php echo esc_attr( get_the_title() ); ?>">Read story &rarr;</a>
                    </div>
                </article>
            <?php endwhile; ?>

        </div>

        <?php if ( $q->max_num_pages > 1 ) : ?>
            <nav class="gtv-pagination" aria-label="News archive pages">
                <?php echo paginate_links( array(
                    'total'   => $q->max_num_pages,
                    'current' => $paged,
                ) ); ?>
            </nav>
        <?php endif; ?>

    <?php else : ?>
        <p style="text-align:center; padding:50px 0;">No news stories found.</p>
    <?php endif;

    wp_reset_postdata(); ?>

</div>

<?php get_footer(); ?>
