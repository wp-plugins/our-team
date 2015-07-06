<?php
/*
 * Short description
 * @author pearlcore <info@pearlcore.com>
 * 
 */
$args = $this->pc_get_args($group);
$members = new WP_Query($args);
$pc_hide = 'style="display:none;"';
?>
<?php
$main_classes = '';
?>
<div id="" class="<?php echo $main_classes; ?>">
    <div class="clear"></div>
    <?php if ($members->have_posts()) { ?>
        <ul class="dvteamgrid" id="pc_our_team_masonry">
            <?php
            if (isset($this->options['single_template']) && !empty($this->options['single_template'])):
                    $pc_single_view_class = 'pc_team_single_' . $this->options['single_template'];
                else:
                    $pc_single_view_class = '';
                endif;
            while ($members->have_posts()) {
                $members->the_post();
                ?>
                <li class="pc_team_member">
                    <figure itemscope itemtype="http://schema.org/Person" class="pc_team_member <?php echo $pc_single_view_class; ?>">

                        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title() ?>" class="<?php echo $pc_single_view_class; ?>" >
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
                            <div class="dv-member-zoom dv-zoomout"></div>
                        </a>
                        <figcaption>
                            <div class="dv-member-desc">
                                <div><span class="dv-member-name"><?php the_title() ?></span></div>
                                <div><span class="dv-member-info">
                                    <?php echo get_post_meta(get_the_ID(), 'team_member_title', true); ?></span>
                                </div>
                            </div>
                        </figcaption>
                    </figure>
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
                        

                        $this->pc_get_social(get_the_ID());
                        ?>
                    </div>
                    <div class="pc_team_skills" <?php echo $pc_hide; ?>>
                        <?php echo $this->pc_get_skills_html(get_the_ID()); ?>
                    </div>
                    <div class="pc_team_member_overlay"></div>
                </li>

            <?php }
            ?>
        </ul>
        <?php
    } else {
        echo 'There are no team members to display';
    }
    ?>
    <div class="clear"></div>
</div>
