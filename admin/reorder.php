<?php include_once 'setting.php'; ?>

<div class="width70 left">
    <table class="widefat">
        <thead>
            <tr>
                <th><b>Drag & Drop the member's pictures to sort them in the order you want them to appear</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <ul class="pc_sortable grid" data-action="<?php echo PC_TEAM_PATH; ?>">
                        <?php
                        $args = array(
                            'post_type' => 'team_member',
                            'meta_key' => 'pc_member_order',
                            'orderby' => 'meta_value_num',
                            'order' => 'ASC',
                            'posts_per_page' => -1,
                        );
                        $members = new WP_Query($args);
                        if ($members->have_posts()) {
                            while ($members->have_posts()) {
                                $members->the_post();
                                $id = get_the_ID();
                                $order = get_post_meta($id, 'pc_member_order', true);
                                if (has_post_thumbnail())
                                    $thumb_url = wp_get_attachment_url(get_post_thumbnail_id($id));
                                else
                                    $thumb_url = PC_TEAM_URL . 'inc/img/noprofile.jpg';
                                ?>
                                <li id="<?php echo $id; ?>" itemscope itemtype="http://schema.org/Person" class="pc_team_member ui-state-default" data-order="<?php echo $order; ?>">
                                    <div class="pc_team_member_inner">
                                        <img src="<?php echo $thumb_url; ?>" />
                                        <div class="pc_team_member_overlay">
                                            <?php the_title() ?>
                                        </div>
                                        <div itemprop="jobtitle" class="pc_team_member_jobtitle">
                                            <?php echo get_post_meta($id, 'team_member_title', true); ?>
                                        </div>
                                    </div>
                                </li>
                                <?php
                            }
                        } else {
                            echo 'There are no team members to display';
                        }
                        ?>
                    </ul>
            </tr>
        </tbody>
    </table>
    <a class="button button-primary" id="set_order">Save Order</a>
    <p class="pc_team_member_update_status">
        <span class="pc_team_member_updating"><img src="<?php echo PC_TEAM_URL . 'inc/img/spinner.gif' ?>" class=""/> Saving</span>
        <span class="pc_team_member_saved"><img src="<?php echo PC_TEAM_URL . 'inc/img/check.png' ?>" class=""/> Saved!</span>
    </p>
</div>
</div>