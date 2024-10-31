<?php
/*
  Plugin Name:Responsive Carousel Slider
  Plugin URI: http://www.clownfishweb.com
  Description: Thanks for installing Responsive Carousel Slider
  Version: 1.0
  Author: Clown Fish Web
  Author URI: http://www.clownfishweb.com
 */

include dirname(__FILE__) . '/inc/bs-ca-slider-post.php';
include dirname(__FILE__) . '/bs-ca-slider-shortcode.php';

class Bss_ca_slideshow {

    public function Bss_ca_Instance() {
        $custom_post = new Bss_ca_slideshow_Post('bss-ca-slideshow');
        $custom_post->Bss_Make_ca_slideshow_Post('bss_ca_slideshow', 'Responsive carousel Slider', 'Responsive carousel Sliders', array('supports' => array('title')));
        add_action('admin_init', array($this, 'bss_ca_slideshow_metabox_feild'));
        add_action('admin_init', array($this, 'bss_ca_slideshow_metabox_feild_shortcode'));
        add_action('save_post', array($this, 'add_bss_ca_slideshow'), 10, 2);
        add_action('admin_menu', array($this, 'bss_ca_slidshow_setting'));
        add_action('admin_init', array($this, 'bss_ca_slidshow_register_settings'));
        add_action('admin_head', array($this, 'bss_ca_slideshow_admin_css'));
        add_action('admin_enqueue_scripts', array($this, 'bss_ca_slideshow_load_wp_media_files'));
    }

    public function bss_ca_slideshow_getInstance() {
        $this->Bss_ca_Instance();
    }

    public function bss_ca_slideshow_metabox_feild() {
        add_meta_box('bss_ca_slideshow_meta_id', 'Add slideshow', array($this, 'display_bss_ca_slideshow_metabox'), 'bss_ca_slideshow', 'normal', 'high');
    }

    public function bss_ca_slideshow_metabox_feild_shortcode() {
        add_meta_box('bss_ca_slideshow_meta_shortcode', 'ShortCode', array($this, 'display_bss_ca_slideshow_metabox_shortcode'), 'bss_ca_slideshow', 'side', 'low');
    }

    public function display_bss_ca_slideshow_metabox_shortcode($bss_ca_slideshow) {
        ?>
        <div class="bss_price">
            <input type="text" name="bss_ca_slideshow_shortcode[]" value="[bs_carousel_slider id='<?= get_the_id(); ?>']" disabled></input>
        </div>

        <?php
    }

    public function display_bss_ca_slideshow_metabox($bss_ca_slideshow) {
        wp_nonce_field('bss_ca_slidshow_nonce', 'bss_ca_slidshow_nonce_field');
        $data_tables = get_post_meta($bss_ca_slideshow->ID, '_bss_ca_slideshow_group', true);
        include dirname(__FILE__) . '/inc/metabox.php';
    }

    public function add_bss_ca_slideshow($post_id, $bss_ca_slideshow) {
        if (!isset($_POST['bss_ca_slidshow_nonce_field']) || !wp_verify_nonce($_POST['bss_ca_slidshow_nonce_field'], 'bss_ca_slidshow_nonce')) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if ($bss_ca_slideshow->post_type == 'bss_ca_slideshow') {
            if (empty($_POST['bss_ca_slideshow_group'])) {
                $_POST['bss_ca_slideshow_group'] = array('');
            }
            foreach ($_POST['bss_ca_slideshow_group'] as $key => $data_table) {
                $bss_ca_slidshow_group_array[] = array_map('sanitize_text_field', $data_table);
                foreach ($bss_ca_slidshow_group_array as $key => $data_table) {
                    if (empty($data_table['bss_img_slidshow_title']) && empty($data_table['bss_img_slidshow_pic']))
                        unset($bss_ca_slidshow_group_array[$key]);
                }
            }

            if (isset($_POST['bss_ca_slideshow_group']) &&
                    $_POST['bss_ca_slideshow_group'] != '') {
                update_post_meta($post_id, '_bss_ca_slideshow_group', $bss_ca_slidshow_group_array);
            }
        }
    }

