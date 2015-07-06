<?php
/*
 * Short description
 * @author pearlcore <info@pearlcore.com>
 * 
 */
$args = $this->pc_get_args($group);
$members = new WP_Query($args);
?>
<div id="pc_our_team" class="stacked">
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
            <div itemscope="" itemtype="http://schema.org/Person" class="pc_team_member ">
                <div class="pc_team_member_left">

                    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title() ?>" class="<?php echo $pc_single_view_class; ?>">
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
                        <h2 itemprop="name" class="pc_team_member_name">
                            <a href="<?php the_permalink() ?>" title="<?php the_title() ?>" rel="bookmark" class="<?php echo $pc_single_view_class; ?>">                            
                                <?php the_title() ?>
                            </a>
                        </h2>
                    <?php endif; ?>
                    <?php if ('yes' == $this->options['title']) : ?>
                        <h3 itemprop="jobtitle" class="pc_team_member_jobtitle">
                            <?php echo get_post_meta(get_the_ID(), 'team_member_title', true); ?>
                        </h3>
                    <?php endif; ?>
                </div>
                <div class="pc_team_member_right">
                    <div class='icons <?php echo 'yes' == $this->options['social'] ? '' : 'hidden'; ?>'>
                        <?php
                        $this->pc_get_social(get_the_ID());
                        ?>
                    </div>                   
                    <?php the_content(); ?>
                </div>
                
                <div class="pc_team_content hidden"><?php the_content(); ?></div>
                <div class="pc_team_skills hidden">
                    <?php echo $this->pc_get_skills_html(get_the_ID()); ?>
                </div>                
            </div>
            <?php
        }
    } else {
        echo 'There are no team members to display';
    }
    ?>
</div>
