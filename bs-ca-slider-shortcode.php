<?php
class Bss_ca_slidshow_shortcode {

    public function __construct() {
        
        add_shortcode('bs_carousel_slider', array($this, 'show_shortcode_bss_ca_slideshow'));
        add_action('wp_enqueue_scripts', array($this, 'bss_ca_slideshow_enqueue_scripts'));
    }

    private function bss_ca_slideshow($atts, $content = NULL) {
        extract(shortcode_atts(
                        array(
            'id' => '',
                        ), $atts)
        );
        $query_args = array(
            'p' => (!empty($id)) ? $id : -1,
            'posts_per_page' => -1,
            'post_type' => 'bss_ca_slideshow',
            'order' => 'DESC',
            'orderby' => 'menu_order',
        );
        $wp_query = new WP_Query($query_args);
        if ($wp_query->have_posts()):while ($wp_query->have_posts()) : $wp_query->the_post();
                return $data_tables = get_post_meta($id, '_bss_ca_slideshow_group', true);
            endwhile;
        else: echo 'No ca slideshow Found';
        endif;
    }
    public function bss_slideshow_get_option()
    {
        $bss_slideshow_options_array= array(
            'bss_ca_slidshow_options'=>array(
                'bss_displayControls' =>'true',
                'bss_transitionDuration'=>300,
                'bss_caControls'=>400,
                'bss_autoSlide'=>'true',
                'bss_listPosition'=>'true',
                'bss_effect'=>'true',
                'bss_items'=>1,
                'bss_adaptiveHeight'=>'true'
            ));
        foreach ($bss_slideshow_options_array as $key => $value) {
            return $value;
        }
    }
    public function show_shortcode_bss_ca_slideshow($atts, $content = NULL) {
        $bss_slideshow_options =get_option('bss_ca_slidshow_options');

        if(empty($bss_slideshow_options)){
            $bss_slideshow_options=$this->bss_slideshow_get_option();
        }
        $bss_navigation = $bss_slideshow_options['bss_displayControls'];
        $bss_slideSpeed = $bss_slideshow_options['bss_transitionDuration'];
        $bss_pagination = $bss_slideshow_options['bss_caControls'];
        $bss_single_item = $bss_slideshow_options['bss_autoSlide'];
        $bss_autoplay = $bss_slideshow_options['bss_effect'];
        $bss_loop = $bss_slideshow_options['bss_listPosition'];
        $bss_stopOnHover = $bss_slideshow_options['bss_adaptiveHeight'];
        $bss_items = $bss_slideshow_options['bss_items'];
        
        $data_values = $this->bss_ca_slideshow($atts, $content = NULL);
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery("#owl-demo").owlCarousel({
                navigation : <?php echo $bss_navigation;?>,
                slideSpeed : <?php echo $bss_slideSpeed;?>,
                paginationSpeed : <?php echo $bss_pagination;?>,
                //singleItem : <?php echo $bss_single_item;?>,
                items:<?php echo $bss_items;?>,
                loop:<?php echo $bss_loop;?>,
                autoPlay: <?php echo $bss_autoplay;?>,
                stopOnHover : <?php echo $bss_stopOnHover;?>,
                navigationText: ["<img src=<?php echo plugin_dir_url(__FILE__).'/images/left.png';?>>","<img src='<?php echo plugin_dir_url(__FILE__).'/images/right.png';?>'>"]
              
                  });
                });
        </script>

        <style>

            #owl-demo .item img{

                display: block;

                width: 100%;

                height: auto;

            }

        </style> 
        <?php if($bss_navigation=='true'){?>
        <style type="text/css">

         .owl-pagination {

            position: abssolute;

            bottom: 0%;

            margin: 0 auto;

            display: block;

            width: 100%;  

        }

        @media screen and (max-width: 480px) {
            .owl-pagination {
            position: abssolute;
            bottom: 0%;
            }

        }
        </style> 
        <?php } else{?>
            <style>
             .owl-pagination{ display: none;}
            </style>

        <?php } ?>   
    <div class="mod_je_responsive_ca_slideshow">  
        <div id="demo">
            <div id="owl-demo" class="owl-carousel">
                <?php
                if (!empty($data_values)) {
                    foreach ($data_values as $key => $data_table):?>
                     <div class="item">
                          <div class="slider-img">
                              <a href="<?php echo $data_table['bss_img_slidshow_title']?>" target="_self"><img src="<?php echo $data_table['bss_img_slidshow_pic']  ;?>" alt=""></a>
                          </div>
                           <!--<div class="slider-description">
                              <span><?php// echo $data_table['bss_img_slidshow_title']?></span>
                          </div>-->
                      </div>

                       <?php
                        //echo '<li><img src="' . $data_table['bss_img_slidshow_pic'] . '"><span>' . $data_table['bss_img_slidshow_title'] . '</span></li>';
                    endforeach;
                }
                ?>
            </div>

        </div>

    </div>

<div style="clear:both;"></div>
        <?php
        $content = ob_get_contents();
        ob_get_clean();
        return $content;
    }

    public function bss_ca_slideshow_enqueue_scripts() {
        wp_enqueue_style('bss_ca_css', plugin_dir_url(__FILE__) . 'css/owl.carousel.css');
        wp_enqueue_style('bss_ca_css1', plugin_dir_url(__FILE__) . 'css/owl.theme.css');
        wp_enqueue_script('bss_ca_slideshow_js', plugin_dir_url(__FILE__) . 'js/owl.carousel.js', array('jquery'), true);
        //wp_enqueue_script('bss_ca_slideshow_js2', plugin_dir_url(__FILE__) . 'js/pgwslider.js', array('jquery'), true);
    }

}

new Bss_ca_slidshow_shortcode();


