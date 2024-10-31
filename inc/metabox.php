<?php
for ($i = 0; $i < 9; $i++) {
    if (empty($data_tables[$i]['bss_img_slidshow_title']))
        $data_tables[$i]['bss_img_slidshow_title'] = '';
    if (empty($data_tables[$i]['bss_img_slidshow_pic']))
        $data_tables[$i]['bss_img_slidshow_pic'] = '';
    ?>
    <div class="bss_ca_slideshow">
        <h3 class="table_title">slideshow <?php echo $i + 1 ?></h3>
        <div class="bss_ca_slideshow_des">
            <div class="bss_ca_slideshow_left">
                <label class="bss_price_table_label_title" >Link</label>
            </div>
            <div class="bss_ca_slideshow_right">
                <input class="price_table_cl" type="text" name="<?= 'bss_ca_slideshow_group[' . $i . '][bss_img_slidshow_title]'; ?>" value="<?= esc_attr($data_tables[$i]['bss_img_slidshow_title']); ?>"></input>
            </div>

            <div class="bss_ca_slideshow_left">
                <label class="bss_price_table_label" >Image</label>
            </div>
            <div class="bss_ca_slideshow_right">
                <input class="price_table_cl bss_img_value_<?= $i ?>" type="text" value="<?= esc_attr($data_tables[$i]['bss_img_slidshow_pic']); ?>" disabled></input>
                <input class="price_table_cl bss_img_value_<?= $i ?>" type="hidden" name="<?= 'bss_ca_slideshow_group[' . $i . '][bss_img_slidshow_pic]'; ?>" value="<?= $data_tables[$i]['bss_img_slidshow_pic']; ?>"></input>

                <a href="#" id='bss_add_ca_slideshow_<?= $i ?>' class="bss_ca_slideshow_btn">Upload</a>
            </div>
            <div class="bss_img_show">
                <img class="bss_ca_slideshow_img_<?= $i ?>" src="<?php echo esc_attr($data_tables[$i]['bss_img_slidshow_pic']); ?>"></img>
            </div>
        </div>

    </div>
    <script>
        jQuery.noConflict();
        jQuery(function ($) {

            var frame,
                    bss_img_slideshow_metaBox = $('#bss_ca_slideshow_meta_id'),
                    bss_add_img = bss_img_slideshow_metaBox.find('#bss_add_ca_slideshow_<?= $i ?>'),
                    imgContainer = bss_img_slideshow_metaBox.find('.bss_ca_slideshow_img_<?= $i ?>'),
                    imgIdInput = bss_img_slideshow_metaBox.find('.bss_img_value_<?= $i ?>');

            bss_add_img.on('click', function (event) {

                event.preventDefault();
                if (frame) {
                    frame.open();
                    return;
                }
                frame = wp.media({
                    title: 'Select or Upload Media Of Your Chosen Persuasion',
                    button: {
                        text: 'Use this media'
                    },
                    multiple: false
                });
                frame.on('select', function () {
                    var attachment = frame.state().get('selection').first().toJSON();
                    console.log(imgContainer.attr('src', attachment.url));
                    imgIdInput.attr('value', attachment.url);
                });
                frame.open();
            });

        });
        
    </script>

<?php } ?>








