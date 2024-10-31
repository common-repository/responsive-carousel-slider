<?php

class Bss_ca_slideshow_Post {

    protected $textdomain;
    protected $posts;

    public function __construct($textdomain) {
        $this->textdomain = $textdomain;
        $this->posts = array();
        add_action('init', array($this, 'register_custom_post'));
    }

    public function Bss_Make_ca_slideshow_Post($type, $singular_label, $plural_label, $settings = array()) {
        $default_settings = array(
            'labels' => array(
                'name' => __($plural_label, $this->textdomain),
                'singular_name' => __($singular_label, $this->textdomain),
                'add_new_item' => __('Add New ' . $singular_label, $this->textdomain),
                'edit_item' => __('Edit ' . $singular_label, $this->textdomain),
                'new_item' => __('New ' . $singular_label, $this->textdomain),
                'view_item' => __('View ' . $singular_label, $this->textdomain),
                'search_items' => __('Search ' . $plural_label, $this->textdomain),
                'not_found' => __('No ' . $plural_label . ' found', $this->textdomain),
                'not_found_in_trash' => __('No ' . $plural_label . ' found in trash', $this->textdomain),
                'parent_item_colon' => __('Parent ' . $singular_label, $this->textdomain),
            ),
            'public' => true,
            'has_archive' => true,
            'menu_position' => 20,
            'supports' => array(
                'title',
                'editor'
            ),
            'rewrite' => array(
                'slug' => sanitize_title_with_dashes($plural_label)
            )
        );
        $this->posts[$type] = array_replace($default_settings, $settings);
    }

    public function register_custom_post() {
        foreach ($this->posts as $key => $values) {
            register_post_type($key, $values);
        }
    }

}

?>
