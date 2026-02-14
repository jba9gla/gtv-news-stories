# GTV News System

Custom news post type and archive system for [Gathering the Voices](https://gatheringthevoices.com), built on GeneratePress with WPCode snippets.

---

## Overview

A fully custom WordPress news section at `/gtvnews/` using a custom post type (`gtv_news`), custom archive and single post templates, and a News Manager admin dashboard. The system integrates with the existing Hero Banner post type for the archive page header.

---

## File Structure

```
wp-content/
└── themes/
    └── generatepress_child/
        ├── archive-gtv_news.php     # Archive page template (v12.9)
        └── single-gtv_news.php      # Single post template
```

### WPCode Snippets (managed via WPCode plugin)

| Snippet ID | Title | Type | Location |
|---|---|---|---|
| 12814 | News Manager | PHP | Run Everywhere |
| 12815 | News - CSS | CSS | Site Wide Header |

---

## archive-gtv_news.php

**Version:** 12.9

### Features
- Checks `gtv_news_archive_hero_id` WordPress option for assigned hero banner
- If hero assigned: renders hero with accent bar, breadcrumbs, title, lead text and featured image
- If no hero: falls back to plain purple banner
- 3-column card grid with pagination (9 posts per page)

### Key Implementation Detail
The hero div is moved out of the GeneratePress content area via JavaScript after page load:

```javascript
document.addEventListener('DOMContentLoaded', function() {
    var hero   = document.getElementById('gtv-news-hero');
    var header = document.querySelector('.site-header');
    if (hero && header) {
        header.insertAdjacentElement('afterend', hero);
    }
});
```

This is necessary because GeneratePress constrains `.content-area` to 70% width when a sidebar layout is active. Moving the hero to sit directly after `.site-header` gives it full viewport width.

### Hero Banner Fields (ACF)
The following ACF fields are read from the assigned `hero_banner` post:
- `primary_lead_text`
- `secondary_lead_text`
- `parent_page_name`
- `parent_page_url`
- Featured image (via `get_post_thumbnail_id()`)

---

## single-gtv_news.php

### Features
- Full-width purple banner with post title and date
- Breadcrumb navigation
- Featured image with purple border accent
- Post body content
- Tag list
- Social share bar (Twitter/X, Facebook, LinkedIn, email)
- Grey page background with white content card

---

## News Manager (WPCode PHP Snippet #12814)

Admin dashboard accessible via **News → News Manager** in the WordPress admin.

### Tabs
- **All Stories** — lists all `gtv_news` posts with edit/view links
- **Add Story** — quick publish form
- **Hero Banners** — assign a `hero_banner` post to the news archive page

### WordPress Option
`gtv_news_archive_hero_id` — stores the ID of the hero banner assigned to the archive page. Set to `0` for no hero (falls back to purple banner).

---

## gtv-news-v12.css (WPCode CSS Snippet #12815)

Consolidated stylesheet. Key sections:

| Section | Purpose |
|---|---|
| 1 | Main wrapper |
| 2 | Single post grey background |
| 3 | Purple banner (single + archive fallback) |
| 4 | Archive hero styles |
| 5 | Breadcrumbs (single post) |
| 6 | Titles and banner meta |
| 7 | Archive card grid |
| 8 | Single featured image |
| 9 | Single body content |
| 10 | Tags |
| 11 | Share bar |
| 12 | Pagination |
| 13 | GeneratePress container overrides |
| 14 | Responsive breakpoints |
| 15 | Accessibility (reduced motion) |

---

## Testimonial App Conflict Fix

The Testimonial Dashboard (WPCode snippet #12428) was interfering with `gtv_news` single posts, causing duplicate content rendering. Four bail-out checks were added to the testimonial snippet:

```php
if ( is_singular('gtv_news') ) return;
```

Applied to: `sg_render_testimonial_content`, `sg_add_body_class`, `sg_remove_default_content`, and `sg_enqueue_testimonial_assets`.

---

## SocialBee RSS Integration

New `gtv_news` stories are automatically available to SocialBee via the post type RSS feed:

```
https://gatheringthevoices.com/gtvnews/feed/
```

In SocialBee: **Content → RSS Feeds** → add the URL above, set to import new posts only. Stories published in WordPress will be automatically queued for social posting.

---

## Dependencies

- GeneratePress + GeneratePress Child Theme
- GeneratePress Premium (GP Premium) — sidebar layout controls
- WPCode Premium — snippet management
- Advanced Custom Fields (ACF) — hero banner field data
- Hero Banner custom post type (WPCode snippet #12000)

---

## CSS Variables

```css
:root {
    --gtv-purple:      #4b286d;
    --gtv-purple-dark: #2a153d;
    --gtv-text:        #0b0c0c;
    --gtv-gray:        #505a5f;
    --gtv-max:         1200px;
    --gtv-single-max:  960px;
    --govuk-blue:      #1d70b8;
}
```

---

## Known Constraints

- Hero banner requires the JS DOM move to display full width due to GeneratePress sidebar layout constraining `.content-area` to 70%
- Hero banner featured image displays at 380px × 280px, object-fit cover
- Cards grid max-width matches hero at 1200px via `--gtv-max` CSS variable

---

## Changelog

| Version | Date | Notes |
|---|---|---|
| 12.9 | 2026-02-14 | JS DOM move for full-width hero, featured image restored |
| 12.8 | 2026-02-14 | JS DOM move introduced, image column removed temporarily |
| 12.7 | 2026-02-14 | Image column removed to debug layout |
| 12.6 | 2026-02-14 | Hero banner integration, inline styles, CSS consolidation |
| 12.0 | 2026-02-14 | Initial custom post type and templates |
