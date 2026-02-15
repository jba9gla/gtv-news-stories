<?php
/**
 * GTV News — Single Story Template v13.0
 * Template: single-gtv_news.php
 * Changes: Two-column layout with dashboard-controlled sidebar, 1300px width
 */

if ( ! defined( 'ABSPATH' ) ) exit;

the_post();

$pid        = get_the_ID();
$title      = get_the_title();
$date       = get_the_date( 'j F Y' );
$date_iso   = get_the_date( 'c' );
$feat_img   = get_the_post_thumbnail_url( $pid, 'full' );
$feat_img_m = get_the_post_thumbnail_url( $pid, 'medium_large' );
$excerpt    = has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : wp_trim_words( wp_strip_all_tags( get_the_content() ), 30, '…' );
$permalink  = get_permalink( $pid );
$tags       = get_the_tags();
$site_name  = get_bloginfo( 'name' );

// Sidebar options from dashboard
$sidebar_enabled = get_option( 'gtv_news_sidebar_enabled', 1 );
$sidebar_type    = get_option( 'gtv_news_sidebar_type', 'latest' ); // latest, related, both
$sidebar_count   = (int) get_option( 'gtv_news_sidebar_count', 3 );
$sidebar_custom  = get_option( 'gtv_news_sidebar_custom', '' );

// SEO meta
add_action( 'wp_head', function() use ( $title, $excerpt, $feat_img_m, $permalink, $site_name, $date_iso ) {
    $og_img = $feat_img_m ? esc_url( $feat_img_m ) : '';
    ?>
    <meta name="description" content="<?php echo esc_attr( $excerpt ); ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo esc_url( $permalink ); ?>">
    <meta property="og:type"        content="article">
    <meta property="og:title"       content="<?php echo esc_attr( $title ); ?>">
    <meta property="og:description" content="<?php echo esc_attr( $excerpt ); ?>">
    <meta property="og:url"         content="<?php echo esc_url( $permalink ); ?>">
    <meta property="og:site_name"   content="<?php echo esc_attr( $site_name ); ?>">
    <meta property="article:published_time" content="<?php echo esc_attr( $date_iso ); ?>">
    <?php if ( $og_img ) : ?>
    <meta property="og:image" content="<?php echo $og_img; ?>">
    <?php endif; ?>
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?php echo esc_attr( $title ); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr( $excerpt ); ?>">
    <?php if ( $og_img ) : ?>
    <meta name="twitter:image" content="<?php echo $og_img; ?>">
    <?php endif; ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsArticle",
        "headline": <?php echo wp_json_encode( $title ); ?>,
        "description": <?php echo wp_json_encode( $excerpt ); ?>,
        "datePublished": <?php echo wp_json_encode( $date_iso ); ?>,
        "url": <?php echo wp_json_encode( $permalink ); ?>
        <?php if ( $og_img ) : ?>
        ,"image": <?php echo wp_json_encode( $og_img ); ?>
        <?php endif; ?>
    }
    </script>
    <?php
}, 5 );

get_header();
?>

<?php /* ── Full width purple banner ── */ ?>
<div class="gtv-single-banner">
    <div class="gtv-single-banner__inner">

        <nav class="gtv-breadcrumbs gtv-breadcrumbs--light" aria-label="Breadcrumb">
            <ol>
                <li><a href="<?php echo esc_url( home_url() ); ?>">Home</a></li>
                <li><a href="<?php echo esc_url( home_url( '/gtvnews/' ) ); ?>">News</a></li>
                <li><span aria-current="page"><?php echo esc_html( $title ); ?></span></li>
            </ol>
        </nav>

        <h1 class="gtv-single-title gtv-single-title--light"><?php echo esc_html( $title ); ?></h1>

        <div class="gtv-banner-meta">
            <time datetime="<?php echo esc_attr( $date_iso ); ?>"><?php echo esc_html( $date ); ?></time>
        </div>

    </div>
</div>

