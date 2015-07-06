<?php
/*
 * Short description
 * @author pearlcore <info@pearlcore.com>
 * 
 */
$args = $this->pc_get_args($group);
$members = new WP_Query($args);
?>
<style>
    .honeycombs .inner_span{
        background-color: #<?php echo $this->options['honeycomb_color']; ?>;
    }
    .pc_our_team_lightbox .name{ color: #<?php echo $this->options['honeycomb_color']; ?>; }
    #pc_our_team_lightbox .progress{ background: #<?php echo $this->options['honeycomb_color']; ?>;}
    .pc_our_team_lightbox.honeycomb .progress{ background: #<?php echo $this->options['honeycomb_color']; ?> !important;}
    .pc_our_team_lightbox.honeycomb .name{ color: #<?php echo $this->options['honeycomb_color']; ?>; }
    #pc_our_team_lightbox .pc_our_team_lightbox .title{ color: #<?php echo $this->options['honeycomb_color']; ?>; }

</style>

<div id="pc_our_team" class="hc sc-col3 honeycombs honeycombs-wrapper">
    <div class="honeycombs-inner-wrapper">
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
                <div itemscope="" itemtype="http://schema.org/Person" class="pc_team_member comb <?php echo $pc_single_view_class; ?>">
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
                    <span style="display: none;">
                        <b>
                            <?php if ('yes' == $this->options['name']) : ?>
                                <div itemprop="name" class="pc_team_member_name">
                                    <a href="<?php the_permalink() ?>" rel="bookmark" >                            
                                        <?php the_title() ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if ('yes' == $this->options['title']) : ?>
                                <div itemprop="jobtitle" class="pc_team_member_jobtitle">
                                    <?php echo get_post_meta(get_the_ID(), 'team_member_title', true); ?>
                                </div>
                            <?php endif; ?>


                            <div class="pc_team_content" style="display:none;">
                                <?php the_content(); ?>
                            </div>                            

                            <div class='icons <?php echo 'yes' == $this->options['social'] ? '' : 'hidden'; ?>' style="display:none;">
                                <?php
                                
                                $this->pc_get_social(get_the_ID());
                                ?>
                            </div>                          

                            <div class="pc_team_skills">
                                <?php echo $this->pc_get_skills_html(get_the_ID()); ?>
                            </div>  
                        </b>
                    </span>
                </div>
                <?php
            }
        } else {
            echo 'There are no team members to display';
        }
        ?>
    </div>
</div>