    public function bss_ca_slidshow_setting() {
        add_submenu_page('edit.php?post_type=bss_ca_slideshow', __('Slidshow Settings', 'bss-ca-slideshow'), __('Settings', 'bss-ca-slideshow'), 'manage_options', 'bss_ca_slideshow_setting', array($this, 'bss_ca_slidshow_setting_field'));
    }

    public function bss_ca_slidshow_setting_field() { ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <form method="post" action="options.php" enctype="multipart/form-data">
                <?php settings_fields('bss_ca_slidshow_options'); ?>
                <?php do_settings_sections('bss_img_slidshow'); ?>
                <p class="submit">
                    <input name="submit" type="submit" class="button-primary" value="Save Changes"/>
                </p>
            </form>
        </div>
    <?php }

    public function bss_ca_slidshow_register_settings() {

        register_setting('bss_ca_slidshow_options', 'bss_ca_slidshow_options');
        add_settings_section('bss_ca_slideshow', '', array($this, 'bss_video_section_text'), 'bss_img_slidshow');
        add_settings_field('bss_displayControls', __('Display Controls', 'bss-ca-slideshow'), array($this, 'bss_ca_slidshow_displayControls'), 'bss_img_slidshow', 'bss_ca_slideshow');
        //add_settings_field('bss_autoSlide', __('Single Item', 'bss-ca-slideshow'), array($this, 'bss_ca_slidshow_autoSlide'), 'bss_img_slidshow', 'bss_ca_slideshow');
        add_settings_field('bss_items', __('Items', 'bss-ca-slideshow'), array($this, 'bss_ca_slidshow_items'), 'bss_img_slidshow', 'bss_ca_slideshow');
        add_settings_field('bss_effect', __('Autoplay', 'bss-ca-slideshow'), array($this, 'bss_ca_slidshow_transitionEffect'), 'bss_img_slidshow', 'bss_ca_slideshow');
        add_settings_field('bss_caControls', __('PaginationSpeed', 'bss-ca-slideshow'), array($this, 'bss_ca_slidshow_caControls'), 'bss_img_slidshow', 'bss_ca_slideshow');
        add_settings_field('bss_listPosition', __('Loop', 'bss-ca-slideshow'), array($this, 'bss_ca_slidshow_listPosition'), 'bss_img_slidshow', 'bss_ca_slideshow');
        //add_settings_field('bss_displayList', __('Display List', 'bss-ca-slideshow'), array($this, 'bss_ca_slidshow_displayList'), 'bss_img_slidshow', 'bss_ca_slideshow');
        add_settings_field('bss_adaptiveHeight', __('Stop On Hover', 'bss-ca-slideshow'), array($this, 'bss_ca_slidshow_adaptiveHeight'), 'bss_img_slidshow', 'bss_ca_slideshow');
        add_settings_field('bss_transitionDuration', __('Transition Duration', 'bss-ca-slideshow'), array($this, 'bss_ca_slidshow_transitionDuration'), 'bss_img_slidshow', 'bss_ca_slideshow');
    }
    public function bss_video_section_text()
    {
       echo "<h2>Responsive ca SlideShow Configuration</h2>";
    }
    public function bss_ca_slidshow_items(){
         $bss_slideshow_options = get_option('bss_ca_slidshow_options');
        echo "<select id='bss_items' name='bss_ca_slidshow_options[bss_items]'>";
        $know = array(1,2,3);
        foreach ($know as $v) {
            echo '<option value="' . $v . '"';
            if ($v == $bss_slideshow_options['bss_items']) {
                echo 'selected="selected"';
            }

            echo '>' . $v . '</option>';
        }

        echo "</select>";
        //echo '<span> Must Be Select Single Item "NO" </span>';
    }

