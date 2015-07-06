<?php
/*
 * Short description
 * @author pearlcore <info@pearlcore.com>
 * 
 */
$args = $this->pc_get_args($group);
$members = new WP_Query($args);
?>
<div id="pc_our_team" class="wapper_our_team">

    <?php if ($members->have_posts()) : ?>
        <ul id="content_our_team" class="roundabout-holder">
            <?php
            if (isset($this->options['single_template']) && !empty($this->options['single_template'])):
                $pc_single_view_class = 'pc_team_single_' . $this->options['single_template'];
            else:
                $pc_single_view_class = '';
            endif;
            while ($members->have_posts()) :
                $members->the_post();
                ?>
                <li class="roundabout-moveable-item pc_team_member <?php echo $pc_single_view_class; ?>" style="">
                    <div class="our_team-image">
                        <?php
                        if (has_post_thumbnail()) {
                            $medium_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($members->ID), 'medium');
                            $sa_user_image = $medium_image_url[0];
                        } else {
                            $sa_user_image = PC_TEAM_URL . 'inc/img/noprofile.jpg';
                        }
                        ?>
                        <img width="234" height="300" src="<?php echo $sa_user_image; ?>" 
                             class="attachment-medium attachment-our_team wp-post-image" alt="" title="">
                    </div>
                    <div class="content_team">
                        <div class="out_team_title">
                            <h4  itemprop="name" class="pc_team_member_name">
                                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title() ?>"><?php the_title() ?></a>
                            </h4>
                            <div class="regency" itemprop="jobtitle">
                                <?php echo get_post_meta(get_the_ID(), 'team_member_title', true); ?>
                            </div>
                        </div>
                        <div class="hidden_child">
                            <div class="description">
                                <div class="excerpt">
                                    <?php
                                    $content = get_the_content();
                                    $pc_content = strip_tags($content);
                                    echo substr($pc_content, 0, 200);
                                    ?>
                                </div>
                            </div>
                            <div class="icons">
                                <?php
                                
                                $this->pc_get_social(get_the_ID());
                                ?>
                            </div>
                        </div>
                        <div class="pc_team_content hidden">
                            <?php the_content(); ?>
                        </div>
                        <div class="pc_team_skills hidden">
                            <?php echo $this->pc_get_skills_html(get_the_ID()); ?>
                        </div>
                    </div>
                    <div class="clear"></div>
                </li>
                <?php
            endwhile;
            ?>
        </ul>
        <div class="nav_team">
            <a href="#" class="next_team"><span class="inner_icon"><span class="icon"><i class="fa fa-angle-right"></i></span></span></a>
            <a href="#" class="prev_team"><span class="inner_icon"><span class="icon"><i class="fa fa-angle-left"></i></span></span></a>
        </div>
        <?php
    else:

    endif;
    ?>

</div>


