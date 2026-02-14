<?php
/**
 * GTV News — Single Story Template v12.4
 * Template: single-gtv_news.php
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

// SEO meta
add_action( 'wp_head', function() use ( $title, $excerpt, $feat_img_m, $permalink, $site_name, $date_iso ) {
    $og_img = $feat_img_m ? esc_url( $feat_img_m ) : '';
    ?>
    <!-- GTV News SEO -->
    <meta name="description" content="<?php echo esc_attr( $excerpt ); ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo esc_url( $permalink ); ?>">

    <!-- Open Graph -->
    <meta property="og:type"        content="article">
    <meta property="og:title"       content="<?php echo esc_attr( $title ); ?>">
    <meta property="og:description" content="<?php echo esc_attr( $excerpt ); ?>">
    <meta property="og:url"         content="<?php echo esc_url( $permalink ); ?>">
    <meta property="og:site_name"   content="<?php echo esc_attr( $site_name ); ?>">
    <meta property="article:published_time" content="<?php echo esc_attr( $date_iso ); ?>">
    <?php if ( $og_img ) : ?>
    <meta property="og:image"       content="<?php echo $og_img; ?>">
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?php echo esc_attr( $title ); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr( $excerpt ); ?>">
    <?php if ( $og_img ) : ?>
    <meta name="twitter:image"       content="<?php echo $og_img; ?>">
    <?php endif; ?>

    <!-- Schema.org Article -->
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

<?php /* ── Content wrapper ── */ ?>
<div class="gtv-news-wrapper gtv-news-wrapper--single">

    <?php /* ── Featured image ── */ ?>
    <?php if ( $feat_img ) : ?>
        <div class="gtv-news-feat-img">
            <img src="<?php echo esc_url( $feat_img ); ?>"
                 alt="<?php echo esc_attr( $title ); ?>"
                 width="1200"
                 height="630"
                 loading="eager"
                 decoding="async">
        </div>
    <?php endif; ?>

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
                       target="_blank"
                       rel="noopener noreferrer"
                       class="gtv-share-btn"
                       aria-label="Share on Facebook">
                        <span aria-hidden="true">FB</span>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( $permalink ); ?>&text=<?php echo urlencode( $title ); ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="gtv-share-btn"
                       aria-label="Share on X (Twitter)">
                        <span aria-hidden="true">X</span>
                    </a>
                </div>
            </div>
        </div>

    </main>

</div>

<script>
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        // Move banner outside GP content container for true full width
        var banner = document.querySelector('.gtv-single-banner');
        var header = document.querySelector('.site-header');
        if (banner && header) {
            header.insertAdjacentElement('afterend', banner);
        }
    });
})();
</script>

<?php get_footer(); ?>
