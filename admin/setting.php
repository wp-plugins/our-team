<style>
    #gopro{
        width: 100%;
        display: block;
        clear: both;
        padding: 10px;
        margin: 10px 8px 15px 5px;
        border: 1px solid #e1e1e1;
        background: #464646;
        color: #ffffff;
        overflow: hidden;
    }
    #wrapper{
        border: 1px solid #f0f0f0;
        width: 95%;

    }
    #wrapper{
        border: 1px solid #f0f0f0;
        width: 95%;

    }
    table.widefat{
        margin-bottom: 15px;
    }
    table.widefat tr{
        transition: 0.3s all ease-in-out;
        -moz-transition: 0.3s all ease-in-out;
        -webkit-transition: 0.3s all ease-in-out;
    }
    table.widefat tr:hover{
        /*background: #E6E6E6;*/
    }

    #wrapper input[type='text']{
        width: 80%;
        transition: 0.3s all ease-in-out;
        -moz-transition: 0.3s all ease-in-out;
        -webkit-transition: 0.3s all ease-in-out;
    }
    #wrapper input[type='text']:focus{
        border: 1px solid #1784c9;
        box-shadow: 0 0 7px #1784c9;
        -moz-box-shadow: 0 0 5px #1784c9;
        -webkit-box-shadow: 0 0 5px #1784c9;
    }
    #wrapper input[type='text'].small-text{
        width: 20%;
    }
    .proversion{
        color: red;
        font-style: italic;
    }
    .choose-progress{
        display: none;
    }
    .pc_popup_mode{
        display: inline-block;
        width: 15px;
        height: 15px;
        border-radius: 100%;
        position: relative;
        top: 2px;
        box-shadow: 0 0 3px #333;
        -moz-box-shadow: 0 0 3px #333;
        -webkit-box-shadow: 0 0 3px #333;
    }

    .pc_popup_mode_off{
        background: #F54412;
    }
    .pc_popup_mode_live{
        background: #84E11F;
    }
    .pc_popup_mode_test{
        background: #FF9717;
    }
    .left{ float: left;}
    .right {float: right;}
    .center{text-align: center;}
    .width70{ width: 70%;}
    .width25{ width: 25% !important;}
    .width50{ width: 50%;}
    .larger{ font-size: larger;}
    .bold{ font-weight: bold;}
    .editcursor{ cursor: text}
    .red{ color: #CC0000; font-size: 12px;}
</style>

<div id="wrapper">
    <div id="gopro">
        <div class="left">
            <h1><b>Our Team Settings</b></h1>
            
        </div>
        
    </div>
    <div class="width25 right">

        <table class="widefat">
            <thead>
                <tr>
                    <th><b>Read Me</b> </th>
                </tr>
                <tr>
                    <td>
                        <ul>
                            <li>- To display the team members, add <b>[our-team]</b> shortcode in a widget, post or page</li>
                            <li>- To display members from a specific group, add <b>[our-team group="name of your group"]</b></li>
                            <li>- To override the template choice from the shortcode, add <b>[our-team template="grid"]</b> . 
                                Template Options: <em>grid, grid_circles, grid_circles2, grid_image, hc, carousel, stacked, brick,  </em></li>
                            <li>- Click on Re-order to arrange the order of the team members</li>
                            <li>- Click on Groups to create groups (example: department, team names)</li>
                        </ul>
                    </td>
                </tr>
            </thead>
        </table>
    </div>