<?php /* ── Main wrapper ── */ ?>
<div class="gtv-news-wrapper gtv-news-wrapper--single">

    <?php /* ── Featured image ── */ ?>
    <?php if ( $feat_img ) : ?>
    <div class="gtv-news-feat-img">
        <img src="<?php echo esc_url( $feat_img ); ?>"
             alt="<?php echo esc_attr( $title ); ?>"
             loading="eager"
             decoding="async">
    </div>
    <?php endif; ?>

    <?php /* ── Two column grid ── */ ?>
    <div class="gtv-single-grid<?php echo $sidebar_enabled ? ' gtv-single-grid--has-sidebar' : ''; ?>">

        <?php /* ── Main content ── */ ?>
        <main class="gtv-news-content" id="main-content">

            <div class="gtv-news-body">
                <?php echo apply_filters( 'the_content', get_the_content() ); ?>
            </div>

            <?php /* ── Tags ── */ ?>
            <?php if ( $tags ) : ?>
            <div class="gtv-news-tags">
                <hr class="gtv-news-divider" aria-hidden="true">
                <dl class="gtv-tag-list">
                    <dt class="gtv-tag-list__label">Tags</dt>
                    <dd class="gtv-tag-list__items">
                        <?php foreach ( $tags as $tag ) : ?>
                        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                           class="gtv-tag-link"
                           rel="tag"><?php echo esc_html( $tag->name ); ?></a>
                        <?php endforeach; ?>
                    </dd>
                </dl>
            </div>
            <?php endif; ?>

            <?php /* ── Share bar ── */ ?>
            <div class="gtv-share-bar">
                <hr class="gtv-news-divider" aria-hidden="true">
                <div class="gtv-share-bar__inner">
                    <p class="gtv-share-heading">Share this page</p>
                    <div class="gtv-share-bar__buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( $permalink ); ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="gtv-share-btn" aria-label="Share on Facebook">
                            <span aria-hidden="true">FB</span>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( $permalink ); ?>&text=<?php echo urlencode( $title ); ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="gtv-share-btn" aria-label="Share on X (Twitter)">
                            <span aria-hidden="true">X</span>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode( $permalink ); ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="gtv-share-btn" aria-label="Share on LinkedIn">
                            <span aria-hidden="true">in</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="gtv-back-link">
                <a href="<?php echo esc_url( home_url( '/gtvnews/' ) ); ?>">&larr; Back to all news</a>
            </div>

        </main>

        <?php /* ── Sidebar ── */ ?>
        <?php if ( $sidebar_enabled ) : ?>
        <aside class="gtv-news-sidebar" aria-label="More news">

            <?php
            // ---------------------------------------------------------------
            // LATEST NEWS STORIES
            // ---------------------------------------------------------------
            $show_latest  = in_array( $sidebar_type, [ 'latest', 'both' ] );
            $show_related = in_array( $sidebar_type, [ 'related', 'both' ] );

            if ( $show_latest ) :
                $latest_q = new WP_Query( [
                    'post_type'      => 'gtv_news',
                    'post_status'    => 'publish',
                    'posts_per_page' => $sidebar_count,
                    'post__not_in'   => [ $pid ],
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ] );

                if ( $latest_q->have_posts() ) :
            ?>
            <div class="gtv-sidebar-block">
                <h2 class="gtv-sidebar-heading">Latest News</h2>
                <ul class="gtv-sidebar-cards">
                    <?php while ( $latest_q->have_posts() ) : $latest_q->the_post();
                        $s_thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
                    ?>
                    <li class="gtv-sidebar-card">
                        <?php if ( $s_thumb ) : ?>
                        <a href="<?php the_permalink(); ?>" class="gtv-sidebar-card__img-wrap" tabindex="-1" aria-hidden="true">
                            <img src="<?php echo esc_url( $s_thumb ); ?>"
                                 alt="<?php echo esc_attr( get_the_title() ); ?>"
                                 loading="lazy">
                        </a>
                        <?php endif; ?>
                        <div class="gtv-sidebar-card__body">
                            <time class="gtv-sidebar-card__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                <?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
                            </time>
                            <h3 class="gtv-sidebar-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <a href="<?php the_permalink(); ?>" class="gtv-sidebar-card__cta">Read story &rarr;</a>
                        </div>
                    </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </div>
            <?php endif; endif; ?>

            <?php
            // ---------------------------------------------------------------
            // RELATED STORIES (by shared tags)
            // ---------------------------------------------------------------
            if ( $show_related && $tags ) :
                $tag_ids   = wp_list_pluck( $tags, 'term_id' );
                $related_q = new WP_Query( [
                    'post_type'      => 'gtv_news',
                    'post_status'    => 'publish',
                    'posts_per_page' => $sidebar_count,
                    'post__not_in'   => [ $pid ],
                    'tag__in'        => $tag_ids,
                    'orderby'        => 'relevance',
                ] );

                if ( $related_q->have_posts() ) :
            ?>
            <div class="gtv-sidebar-block">
                <h2 class="gtv-sidebar-heading">Related Stories</h2>
                <ul class="gtv-sidebar-cards">
                    <?php while ( $related_q->have_posts() ) : $related_q->the_post();
                        $s_thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
                    ?>
                    <li class="gtv-sidebar-card">
                        <?php if ( $s_thumb ) : ?>
                        <a href="<?php the_permalink(); ?>" class="gtv-sidebar-card__img-wrap" tabindex="-1" aria-hidden="true">
                            <img src="<?php echo esc_url( $s_thumb ); ?>"
                                 alt="<?php echo esc_attr( get_the_title() ); ?>"
                                 loading="lazy">
                        </a>
                        <?php endif; ?>
                        <div class="gtv-sidebar-card__body">
                            <time class="gtv-sidebar-card__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                <?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
                            </time>
                            <h3 class="gtv-sidebar-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <a href="<?php the_permalink(); ?>" class="gtv-sidebar-card__cta">Read story &rarr;</a>
                        </div>
                    </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </div>
            <?php endif; endif; ?>

            <?php
            // ---------------------------------------------------------------
            // CUSTOM TEXT BLOCK
            // ---------------------------------------------------------------
            if ( ! empty( $sidebar_custom ) ) :
            ?>
            <div class="gtv-sidebar-block gtv-sidebar-block--custom">
                <?php echo wp_kses_post( $sidebar_custom ); ?>
            </div>
            <?php endif; ?>

            <?php /* ── Back to news (sidebar) ── */ ?>
            <div class="gtv-sidebar-block gtv-sidebar-block--back">
                <a href="<?php echo esc_url( home_url( '/gtvnews/' ) ); ?>" class="gtv-sidebar-back-link">
                    &larr; All News Stories
                </a>
            </div>

        </aside>
        <?php endif; ?>

    </div><!-- .gtv-single-grid -->

</div><!-- .gtv-news-wrapper -->

<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var banner = document.querySelector('.gtv-single-banner');
        var header = document.querySelector('.site-header');
        if (banner && header) {
            header.insertAdjacentElement('afterend', banner);
        }
    });
})();
</script>

<?php get_footer(); ?>
