<?php
/*
 * Short description
 * @author pearlcore <info@pearlcore.com>
 * 
 */
$args = $this->pc_get_args($group);
$members = new WP_Query($args);
$pc_hide = '';
?>
<?php
if (isset($template) && !empty($template)):
    $main_classes = 'team team_list group brick';
else:
    $main_classes = 'grid sc-col' . $this->options['columns'];
endif;
$count = 1;
$pc_full_content = '';
?>
<div class="pc_gri2">
    <div class="clear"></div>
    <div class="pc_left_column">
        <div class="team-list">
            <?php
            if ($members->have_posts()) {
                while ($members->have_posts()) {
                    $members->the_post();
                    if (isset($count) && $count == 1):
                        $pc_hide = 'style="display:block;"';
                        $pc_current_element = 'current';
                    else:
                        $pc_hide = '';
                    $pc_current_element = '';
                    endif;
                    ?>
                    <div itemscope itemtype="http://schema.org/Person" class="team-item small-6 large-4 column <?php echo $pc_current_element;?>" data-team="<?php echo $count; ?>">

                        <div class="pc_member_image">
                            <?php
                            if (has_post_thumbnail()) {
                                $medium_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($members->ID), 'medium');
                                $sa_user_image = $medium_image_url[0];
                                echo '<img src="' . $sa_user_image . '" class="attachment-medium wp-post-image team-img"/>';
                            } else {
                                $sa_user_image = PC_TEAM_URL . 'inc/img/noprofile.jpg';
                                echo '<img src="' . $sa_user_image . '" class="attachment-medium wp-post-image team-img"/>';
                            }
                            ?>
                        </div>
                        <div class="team-desc anim">
                            <h3><?php the_title() ?></h3>
                            <p><?php echo get_post_meta(get_the_ID(), 'team_member_title', true); ?></p>
                        </div>
                        <?php
                        $pc_full_content .= '<div id="team' . $count . '" class="team-content current" ' . $pc_hide . '>';
                        $pc_full_content .= '<img src="' . $sa_user_image . '" class="attachment-medium wp-post-image team-img" width="400" height="400"/>';
                        $pc_full_content .= '<div class="team-content-text">';
                        $pc_full_content .= get_the_content();

                        /** Social Links Start */
                        $pc_full_content .= '<div class="social_icons team_bio">';

                        $facebook = get_post_meta(get_the_ID(), 'team_member_facebook', true);
                        $twitter = get_post_meta(get_the_ID(), 'team_member_twitter', true);
                        $linkedin = get_post_meta(get_the_ID(), 'team_member_linkedin', true);
                        $gplus = get_post_meta(get_the_ID(), 'team_member_gplus', true);
                        $email = get_post_meta(get_the_ID(), 'team_member_email', true);
                        if ($facebook):
                            $pc_full_content .= '<a href="' . $facebook . '" target="_blank" class="facebook" ></a>';
                        endif;
                        if ($twitter):
                            $pc_full_content .= '<a href="' . $twitter . '" target="_blank" class="twitter" ></a>';
                        endif;
                        if ($linkedin):
                            $pc_full_content .= '<a href="' . $linkedin . '" target="_blank" class="linkedin" ></a>';
                        endif;
                        if ($gplus):
                            $pc_full_content .= '<a href="' . $gplus . '" target="_blank" class="googleplus" ></a>';
                        endif;
                        if ($email):
                            $pc_full_content .= '<a href="mailto:' . $email . '"class="email" ></a>';
                        endif;
                        $pc_full_content .= '</div>';
                        /** Social Links End */
                        /** Skills Start */
                        $pc_full_content .= '<div class="pc_team_skills">';
                        $pc_full_content .= '<h2>Skills</h2>';
                        $pc_full_content .= $this->pc_get_skills_html(get_the_ID());
                        $pc_full_content .= '</div>';
                        /** Skills End */
                        $pc_full_content .= '</div>';
                        $pc_full_content .= '</div>';
                        ?>
                    </div>
                    <?php
                    $count++;
                }
            } else {
                echo 'There are no team members to display';
            }
            ?>
        </div>
    </div>
    <div class="pc_right_column team-item-holder">
        <?php echo $pc_full_content; ?>
    </div>
</div>