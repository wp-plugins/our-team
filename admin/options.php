<?php include_once 'setting.php'; extract( get_option('pearlcore_team_options')); ?>
<div class="width70 left">
    <p>To display the Team, copy and paste this shortcode into the page or widget where you want to show it. 
        <input type="text" readonly="readonly" value="[our-team]" style="width: 100px" onfocus="this.select()"/>
    </p>
    
    <form name="pc_our_team_post_form" method="post" action="" enctype="multipart/form-data">
        <table class="widefat">
            <thead>
                <tr>
                    <th colspan="2"><b>Team View Settings</b></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Template</td>
                    <td>
                        <select name="pearlcore_team_options[template]" id="pc_our_team_template">
                            <option value="" >Select Template</option>
                            <option value="grid" <?php echo 'grid' == esc_attr( $template ) ? 'selected=selected' : ''; ?>>Grid - Boxes</option>
                            <option value="grid_circles" <?php echo 'grid_circles' == esc_attr( $template ) ? 'selected=selected' : ''; ?>>Grid - Circles</option>
                            <option value="grid_circles2" <?php echo 'grid_circles2' == esc_attr( $template ) ? 'selected=selected' : ''; ?>>Grid Circles 2 </option>
                            <option value="grid_image" <?php echo 'grid_image' == esc_attr( $template ) ? 'selected=selected' : ''; ?>>Grid Image</option>
                            <option value="honey_comb"  <?php echo 'honey_comb' == esc_attr( $template ) ? 'selected=selected' : ''; ?>>Honey Comb</option>
                            <option value="carousel" <?php echo 'carousel' == esc_attr( $template ) ? 'selected=selected' : ''; ?>>Carousel</option>
                            <option value="stacked" <?php echo 'stacked' == esc_attr( $template ) ? 'selected=selected' : ''; ?>>List - Stacked </option>
                            <option value="brick" <?php echo 'brick' == esc_attr( $template ) ? 'selected=selected' : ''; ?>>Brick Style</option>
                            <option value="round" <?php echo 'round' == esc_attr( $template ) ? 'selected=selected' : ''; ?>>Round About</option>
                            <option value="pager" <?php echo 'pager' == esc_attr( $template ) ? 'selected=selected' : ''; ?>>Pager Layout</option>
                        </select>
                    </td>
                </tr>
                
                <tr id="columns-row">
                    <td>Grid Columns</td>
                    <td>
                        <select name="pearlcore_team_options[columns]">
                            <option value="2" <?php echo '2' == esc_attr ( $columns ) ? 'selected=selected' : ''; ?>>2</option>
                            <option value="3" <?php echo '3' == esc_attr ( $columns ) ? 'selected=selected' : ''; ?>>3</option>
                            <option value="4" <?php echo '4' == esc_attr( $columns ) ? 'selected=selected' : ''; ?>>4</option>
                            <option value="5" <?php echo '5' == esc_attr( $columns ) ? 'selected=selected' : ''; ?>>5</option>
                            <option value="10" <?php echo '10' == esc_attr( $columns ) ? 'selected=selected' : ''; ?>>10</option>
                        </select>
                    </td>
                </tr>                

                <tr id="height-row">
                    <td>Row Height</td>
                    <td>
                        <input type="text" name="pearlcore_team_options[height]" value="<?php echo esc_attr( $height ); ?>" class="width25"/>px
                    </td>
                </tr>                

                <tr id="margin-row">
                    <td>Margin</td>
                    <td>
                        <select name="pearlcore_team_options[margin]">
                            <option value="0" <?php echo '0' == esc_attr( $margin ) ? 'selected=selected' : ''; ?>>No margin</option>
                            <option value="5" <?php echo '5' == esc_attr( $margin ) ? 'selected=selected' : ''; ?>>5</option>
                            <option value="10" <?php echo '10' == esc_attr( $margin ) ? 'selected=selected' : ''; ?>>10</option>
                            <option value="15" <?php echo '15' == esc_attr( $margin ) ? 'selected=selected' : ''; ?>>15</option>
                        </select>px
                    </td>
                </tr>                
                
                <tr id="social_icons_row">
                    <td>Display Social Icons</td>
                    <td>
                        <select name="pearlcore_team_options[social]">
                            <option value="yes" <?php echo 'yes' == esc_attr( $social ) ? 'selected=selected' : ''; ?>>Yes</option>
                            <option value="no" <?php echo 'no' == esc_attr( $social ) ? 'selected=selected' : ''; ?>>No</option>
                        </select>
                    </td>
                </tr>
                
                <tr id="social_icons_style_row">
                    <td>Social Icons Style</td>
                    <td>
                        <select name="pearlcore_team_options[social_style]">
                            <option value="square" <?php echo 'square' == esc_attr( $social_style ) ? 'selected=selected' : ''; ?>>Square</option>
                            <option value="circle" <?php echo 'circle' == esc_attr( $social_style ) ? 'selected=selected' : ''; ?>>Circle</option>
                        </select>
                    </td>
                </tr>
                
                <tr id="">
                    <td>Display Name</td>
                    <td>
                        <select name="pearlcore_team_options[name]">
                            <option value="yes" <?php echo 'yes' == esc_attr( $name ) ? 'selected=selected' : ''; ?>>Yes</option>
                            <option value="no" <?php echo 'no' == esc_attr( $name ) ? 'selected=selected' : ''; ?>>No</option>
                        </select>
                    </td>
                </tr>
                
                <tr id="">
                    <td>Display Title</td>
                    <td>
                        <select name="pearlcore_team_options[title]">
                            <option value="yes" <?php echo 'yes' == esc_attr( $title )? 'selected=selected' : ''; ?>>Yes</option>
                            <option value="no" <?php echo 'no' == esc_attr( $title ) ? 'selected=selected' : ''; ?>>No</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Number of members to display</td>
                    <td>
                        <input type="text" value="<?php echo esc_attr( $member_count ); ?>" name="pearlcore_team_options[member_count]" placeholder="number of members to show"/><br>
                        <em>Set to -1 to display all members</em>
                    </td>
                </tr>
                <tr>
                    <td>Main Color</td>
                    <td>
                        <input class="wp_popup_color width25" type="text" value="<?php echo esc_attr( $text_color ); ?>" name="pearlcore_team_options[text_color]"/>
                    </td>
                </tr>
                <tr id="">
                    <td>Honey Comb Color</td>
                    <td>
                        <input class="wp_popup_color width25" type="text" value="<?php echo esc_attr($honeycomb_color);?>" name="pearlcore_team_options[honeycomb_color]"/>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table class="widefat">
            <thead>
                <tr>
                    <th colspan="2"><b>Single Member View Settings</b></th>
                </tr>
                <tr>
                    <td>Template</td>
                    <td>
                        <select name="pearlcore_team_options[single_template]">
                            <option>Select Template</option>
                            <option value="standard" <?php echo 'standard' == esc_attr( $single_template ) ? 'selected=selected' : ''; ?>>Theme Default (single post)</option>
                            <option value="popup" <?php echo 'popup' == esc_attr( $single_template ) ? 'selected=selected' : ''; ?>>Popup Card</option>
                            <option value="panel" <?php echo 'panel' == esc_attr( $single_template ) ? 'selected=selected' : ''; ?>>Panel</option>
                        </select>
                    </td>
                </tr>
                
                <tr id="">
                    <td>Display Social Icons</td>
                    <td>
                        <select name="pearlcore_team_options[single_social]">
                            <option value="yes" <?php echo 'yes' == esc_attr( $single_social ) ? 'selected=selected' : ''; ?>>Yes</option>
                            <option value="no" <?php echo 'no' == esc_attr( $single_social ) ? 'selected=selected' : ''; ?>>No</option>
                        </select>
                    </td>
                </tr>
                
                <tr id="">
                    <td>Popup Image Style</td>
                    <td>
                        <select name="pearlcore_team_options[single_image_style]">
                            <option value="square" <?php echo 'square' == esc_attr( $single_image_style ) ? 'selected=selected' : ''; ?>>Square</option>
                            <option value="circle" <?php echo 'circle' == esc_attr( $single_image_style ) ? 'selected=selected' : ''; ?>>Circle</option>
                        </select>
                    </td>
                </tr>
                
                <tr id="">
                    <td>Display Skills</td>
                    <td>
                        <select name="pearlcore_team_options[single_skills]">
                            <option value="yes" <?php echo 'yes' == esc_attr( $single_skills ) ? 'selected=selected' : ''; ?>>Yes</option>
                            <option value="no" <?php echo 'no' == esc_attr( $single_skills ) ? 'selected=selected' : ''; ?>>No</option>
                        </select>
                    </td>
                </tr>

            </thead>
        </table>
        
        <input type="hidden" name="pearlcore_team_options[redirect]" value=""/>
        <input type="submit" name="pc_our_team_save" value="Update" class="button button-primary" />
        
    </form>
    
