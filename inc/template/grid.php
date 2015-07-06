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
    $main_classes = $template . ' sc-col' . $this->options['columns'];
    if ($template == 'grid_image'):
        $pc_hide = 'style="display:none;"';
        $main_classes = 'grid sc-col' . $this->options['columns'];
    endif;
else:
    $main_classes = 'grid sc-col' . $this->options['columns'];
endif;
?>
<div id="pc_our_team" class="<?php echo $main_classes; ?>">
    <div class="clear"></div>
    <?php
    if ($members->have_posts()) {
        if (isset($this->options['single_template']) && !empty($this->options['single_template'])):
            $pc_single_view_class = 'pc_team_single_' . $this->options['single_template'];
        else:
            $pc_single_view_class = '';
        endif;
        while ($members->have_posts()) {
            $members->the_post();
            ?>
            <div itemscope itemtype="http://schema.org/Person" class="pc_team_member <?php echo $pc_single_view_class; ?>">
                <div class="pc_team_member_inner">

                    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title() ?>" class="pc_team_single_popup" >
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
                    </a>

                    <?php if ('yes' == $this->options['name']) : ?>
                        <div itemprop="name" class="pc_team_member_name" <?php echo $pc_hide; ?>>
                            <a href="<?php the_permalink() ?>" rel="bookmark" >                            
                                <?php the_title() ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ('yes' == $this->options['title']) : ?>
                        <div itemprop="jobtitle" class="pc_team_member_jobtitle" <?php echo $pc_hide; ?>>
                            <?php echo get_post_meta(get_the_ID(), 'team_member_title', true); ?>
                        </div>
                    <?php endif; ?>


                    <div class="pc_team_content" <?php echo $pc_hide; ?>>
                        <?php the_content(); ?>
                    </div>

                    <div class='icons <?php echo 'yes' == $this->options['social'] ? '' : 'hidden'; ?>' <?php echo $pc_hide; ?>>

                        <?php
                        $facebook = get_post_meta(get_the_ID(), 'team_member_facebook', true);
                        $twitter = get_post_meta(get_the_ID(), 'team_member_twitter', true);
                        $linkedin = get_post_meta(get_the_ID(), 'team_member_linkedin', true);
                        $gplus = get_post_meta(get_the_ID(), 'team_member_gplus', true);
                        $email = get_post_meta(get_the_ID(), 'team_member_email', true);

                        $this->pc_get_social(get_the_ID());
                        ?>

                    </div>

                    <div class="pc_team_skills" <?php echo $pc_hide; ?>>
                        <?php echo $this->pc_get_skills_html(get_the_ID()); ?>
                    </div>


                    <div class="pc_team_member_overlay"></div>
                    <?php if ($this->options['single_template'] == 'standard'): ?>
                        <div class="pc_team_more">
                            <a href="<?php the_permalink() ?>" rel="bookmark" class=""> 
                                <img src="<?php echo PC_TEAM_URL . 'inc/img/more.png' ?>"/>
                            </a>
                        </div>
                    <?php endif;
                    ?>
                </div>

            </div>
            <?php
        }
    } else {
        echo 'There are no team members to display';
    }
    ?>
    <div class="clear"></div>
</div>