    public function bss_ca_slidshow_displayControls() {
        $bss_slideshow_options = get_option('bss_ca_slidshow_options');
        echo "<select id='bss_displayControls' name='bss_ca_slidshow_options[bss_displayControls]'>";
        $know = array('Yes' => 'true', 'No' => 'false');
        foreach ($know as $key => $v) {
            echo '<option value="' . $v . '"';
            if ($v == $bss_slideshow_options['bss_displayControls']) {
                echo 'selected="selected"';
            }

            echo '>' . $key . '</option>';
        }

        echo "</select>";
    }

    public function bss_ca_slidshow_transitionEffect() {
        $bss_slideshow_options = get_option('bss_ca_slidshow_options');
        echo "<select id='bss_effect' name='bss_ca_slidshow_options[bss_effect]'>";
        $know = array('Yes' => 'true', 'No' => 'false');
        foreach ($know as $key => $v) {
            echo '<option value="' . $v . '"';
            if ($v == $bss_slideshow_options['bss_effect']) {
                echo 'selected="selected"';
            }

            echo '>' . $key . '</option>';
        }

        echo "</select>";
    }
    public function bss_ca_slidshow_caControls() {
        //echo "<select id='bss_caControls' name='bss_ca_slidshow_options[bss_caControls]'>";
        $bss_slideshow_options = get_option('bss_ca_slidshow_options');
        empty($bss_slideshow_options['bss_caControls']) ? $bss_slideshow_options['bss_caControls'] = 500 : $bss_slideshow_options['bss_caControls'];
        echo "<input id='bss_caControls' name='bss_ca_slidshow_options[bss_caControls]' size='20' type='text' value='{$bss_slideshow_options['bss_caControls']}' />";
    }
    public function bss_ca_slidshow_listPosition() {
        $bss_slideshow_options = get_option('bss_ca_slidshow_options');
        echo "<select id='bss_listPosition' name='bss_ca_slidshow_options[bss_listPosition]'>";
        $know = array('Yes' => 'true', 'No' => 'false');
        foreach ($know as $key => $v) {
            echo '<option value="' . $v . '"';
            if ($v == $bss_slideshow_options['bss_listPosition']) {
                echo 'selected="selected"';
            }

            echo '>' . $key . '</option>';
        }
        echo "</select>";
    }

    public function bss_ca_slidshow_adaptiveHeight() {
        $bss_slideshow_options = get_option('bss_ca_slidshow_options');
        echo "<select id='bss_adaptiveHeight' name='bss_ca_slidshow_options[bss_adaptiveHeight]'>";
        $know = array('Yes' => 'true', 'No' => 'false');
        foreach ($know as $key => $v) {
            echo '<option value="' . $v . '"';
            if ($v == $bss_slideshow_options['bss_adaptiveHeight']) {
                echo 'selected="selected"';
            }

            echo '>' . $key . '</option>';
        }
        echo "</select>";
    }

    public function bss_ca_slidshow_transitionDuration() {
        $bss_slideshow_options = get_option('bss_ca_slidshow_options');
        empty($bss_slideshow_options['bss_transitionDuration']) ? $bss_slideshow_options['bss_transitionDuration'] = 500 : $bss_slideshow_options['bss_transitionDuration'];
        echo "<input id='bss_transitionDuration' name='bss_ca_slidshow_options[bss_transitionDuration]' size='20' type='text' value='{$bss_slideshow_options['bss_transitionDuration']}' />";
    }
    public function bss_ca_slideshow_admin_css() {
        wp_enqueue_style('bss_ca_slideshow_admin_css', plugin_dir_url(__FILE__) . 'css/admin_style.css');
    }
    public function bss_ca_slideshow_load_wp_media_files() {
        wp_enqueue_media();
    }
}

$var = new Bss_ca_slideshow();
$var->bss_ca_slideshow_getInstance();

