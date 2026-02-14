add_filter('generate_sidebar_layout', function($layout) {
    if (is_post_type_archive('gtv_news')) {
        return 'no-sidebar';
    }
    return $layout;
});
