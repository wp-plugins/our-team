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
?>
<div id="pc_our_team" class="<?php echo $main_classes; ?>">
    <div class="clear"></div>
    <?php
    if ($members->have_posts()) {
        while ($members->have_posts()) {
            $count ++;
            $members->the_post();
            if (($count % 2) == 0):
                $pc_add_class = 'right grey divider';
            else:
                $pc_add_class = 'grey';
            endif;
            ?>
            <div itemscope itemtype="http://schema.org/Person" class="team_bio group <?php echo $pc_add_class; ?>">
                <div class="center">

                    <div class="view <?php the_title() ?>" rel="gallery">
                        <?php
                        if (has_post_thumbnail()) {
                            $medium_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($members->ID), 'medium');
                            $sa_user_image = $medium_image_url[0];
                            echo '<img src="' . $sa_user_image . '" class="attachment-medium wp-post-image"/>';
                        } else {
                            $sa_user_image = PC_TEAM_URL . 'inc/img/noprofile.jpg';
                            echo '<img src="' . $sa_user_image . '" class="attachment-medium wp-post-image"/>';
                        }
                        ?>
                    </div>
                    <div class="team_single_detail">
                        <?php if ('yes' == $this->options['name']) : ?>
                            <h3>
                                <?php the_title() ?>
                            </h3>
                        <?php endif; ?>

                        <?php if ('yes' == $this->options['title']) : ?>
                            <h4>
                                <?php echo get_post_meta(get_the_ID(), 'team_member_title', true); ?>
                            </h4>
                        <?php endif; ?>
                        <?php the_content(); ?>
                        <div class="social_icons">
                            <?php
                            $facebook = get_post_meta(get_the_ID(), 'team_member_facebook', true);
                            $twitter = get_post_meta(get_the_ID(), 'team_member_twitter', true);
                            $linkedin = get_post_meta(get_the_ID(), 'team_member_linkedin', true);
                            $gplus = get_post_meta(get_the_ID(), 'team_member_gplus', true);
                            $email = get_post_meta(get_the_ID(), 'team_member_email', true);
                            ?>
                            <?php if ($facebook): ?>
                                <a href="<?php echo $facebook; ?>" target="_blank" class="facebook" ></a>
                            <?php endif; ?>
                            <?php if ($twitter): ?>
                                <a href="<?php echo $twitter; ?>" target="_blank" class="twitter" ></a>
                            <?php endif; ?>
                            <?php if ($linkedin): ?>
                                <a href="<?php echo $linkedin; ?>" target="_blank" class="linkedin" ></a>
                            <?php endif; ?>
                            <?php if ($gplus): ?>
                                <a href="<?php echo $gplus; ?>" target="_blank" class="googleplus" ></a>
                            <?php endif; ?>
                            <?php if ($email): ?>
                                <a href="mailto:<?php echo $email; ?>"class="email" ></a>
                            <?php endif; ?>
                        </div>
                        <div class="pc_team_skills">
                            <h2>Skills</h2>
                            <?php echo $this->pc_get_skills_html(get_the_ID()); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo 'There are no team members to display';
    }
    ?>
</div>
