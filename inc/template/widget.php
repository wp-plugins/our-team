<?php
/*
 * Short description
 * @author pearlcore <info@pearlcore.com>
 * 
 */
$args = array(
    'post_type' => 'team_member',
    'meta_key' => 'pc_member_order',
    'orderby' => 'meta_value',
    'order' => 'ASC'
);
$team = new WP_Query($args);
?>
<div id="pc_our_team" class="widget">
    <?php
    if ($team->have_posts()) {
        while ($team->have_posts()) {
            $team->the_post();
//                            echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID() ));
            ?>
            <div itemscope itemtype="http://schema.org/Person" class="pc_sidebar_team_member">
                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                    <?php
                    if (has_post_thumbnail())
                        echo the_post_thumbnail('medium');
                    else
                        echo '<img src="' . PC_TEAM_URL . 'inc/img/noprofile.jpg" class="attachment-medium wp-post-image"/>';
                    ?>  
                </a>
                <div class="pc_team_member_overlay">
                    <div itemprop="name" class="pc_team_member_name">
                        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                            <?php the_title() ?>
                        </a>
                    </div>
                    <div itemprop="jobtitle" class="pc_team_member_jobtitle">
                        <?php echo get_post_meta(get_the_ID(), 'team_member_title', true); ?>
                    </div>
                    <div class='icons'>

                        <?php // the_content(); ?>
                        <?php
                        $facebook = get_post_meta(get_the_ID(), 'team_member_facebook', true);
                        $twitter = get_post_meta(get_the_ID(), 'team_member_twitter', true);
                        $linkedin = get_post_meta(get_the_ID(), 'team_member_linkedin', true);
                        $gplus = get_post_meta(get_the_ID(), 'team_member_gplus', true);
                        $email = get_post_meta(get_the_ID(), 'team_member_email', true);
                        ?>
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