<div class="clear"></div>

    <table class="widefat">
        <thead>
            <tr>
                <th colspan="4">Screenshots</th>
            </tr>
            <tr>
                <td>
                    Grid Boxes &amp; Grid Circles Demo<br>
                    <img src="<?php echo PC_TEAM_URL ?>screenshot-1.jpg" width="100%">
                </td>
                <td>
                    Carousel Demo<br>
                    <img src="<?php echo PC_TEAM_URL ?>screenshot-6.jpg" width="100%">
                </td>
                <td>
                    Honeycomb Demo<br>
                    <img src="<?php echo PC_TEAM_URL ?>screenshot-4.jpg" width="100%">
                </td>
                <td>
                    Stacked List Demo<br>
                    <img src="<?php echo PC_TEAM_URL ?>screenshot-5.jpg" width="100%">
                </td>
                <td>
                    Popup Card Demo<br>
                    <img src="<?php echo PC_TEAM_URL ?>capture.JPG" width="100%">
                </td>
                <td></td>
            </tr>
        </thead>
    </table>


</div>


</div>

<script>
    jQuery(document).ready(function($) {
        $("#pc_popup_shortcode").focusout(function() {
            var shortcode = jQuery(this).val();
            shortcode = shortcode.replace(/"/g, "").replace(/'/g, "");
            jQuery(this).val(shortcode);
        });
    });

</script>