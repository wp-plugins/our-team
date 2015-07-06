/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function ($) {


    var member_height = $('#pc_our_team.grid_circles .pc_team_member').width();
    $('#pc_our_team.grid_circles .pc_team_member').each(function () {
        $(this).css({
            height: member_height
        });
    });
    var member_height = $('#pc_our_team.grid_circles2 .pc_team_member').width();
    $('#pc_our_team.grid_circles2 .pc_team_member').each(function () {
        $(this).css({
            height: member_height
        });
    });
    $(document).on('click', '.pc_close_button', function (event) {
        if ($('#pc_our_team_lightbox').hasClass('show')) {
            $('.pc_our_team_lightbox').slideUp(300, function () {
                $('#pc_our_team_lightbox').fadeOut(300);
            });
            $('#pc_our_team_lightbox').removeClass('show');
        }
    });

    /**
     * Popup Js
     */
    $('.pc_team_single_popup').click(function (e) {
        var item = null;
        if ($(this).hasClass('pc_team_member')) {
            item = $(this);
        } else {
            item = $(this).parents('.pc_team_member');
        }
        build_popup(item);
        e.stopPropagation();
        e.preventDefault();
    });
    function build_popup(item) {
        $('.pc_our_team_lightbox .name').html($('.pc_team_member_name a', item).html());
        $('.pc_our_team_lightbox .skills').html($('.pc_team_skills', item).html());
        $('.pc_our_team_lightbox .pc-content').html($('.pc_team_content', item).html());
        $('.pc_our_team_lightbox .social').html($('.icons', item).html());
        $('.pc_our_team_lightbox .title').html($('.pc_team_member_jobtitle', item).html());
        $('.pc_our_team_lightbox .image').attr('src', $('.wp-post-image', item).attr('src'));
        $('.pc_our_team_lightbox img').css('display', 'block');
        $('#pc_our_team_lightbox').fadeIn(350, function () {
            $('.pc_our_team_lightbox').slideDown(350, function () {
                $('#pc_our_team_lightbox').addClass('show');
            });
        });
    }
    /**
     * Panel JS
     */
    $('.pc_our_team_panel .pc-right-panel .pc_close_button').click(function () {
        if ($('#pc_our_team_panel').hasClass('show')) {
            $('.pc_our_team_panel').removeClass('slidein');
            $('#pc_our_team_panel').delay(450).fadeOut(300);
            $('#pc_our_team_panel').removeClass('show');
        }
    });
    $('.pc_team_single_panel').click(function (e) {
        var item = null;
        if ($(this).hasClass('pc_team_member')) {
            item = $(this);
        } else {
            item = $(this).parents('.pc_team_member');
        }
        build_panel(item);
        e.stopPropagation();
        e.preventDefault();

    });
    function build_panel(item) {
        $('.pc_our_team_panel .pc-name').html($('.pc_team_member_name a', item).html());
        $('.pc_our_team_panel .pc-skills').html($('.pc_team_skills', item).html());
        $('.pc_our_team_panel .pc_personal_quote').html($('.pc_personal_quote', item).html());
        $('.pc_our_team_panel .pc-content').html($('.pc_team_content', item).html());
        $('.pc_our_team_panel .pc-social').html($('.icons', item).html());
        $('.pc_our_team_panel .pc-title').html($('.pc_team_member_jobtitle', item).html());
        $('.pc_our_team_panel .pc-image').attr('src', $('.wp-post-image', item).attr('src'));
        $('#pc_our_team_panel').fadeIn(350, function () {
            $('.pc_our_team_panel').addClass('slidein');
            $('#pc_our_team_panel').addClass('show');
        });
    }


    $('#pc_our_team .pc_team_member').hover(function () {
        $('.pc_team_member_overlay', this).stop(true, false).fadeIn(440);
        $('.wp-post-image', this).addClass('zoomIn');
        $('.pc_team_more', this).addClass('show');
    }, function () {
        $('.pc_team_member_overlay', this).stop(true, false).fadeOut(440);
        $('.wp-post-image', this).removeClass('zoomIn');
        $('.pc_team_more', this).removeClass('show');
    });

    /* SKILLS */
    $('.pc_skill_bar').each(function () {
        $(this).find('.pc_skill_bar_bar').css('width', jQuery(this).attr('data-percent'));
    });


    /**
     * Pager Js
     */
    $('.team-item').click(function () {
        $('.team-content').hide();
        var team_id = $(this).attr('data-team');
        $('.team-item').removeClass('current');
        $('.team-content').removeClass('current');
        $(this).addClass('current');
        $("#team" + team_id).addClass('current');
        $("#team" + team_id).show();
    });
    // go to team item for mobile
    $(function () {
        $('.touch .team-item').click(function () {
            var target = $('.team-item-holder');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        });
    });
    /**
     * Round Js
     */
    window.addEventListener('load', function () {
        setTimeout(function () {
            $("#content_our_team").roundabout({
                btnNext: ".next_team",
                btnPrev: ".prev_team",
                responsive: true
            });
        }, false);
    });
    /**
     * Masonry Js
     */
    $("body").find(".dvteamgrid").show();
    $('#pc_our_team_masonry').imagesLoaded(function () {
        if ($(window).width() > 1024) {
            var gridwidth = '50%';
        } else {
            var gridwidth = '100%';
        }
        var options = {
            itemWidth: 250,
            autoResize: false,
            align: 'left',
            direction: 'left',
            container: jQuery('#pc_our_team_masonry'),
            offset: 0,
            outerOffset: 0,
            fillEmptySpace: true,
            flexibleWidth: gridwidth
        };
        var handler = $('#pc_our_team_masonry li');
        $(window).resize(function () {
            var windowWidth = $(window).width(),
                    newOptions = {
                        flexibleWidth: '50%'
                    };
            if (windowWidth < 1024) {
                newOptions.flexibleWidth = '100%';
            }
            handler.wookmark(newOptions);
        });
        handler.wookmark(options);
    });
    /* REMOVE HOVER EFFECT ON TOUCH DEVICES */
    $(document).ready(function () {
        if ("ontouchstart" in document.documentElement) {
            $('.dv-member-name').addClass('rmveffect');
            $('.dv-member-info').addClass('rmveffect');
            $('.dv-member-desc').addClass('rmveffect');
            $('img').addClass('rmveffect');
            $('.dv-member-zoom').addClass('rmveffect');
        } else {
            /* CUSTOM ZOOM ANIMATION */
            $(".dvteamgrid figure").hover(
                    function () {
                        "use strict";
                        $(this).find('.dv-member-zoom').removeClass('dv-zoomout');
                        $(this).find('.dv-member-zoom').addClass('dv-zoomin');
                        $(this).find('.wp-post-image').removeClass('dv-zoomout');
                        $(this).find('.wp-post-image').addClass('dv-zoomin');
                    }, function () {
                "use strict";
                $(this).find('.dv-member-zoom').removeClass('dv-zoomin');
                $(this).find('.dv-member-zoom').addClass('dv-zoomout');
                $(this).find('.wp-post-image').removeClass('dv-zoomin');
                $(this).find('.wp-post-image').addClass('dv-zoomout');
            }
            );
        }
    });
});