<script>
    function selectState(country_id) {
        if (country_id != "") {
            loadData('state', country_id);
            $("#city_dropdown").html("<option value='-1'>Select city</option>");
        } else {
            $("#state_dropdown").html("<option value='-1'>Select state</option>");
            $("#city_dropdown").html("<option value='-1'>Select city</option>");
        }
    }
    function selectCity(state_id) {
        if (state_id != "-1") {
            loadData('city', state_id);
        } else {
            $("#city_dropdown").html("<option value='-1'>Select city</option>");
        }
    }
    function loadData(loadType, loadId) {
        var dataString = 'loadType=' + loadType + '&loadId=' + loadId;
        $("#" + loadType + "_loader").show();
        $("#" + loadType + "_loader").fadeIn(400).html('Please wait... <img src="<?php echo base_url(); ?>assets/images/loading.gif" />');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>site/loadData",
            data: dataString,
            cache: false,
            success: function (result) {
                $("#" + loadType + "_loader").hide();
                $("#" + loadType + "_dropdown").html("<option value='-1'>Select " + loadType + "</option>");
                $("#" + loadType + "_dropdown").append(result);
                $("#" + loadType + "_dropdown").append("<option value='0'>Others</option>");
            }
        });
    }
    function filter_by_users(user_id, role) {

        var url = '<?php echo base_url(); ?>site/index/?filter_by_user=1&id=' + user_id + '&role=' + role;
        window.location.assign(url);

    }
</script>
<link href="<?php echo base_url(); ?>assets/css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">

<!-- begin MAIN PAGE CONTENT -->
<div id="page-wrapper">

    <div class="page-content">

        <!-- begin PAGE TITLE AREA -->
        <!-- Use this section for each page's title and breadcrumb layout. In this example a date range picker is included within the breadcrumb. -->
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h1>Dashboard
                        <small>Content Overview</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
                        <li class="pull-right">
                            <!--                                    <div id="reportrange" class="btn btn-green btn-square date-picker">
                                                                    <i class="fa fa-calendar"></i>
                                                                    <span class="date-range"></span> <i class="fa fa-caret-down"></i>
                                                                </div>-->
                        </li>
                    </ol>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <!-- end PAGE TITLE AREA -->

        <!-- begin DASHBOARD CIRCLE TILES -->
        <div class="row">
            <?php if ($user_info['role'] == "1") {
                ?>
                <div class="col-lg-12">
                    <table class="table table-hover">

                        <tr>
                            <td style="width: 15%;">
                                <select class="form-control" name="national_sales_manager"
                                        onchange="filter_by_users(this.value, 2);">
                                    <option value="0">National Sales Manager</option>
                                    <?php
                                    foreach ($users as $user) {
                                        if ($user['role'] != 2) {
                                            continue;
                                        }
                                        ?>
                                        <option value="<?php echo $user['user_id']; ?>" <?php if ($user['user_id'] == $filter_by_user) {
                                            echo "selected";
                                        } ?>><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td style="width: 15%;">
                                <select class="form-control" name="state_sales_manager"
                                        onchange="filter_by_users(this.value, 3);">
                                    <option value="0">State Sales Manager</option>
                                    <?php
                                    foreach ($users as $user) {
                                        if ($user['role'] != 3) {
                                            continue;
                                        }
                                        ?>
                                        <option value="<?php echo $user['user_id']; ?>" <?php if ($user['user_id'] == $filter_by_user) {
                                            echo "selected";
                                        } ?>><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td style="width: 15%;">
                                <select class="form-control" name="area_sales_manager"
                                        onchange="filter_by_users(this.value, 4);">
                                    <option value="0">Area Sales Manager</option>
                                    <?php
                                    foreach ($users as $user) {
                                        if ($user['role'] != 4) {
                                            continue;
                                        }
                                        ?>
                                        <option value="<?php echo $user['user_id']; ?>" <?php if ($user['user_id'] == $filter_by_user) {
                                            echo "selected";
                                        } ?>><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td style="width: 15%;">
                                <select class="form-control" name="sales_person"
                                        onchange="filter_by_users(this.value, 5);">
                                    <option value="0">Sales person</option>
                                    <?php
                                    foreach ($users as $user) {
                                        if ($user['role'] != 5) {
                                            continue;
                                        }
                                        ?>
                                        <option value="<?php echo $user['user_id']; ?>" <?php if ($user['user_id'] == $filter_by_user) {
                                            echo "selected";
                                        } ?>><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>

                        </tr>

                    </table>
                    <form action="<?php echo base_url(); ?>site/index" method="GET" enctype="multipart/form-data">
                        <table class="table table-hover">

                            <tr>


                                <td style="width: 15%;">
                                    <select class="form-control" name="scountry"
                                            onchange="selectState(this.options[this.selectedIndex].value)">
                                        <option value="">Select country</option>
                                        <?php
                                        foreach ($country as $v) {
                                            ?>
                                            <option value="<?php echo $v['id']; ?>" <?php if (isset($_GET['scountry'])) {
                                                if ($v['id'] == $_GET['scountry']) {
                                                    echo 'selected';
                                                }
                                            } ?>><?php echo $v['name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td style="width: 15%;">
                                    <select class="form-control" name="sstate" id="state_dropdown"
                                            onchange="selectCity(this.options[this.selectedIndex].value)">
                                        <option value="">Select state</option>
                                        <?php if (!empty($state)) {
                                            foreach ($state as $states) {
                                                ?>

                                                <option value="<?php echo $states['sid']; ?>" <?php if (isset($_GET['sstate'])) {
                                                    if ($states['sid'] == $_GET['sstate']) {
                                                        echo 'selected';
                                                    }
                                                } ?>><?php echo $states['state_name']; ?></option>
                                            <?php }
                                        }
                                        ?>
                                    </select>
                                    <span id="state_loader"></span>
                                </td>
                                <td style="width: 15%;">
                                    <select class="form-control" name="scity" id="city_dropdown">
                                        <option value="">Select city</option>
                                        <?php if (!empty($city)) {
                                            foreach ($city as $citys) {
                                                ?>
                                                <option value="<?php echo $citys['city_id']; ?>" <?php if (isset($_GET['scity'])) {
                                                    if ($citys['city_id'] == $_GET['scity']) {
                                                        echo 'selected';
                                                    }
                                                } ?>><?php echo $citys['city_name']; ?></option>
                                            <?php }
                                        }
                                        ?>
                                    </select>
                                    <span id="city_loader"></span>
                                </td>

                                <td style="width: 15%;">
                                    <input type="text" name="zip_code" placeholder="Zip Code" class="form-control">
                                </td>

                                <td>&nbsp;&nbsp;
                                    <button type="submit" name="add_shop" value="add_shop" class="btn btn-default">GO
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" style="border-top: 1px solid #ddd;">&nbsp;</td>
                            </tr>
                        </table>
                    </form>
                </div>

            <?php }
            if ($user_info['role'] == "1") { ?>
                <div class="col-lg-2 col-sm-6">
                    <div class="circle-tile">
                        <a href="#">
                            <div class="circle-tile-heading dark-blue">
                                <i class="fa fa-users fa-fw fa-3x"></i>
                            </div>
                        </a>
                        <div class="circle-tile-content dark-blue">
                            <div class="circle-tile-description text-faded">
                                Membership(right now)
                            </div>
                            <div class="circle-tile-number text-faded">
                                -
                                <span id="sparklineA"></span>
                            </div>
                            <!--                             <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <div class="circle-tile">
                        <a href="#">
                            <div class="circle-tile-heading green">
                                <i class="fa fa-money fa-fw fa-3x"></i>
                            </div>
                        </a>
                        <div class="circle-tile-content green">
                            <div class="circle-tile-description text-faded">
                                Revenue Year to Date
                            </div>
                            <div class="circle-tile-number text-faded">
                                -
                            </div>
                            <!--                             <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <div class="circle-tile">
                        <a href="#">
                            <div class="circle-tile-heading green">
                                <i class="fa fa-money fa-fw fa-3x"></i>
                            </div>
                        </a>
                        <div class="circle-tile-content green">
                            <div class="circle-tile-description text-faded">
                                Revenue Month to Date
                            </div>
                            <div class="circle-tile-number text-faded">
                                -
                            </div>
                            <!--                             <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                        </div>
                    </div>
                </div>
            <?php } ?>


             <?php 
            if ($user_info['role'] == "9") { 

            if (isset($corporate_business_list)) {
                
                if( count($corporate_business_list) > 1)
                {
            ?>
            <form action="<?php echo base_url(); ?>site/index" method="GET" enctype="multipart/form-data">
                        <table class="table table-hover">

                            <tr>

                                <td style="width: 15%;">
                                    <select class="form-control" name="sbid">
                                        <option value="0">All</option>
                                        <?php
                                        foreach ($corporate_business_list as $v) {
                                            ?>
                                            <option value="<?php echo $v['shop_id']; ?>" <?php if (isset($_GET['sbid'])) {
                                                if ($v['shop_id'] == $_GET['sbid']) {
                                                    echo 'selected';
                                                }
                                            } ?>><?php echo $v['shop_name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td>&nbsp;&nbsp;
                                    <button type="submit" name="search" value="search" class="btn btn-default">GO</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" style="border-top: 1px solid #ddd;">&nbsp;</td>
                            </tr>
                        </table>
                    </form>
    <?php } } } ?>



            <?php if (($user_info['role'] != "6") && ($user_info['role'] != "9")) { ?>
                <div class="col-lg-2 col-sm-6">
                    <div class="circle-tile">
                        <a href="#">
                            <div class="circle-tile-heading orange">
                                <i class="fa fa-bell fa-fw fa-3x"></i>
                            </div>
                        </a>
                        <div class="circle-tile-content orange">
                            <div class="circle-tile-description text-faded">
                                Past Due Accts Current Month
                            </div>
                            <div class="circle-tile-number text-faded">
                                -
                            </div>
                            <!--                         <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <div class="circle-tile">
                        <a href="#">
                            <div class="circle-tile-heading orange">
                                <i class="fa fa-bell fa-fw fa-3x"></i>
                            </div>
                        </a>
                        <div class="circle-tile-content orange">
                            <div class="circle-tile-description text-faded">
                                Past Due Accts Year To Date
                            </div>
                            <div class="circle-tile-number text-faded">
                                -
                            </div>
                            <!--                         <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                        </div>
                    </div>
                </div>
                <?php if ($user_info['role'] == 1) { ?>
                    <div class="col-lg-2 col-sm-6">

                        <div align="center" class="circle-tile-content red" style="padding-top: 20px;">
                            <div class="text-faded">
                                New Apps Download today
                            </div>
                            <div class="text-faded">
                                Android : <?php echo $newapp_today['android']; ?> &nbsp;&nbsp; ios
                                : <?php echo $newapp_today['ios']; ?>
                            </div>
                        </div>

                        <div align="center" class="circle-tile-content green" style="padding-top: 20px;">
                            <div class="text-faded">
                                New Apps Download MTD
                            </div>
                            <div class="text-faded">
                                Android : <?php echo $newapp_mtd['android']; ?> &nbsp;&nbsp;ios
                                : <?php echo $newapp_mtd['ios']; ?>
                            </div>
                        </div>

                        <div align="center" class="circle-tile-content purple" style="padding-top: 20px;">
                            <div class="text-faded">
                                New Apps Download YTD
                            </div>
                            <div class="text-faded">
                                Android : <?php echo $newapp_ytd['android']; ?> &nbsp;&nbsp;ios
                                : <?php echo $newapp_ytd['ios']; ?>
                            </div>
                        </div>

                    </div>
                <?php } ?>
            <?php } ?>

            <!-- end DASHBOARD CIRCLE TILES -->
            <div class="row">
                <div class="col-lg-12">
                    <?php if (!($user_info['role'] == "6") && ($user_info['role'] != "9")) { ?>
                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">

                                <div class="circle-tile-content blue">
                                    <div class="circle-tile-description text-faded">
                                        New Business Sign ups Today
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $bsignup_today; ?>
                                        <span id="sparklineB"></span>
                                    </div>
                                    <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">

                                <div class="circle-tile-content blue">
                                    <div class="circle-tile-description text-faded">
                                        New Business Sign ups MTD
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $bsignup_mtd; ?>
                                        <span id="sparklineB"></span>
                                    </div>
                                    <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">

                                <div class="circle-tile-content blue">
                                    <div class="circle-tile-description text-faded">
                                        New Business Sign ups YTD
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $bsignup_ytd; ?>
                                        <span id="sparklineB"></span>
                                    </div>
                                    <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!($user_info['role'] == "8")){ ?>

                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">

                            <div class="circle-tile-content red">
                                <div class="circle-tile-description text-faded">
                                    Offers Created Today
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo $deal_created_today; ?>
                                    <span id="sparklineC"></span>
                                </div>
                                <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">

                            <div class="circle-tile-content red">
                                <div class="circle-tile-description text-faded">
                                    Offers Created MTD
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo $deal_created_mtd; ?>
                                    <span id="sparklineC"></span>
                                </div>
                                <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">

                            <div class="circle-tile-content red">
                                <div class="circle-tile-description text-faded">
                                    Offers Created YTD
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo $deal_created_ytd; ?>
                                    <span id="sparklineC"></span>
                                </div>
                                <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                            </div>
                        </div>
                    </div>


                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">

                            <div class="circle-tile-content purple">
                                <div class="circle-tile-description text-faded">
                                    Push Notes Sent Today
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo $pnotes_today; ?>
                                    <span id="sparklineB"></span>
                                </div>
                                <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">

                            <div class="circle-tile-content purple">
                                <div class="circle-tile-description text-faded">
                                    Push Notes Sent MTD
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo $pnotes_mtd; ?>
                                    <span id="sparklineB"></span>
                                </div>
                                <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">

                            <div class="circle-tile-content purple">
                                <div class="circle-tile-description text-faded">
                                    Push Notes Sent YTD
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo $pnotes_ytd; ?>
                                    <span id="sparklineB"></span>
                                </div>
                                <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">

                            <div class="circle-tile-content yellow">
                                <div class="circle-tile-description text-faded">
                                    Offers Activated Today
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo $deal_activated_today; ?>
                                    <span id="sparklineC"></span>
                                </div>
                                <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">

                            <div class="circle-tile-content yellow">
                                <div class="circle-tile-description text-faded">
                                    Offers Activated MTD
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo $deal_activated_mtd; ?>
                                    <span id="sparklineC"></span>
                                </div>
                                <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">

                            <div class="circle-tile-content yellow">
                                <div class="circle-tile-description text-faded">
                                    Offers Activated YTD
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo $deal_activated_ytd; ?>
                                    <span id="sparklineC"></span>
                                </div>
                                <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                            </div>
                        </div>
                    </div>


                </div>


                <?php } ?>
            </div>


            <?php if ($user_info['role'] == "1") {
                ?>

                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">

                                <div class="circle-tile-content blue">
                                    <div class="circle-tile-description text-faded">
                                        Offers Used
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $deal_used; ?>
                                        <span id="sparklineB"></span>
                                    </div>
                                    <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">

                                <div class="circle-tile-content blue">
                                    <div class="circle-tile-description text-faded">
                                        Offers Shared
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $deal_shared; ?>
                                        <span id="sparklineB"></span>
                                    </div>
                                    <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                                </div>
                            </div>
                        </div>

                <?php
            }
            ?>



           

                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">

                                <div class="circle-tile-content gray ">
                                    <div class="circle-tile-description text-faded">
                                        Messages sent today
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $messages_sent_today; ?>
                                        <span id="sparklineB"></span>
                                    </div>
                                    <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">

                                <div class="circle-tile-content gray">
                                    <div class="circle-tile-description text-faded">
                                        Messages sent MTD
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $messages_sent_mtd; ?>
                                        <span id="sparklineB"></span>
                                    </div>
                                    <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">

                                <div class="circle-tile-content gray">
                                    <div class="circle-tile-description text-faded">
                                        Messages sent YTD
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $messages_sent_ytd; ?>
                                        <span id="sparklineB"></span>
                                    </div>
                                    <!--                                 <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> -->
                                </div>
                            </div>
                        </div>


        </div>


        <div class="row">

            <div class="col-lg-3">
                <div class="tile tile-img tile-time" style="height: 200px">
                    <p class="time-widget">
                        <span class="time-widget-heading">It Is Currently</span>
                        <br>
                        <strong>
                            <span id="datetime"></span>
                        </strong>
                    </p>
                </div>
                <?php // if(!($user_info['role'] == "6")){ ?>
                <?php // if(isset($task_to_do) && !empty($task_to_do)){?>
                <!--                <div class="tile dark-blue checklist-tile" style="height: 370px">
                                    <h4 onclick="check_all()"><i class="fa fa-check-square-o" id="to_do"></i> To-Do List</h4>
                                    <div class="checklist">-->
                <?php // foreach ($task_to_do as $to_do) { ?>
                <!--                        <label class="" onclick="redirect_task(<?php // echo $to_do['bussiness_id'];?>);">
                            <input type="checkbox" onclick="redirect_task(<?php // echo $to_do['bussiness_id'];?>);"> <i class="fa fa-wrench fa-fw text-faded"></i> <?php // echo $to_do['task'];?>
                            <span class="task-time text-faded pull-right"><?php // echo date('h:i a',strtotime($to_do['end_date']));?></span>
                        </label>-->
                <?php // } ?>
                <!--                        <label class="selected">
                                            <input type="checkbox" checked> <i class="fa fa-wrench fa-fw text-faded"></i> Server #2 Hardward Upgrade
                                            <span class="task-time text-faded pull-right">9:39 AM</span>
                                        </label>
                                        <label class="selected">
                                            <input type="checkbox" checked> <i class="fa fa-warning fa-fw text-orange"></i> Call Ticket #2032
                                            <span class="task-time text-faded pull-right">9:53 AM</span>
                                        </label>
                                        <label>
                                            <input type="checkbox"> <i class="fa fa-warning fa-fw text-orange"></i> Emergency Maintenance
                                            <span class="task-time text-faded pull-right">10:14 AM</span>
                                        </label>
                                        <label>
                                            <input type="checkbox"> <i class="fa fa-file fa-fw text-faded"></i> Purchase Order #439
                                            <span class="task-time text-faded pull-right">10:20 AM</span>
                                        </label>
                                        <label>
                                            <input type="checkbox"> <i class="fa fa-pencil fa-fw text-faded"></i> March Content Update
                                            <span class="task-time text-faded pull-right">10:48 AM</span>
                                        </label>
                                        <label>
                                            <input type="checkbox"> <i class="fa fa-magic fa-fw text-faded"></i> Client #42 Data Scrubbing
                                            <span class="task-time text-faded pull-right">11:09 AM</span>
                                        </label>
                                        <label>
                                            <input type="checkbox"> <i class="fa fa-wrench fa-fw text-faded"></i> PHP Upgrade Server #6
                                            <span class="task-time text-faded pull-right">11:17 AM</span>
                                        </label>-->
                <!--                    </div>
                                </div>-->

                <?php // }?>
                <?php // }?>
                <!--</div>-->
                <!--            <script>
                function check_all(){
                    if($("#to_do").hasClass("fa-check-square-o")){
                        $("#to_do").removeClass("fa-check-square-o");
                        $("#to_do").addClass("fa-square-o");
                    }
                    else{
                        $("#to_do").removeClass("fa-square-o");
                        $("#to_do").addClass("fa-check-square-o");
                    }
                }
                function redirect_task(id){
                    window.location.href ="<?php // echo base_url();?>site/view_shop/?id=" + id;
                }
            </script>-->
                <!--            <div class="col-lg-9">
                                <div class="row">
                                    <div class="portlet portlet-default">
                                        <div class="portlet-heading">
                                            <div class="portlet-title">
                                                <h4>Calendar</h4>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="table-responsive">
                                                <div id="calendar" class="fc fc-ltr"><table class="fc-header" style="width:100%"><tbody><tr><td class="fc-header-left"><span class="fc-button fc-button-prev fc-state-default fc-corner-left" unselectable="on"><span class="fc-text-arrow">‹</span></span><span class="fc-button fc-button-next fc-state-default fc-corner-right" unselectable="on"><span class="fc-text-arrow">›</span></span><span class="fc-header-space"></span><span class="fc-button fc-button-today fc-state-default fc-corner-left fc-corner-right fc-state-disabled" unselectable="on">today</span></td><td class="fc-header-center"><span class="fc-header-title"><h2>March 2015</h2></span></td><td class="fc-header-right"><span class="fc-button fc-button-month fc-state-default fc-corner-left fc-state-active" unselectable="on">month</span><span class="fc-button fc-button-agendaWeek fc-state-default" unselectable="on">week</span><span class="fc-button fc-button-agendaDay fc-state-default fc-corner-right" unselectable="on">day</span></td></tr></tbody></table><div class="fc-content" style="position: relative;"><div class="fc-view fc-view-month fc-grid" style="position:relative" unselectable="on"><div class="fc-event-container" style="position:absolute;z-index:8;top:0;left:0"><div class="fc-event fc-event-hori fc-event-draggable fc-event-start fc-event-end fc-green" style="position: absolute; left: 3px; width: 143px; top: 44px;"><div class="fc-event-inner"><span class="fc-event-title">All Day Event</span></div><div class="ui-resizable-handle ui-resizable-e">&nbsp;&nbsp;&nbsp;</div></div><div class="fc-event fc-event-hori fc-event-draggable fc-event-start fc-event-end fc-orange ui-draggable" style="position: absolute; left: 299px; width: 587px; top: 44px;" unselectable="on"><div class="fc-event-inner"><span class="fc-event-title">Long Event</span></div><div class="ui-resizable-handle ui-resizable-e">&nbsp;&nbsp;&nbsp;</div></div><div class="fc-event fc-event-hori fc-event-draggable fc-event-start fc-event-end fc-blue" style="position: absolute; left: 595px; width: 143px; top: 64px;"><div class="fc-event-inner"><span class="fc-event-time">4p</span><span class="fc-event-title">Repeating Event</span></div><div class="ui-resizable-handle ui-resizable-e">&nbsp;&nbsp;&nbsp;</div></div><div class="fc-event fc-event-hori fc-event-draggable fc-event-start fc-event-end fc-red" style="position: absolute; left: 595px; width: 143px; top: 169px;"><div class="fc-event-inner"><span class="fc-event-time">4p</span><span class="fc-event-title">Repeating Event</span></div><div class="ui-resizable-handle ui-resizable-e">&nbsp;&nbsp;&nbsp;</div></div><div class="fc-event fc-event-hori fc-event-draggable fc-event-start fc-event-end fc-purple ui-draggable" style="position: absolute; left: 3px; width: 143px; top: 169px;" unselectable="on"><div class="fc-event-inner"><span class="fc-event-time">10:30a</span><span class="fc-event-title">Meeting</span></div><div class="ui-resizable-handle ui-resizable-e">&nbsp;&nbsp;&nbsp;</div></div><div class="fc-event fc-event-hori fc-event-draggable fc-event-start fc-event-end fc-default" style="position: absolute; left: 3px; width: 143px; top: 189px;"><div class="fc-event-inner"><span class="fc-event-time">12p</span><span class="fc-event-title">Lunch</span></div><div class="ui-resizable-handle ui-resizable-e">&nbsp;&nbsp;&nbsp;</div></div><div class="fc-event fc-event-hori fc-event-draggable fc-event-start fc-event-end fc-white" style="position: absolute; left: 151px; width: 143px; top: 169px;"><div class="fc-event-inner"><span class="fc-event-time">7p</span><span class="fc-event-title">Birthday Party</span></div><div class="ui-resizable-handle ui-resizable-e">&nbsp;&nbsp;&nbsp;</div></div></div><table class="fc-border-separate" style="width:100%" cellspacing="0"><thead><tr class="fc-first fc-last"><th class="fc-day-header fc-sun fc-widget-header fc-first" style="width: 148px;">Sun</th><th class="fc-day-header fc-mon fc-widget-header" style="width: 148px;">Mon</th><th class="fc-day-header fc-tue fc-widget-header" style="width: 148px;">Tue</th><th class="fc-day-header fc-wed fc-widget-header" style="width: 148px;">Wed</th><th class="fc-day-header fc-thu fc-widget-header" style="width: 148px;">Thu</th><th class="fc-day-header fc-fri fc-widget-header" style="width: 148px;">Fri</th><th class="fc-day-header fc-sat fc-widget-header fc-last" style="width: 148px;">Sat</th></tr></thead><tbody><tr class="fc-week fc-first"><td class="fc-day fc-sun fc-widget-content fc-past fc-first" data-date="2015-03-01"><div style="min-height: 124px;"><div class="fc-day-number">1</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td><td class="fc-day fc-mon fc-widget-content fc-past" data-date="2015-03-02"><div><div class="fc-day-number">2</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td><td class="fc-day fc-tue fc-widget-content fc-past" data-date="2015-03-03"><div><div class="fc-day-number">3</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td><td class="fc-day fc-wed fc-widget-content fc-past" data-date="2015-03-04"><div><div class="fc-day-number">4</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td><td class="fc-day fc-thu fc-widget-content fc-past" data-date="2015-03-05"><div><div class="fc-day-number">5</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td><td class="fc-day fc-fri fc-widget-content fc-past" data-date="2015-03-06"><div><div class="fc-day-number">6</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td><td class="fc-day fc-sat fc-widget-content fc-past fc-last" data-date="2015-03-07"><div><div class="fc-day-number">7</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td></tr><tr class="fc-week"><td class="fc-day fc-sun fc-widget-content fc-today fc-state-highlight fc-first" data-date="2015-03-08"><div style="min-height: 123px;"><div class="fc-day-number">8</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td><td class="fc-day fc-mon fc-widget-content fc-future" data-date="2015-03-09"><div><div class="fc-day-number">9</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td><td class="fc-day fc-tue fc-widget-content fc-future" data-date="2015-03-10"><div><div class="fc-day-number">10</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td><td class="fc-day fc-wed fc-widget-content fc-future" data-date="2015-03-11"><div><div class="fc-day-number">11</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td><td class="fc-day fc-thu fc-widget-content fc-future" data-date="2015-03-12"><div><div class="fc-day-number">12</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td><td class="fc-day fc-fri fc-widget-content fc-future" data-date="2015-03-13"><div><div class="fc-day-number">13</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td><td class="fc-day fc-sat fc-widget-content fc-future fc-last" data-date="2015-03-14"><div><div class="fc-day-number">14</div><div class="fc-day-content"><div style="position: relative; height: 40px;">&nbsp;</div></div></div></td></tr><tr class="fc-week"><td class="fc-day fc-sun fc-widget-content fc-future fc-first" data-date="2015-03-15"><div style="min-height: 123px;"><div class="fc-day-number">15</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-mon fc-widget-content fc-future" data-date="2015-03-16"><div><div class="fc-day-number">16</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-tue fc-widget-content fc-future" data-date="2015-03-17"><div><div class="fc-day-number">17</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-wed fc-widget-content fc-future" data-date="2015-03-18"><div><div class="fc-day-number">18</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-thu fc-widget-content fc-future" data-date="2015-03-19"><div><div class="fc-day-number">19</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-fri fc-widget-content fc-future" data-date="2015-03-20"><div><div class="fc-day-number">20</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-sat fc-widget-content fc-future fc-last" data-date="2015-03-21"><div><div class="fc-day-number">21</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td></tr><tr class="fc-week"><td class="fc-day fc-sun fc-widget-content fc-future fc-first" data-date="2015-03-22"><div style="min-height: 123px;"><div class="fc-day-number">22</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-mon fc-widget-content fc-future" data-date="2015-03-23"><div><div class="fc-day-number">23</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-tue fc-widget-content fc-future" data-date="2015-03-24"><div><div class="fc-day-number">24</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-wed fc-widget-content fc-future" data-date="2015-03-25"><div><div class="fc-day-number">25</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-thu fc-widget-content fc-future" data-date="2015-03-26"><div><div class="fc-day-number">26</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-fri fc-widget-content fc-future" data-date="2015-03-27"><div><div class="fc-day-number">27</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-sat fc-widget-content fc-future fc-last" data-date="2015-03-28"><div><div class="fc-day-number">28</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td></tr><tr class="fc-week"><td class="fc-day fc-sun fc-widget-content fc-future fc-first" data-date="2015-03-29"><div style="min-height: 123px;"><div class="fc-day-number">29</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-mon fc-widget-content fc-future" data-date="2015-03-30"><div><div class="fc-day-number">30</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-tue fc-widget-content fc-future" data-date="2015-03-31"><div><div class="fc-day-number">31</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-wed fc-widget-content fc-other-month fc-future" data-date="2015-04-01"><div><div class="fc-day-number">1</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-thu fc-widget-content fc-other-month fc-future" data-date="2015-04-02"><div><div class="fc-day-number">2</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-fri fc-widget-content fc-other-month fc-future" data-date="2015-04-03"><div><div class="fc-day-number">3</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-sat fc-widget-content fc-other-month fc-future fc-last" data-date="2015-04-04"><div><div class="fc-day-number">4</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td></tr><tr class="fc-week fc-last"><td class="fc-day fc-sun fc-widget-content fc-other-month fc-future fc-first" data-date="2015-04-05"><div style="min-height: 124px;"><div class="fc-day-number">5</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-mon fc-widget-content fc-other-month fc-future" data-date="2015-04-06"><div><div class="fc-day-number">6</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-tue fc-widget-content fc-other-month fc-future" data-date="2015-04-07"><div><div class="fc-day-number">7</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-wed fc-widget-content fc-other-month fc-future" data-date="2015-04-08"><div><div class="fc-day-number">8</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-thu fc-widget-content fc-other-month fc-future" data-date="2015-04-09"><div><div class="fc-day-number">9</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-fri fc-widget-content fc-other-month fc-future" data-date="2015-04-10"><div><div class="fc-day-number">10</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-sat fc-widget-content fc-other-month fc-future fc-last" data-date="2015-04-11"><div><div class="fc-day-number">11</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td></tr></tbody></table></div></div></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>-->

            </div>
            <!-- /.row -->


        </div>
    </div>
</div>

</div>
<!-- /.page-content -->

</div>
<!-- /#page-wrapper -->
<!-- end MAIN PAGE CONTENT -->

</div>
<!-- /#wrapper -->

<!-- GLOBAL SCRIPTS -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/popupoverlay/defaults.js"></script>
<?php require('logout.php'); ?>
<script src="<?php echo base_url(); ?>assets/js/plugins/popupoverlay/logout.js"></script>
<!-- HISRC Retina Images -->
<script src="<?php echo base_url(); ?>assets/js/plugins/hisrc/hisrc.js"></script>

<!-- PAGE LEVEL PLUGIN SCRIPTS -->
<!-- HubSpot Messenger -->
<script src="<?php echo base_url(); ?>assets/js/plugins/messenger/messenger.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/messenger/messenger-theme-flat.js"></script>
<!-- Date Range Picker -->
<script src="<?php echo base_url(); ?>assets/js/plugins/daterangepicker/moment.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Morris Charts -->
<script src="<?php echo base_url(); ?>assets/js/plugins/morris/raphael-2.1.0.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/morris/morris.js"></script>
<!-- Flot Charts -->
<script src="<?php echo base_url(); ?>assets/js/plugins/flot/jquery.flot.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/flot/jquery.flot.resize.js"></script>
<!-- Sparkline Charts -->
<script src="<?php echo base_url(); ?>assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- Moment.js -->
<script src="<?php echo base_url(); ?>assets/js/plugins/moment/moment.min.js"></script>
<!-- jQuery Vector Map -->
<script src="<?php echo base_url(); ?>assets/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/jvectormap/maps/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?php echo base_url(); ?>assets/js/demo/map-demo-data.js"></script>
<!-- Easy Pie Chart -->
<script src="<?php echo base_url(); ?>assets/js/plugins/easypiechart/jquery.easypiechart.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/js/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/datatables/datatables-bs3.js"></script>

<!-- THEME SCRIPTS -->
<script src="<?php echo base_url(); ?>assets/js/flex.js"></script>
<script>

    //Date Range Picker
    $(document).ready(function () {
        $('#reportrange').daterangepicker({
                startDate: moment().subtract('days', 29),
                endDate: moment(),
                minDate: '01/01/2012',
                maxDate: '12/31/2014',
                dateLimit: {
                    days: 60
                },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                opens: 'left',
                buttonClasses: ['btn btn-default'],
                applyClass: 'btn-small btn-primary',
                cancelClass: 'btn-small',
                format: 'MM/DD/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Submit',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            },
            function (start, end) {
                console.log("Callback has been called!");
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        );
        //Set the initial state of the picker label
        $('#reportrange span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
    });

    //Morris Area Chart
    //var sales_data = [{
    //    date: '2014-1-25',
    //    productA: 80.26,
    //    productB: 92.26,
    //    productC: 79.91,
    //    productD: 81.63
    //}, {
    //    date: '2014-1-26',
    //    productA: 251.34,
    //    productB: 115.62,
    //    productC: 128.34,
    //    productD: 92.35
    //}, {
    //    date: '2014-1-27',
    //    productA: 90.91,
    //    productB: 89.26,
    //    productC: 124.48,
    //    productD: 152.61
    //}, {
    //    date: '2014-1-28',
    //    productA: 91.23,
    //    productB: 87.94,
    //    productC: 250.79,
    //    productD: 352.24
    //}, {
    //    date: '2014-1-29',
    //    productA: 148.26,
    //    productB: 151.98,
    //    productC: 164.33,
    //    productD: 142.43
    //}, {
    //    date: '2014-1-30',
    //    productA: 74.53,
    //    productB: 71.26,
    //    productC: 78.91,
    //    productD: 76.32
    //}, {
    //    date: '2014-1-31',
    //    productA: 84.26,
    //    productB: 62.87,
    //    productC: 156.72,
    //    productD: 163.06
    //}, ];
    //Morris.Area({
    //    element: 'morris-chart-dashboard',
    //    data: sales_data,
    //    xkey: 'date',
    //    xLabelFormat: function(date) {
    //        return (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();
    //    },
    //    xLabels: 'day',
    //    ykeys: ['productA', 'productB', 'productC', 'productD'],
    //    yLabelFormat: function(y) {
    //        return "$" + y;
    //    },
    //    labels: ['Product A', 'Product B', 'Product C', 'Product D'],
    //    lineColors: ['#fff', '#fff', '#fff', '#fff'],
    //    hideHover: 'auto',
    //    resize: true,
    //    gridTextFamily: ['Open Sans'],
    //    gridTextColor: ['rgba(255,255,255,0.7)'],
    //    fillOpacity: 0.1,
    //    pointSize: 0,
    //    smooth: true,
    //    lineWidth: 2,
    //    grid: true,
    //    dateFormat: function(date) {
    //        d = new Date(date);
    //        return (d.getMonth() + 1) + '/' + d.getDate() + '/' + d.getFullYear();
    //    },
    //});

    //Responsive Sparkline Inline Charts
    //$("#sparklineA").sparkline([200, 215, 221, 214, 232, 265], {
    //    type: 'bar',
    //    zeroAxis: false,
    //    height: 24,
    //    chartRangeMin: 100,
    //    barColor: 'rgba(255,255,255,0.5)',
    //    negBarColor: 'rgba(255,255,255,0.5)'
    //});
    //
    //$("#sparklineB").sparkline([10, 24, 18], {
    //    type: 'pie',
    //    height: 24,
    //    sliceColors: ['rgba(255,255,255,0.2)', 'rgba(255,255,255,0.4)', 'rgba(255,255,255,0.6)']
    //});
    //
    //$("#sparklineC").sparkline([22, 29, 14, 12, 18, 21, 24], {
    //    type: 'bar',
    //    zeroAxis: false,
    //    height: 24,
    //    chartRangeMin: 0,
    //    barColor: 'rgba(255,255,255,0.5)',
    //    negBarColor: 'rgba(255,255,255,0.5)'
    //});
    //
    //$("#sparklineD").sparkline([72, 65, 45, 65, 82, 78, 92, 83, 46, 87, 69, 96], {
    //    type: 'line',
    //    lineColor: 'rgba(255,255,255,0.8)',
    //    fillColor: 'rgba(255,255,255,0.3)',
    //    spotColor: '#ffffff',
    //    minSpotColor: '#ffffff',
    //    maxSpotColor: '#ffffff',
    //    highlightLineColor: '#ffffff',
    //    height: 24,
    //    chartRangeMin: 25,
    //    drawNormalOnTop: false
    //});
    //
    ////Flot Chart Dynamic Chart
    //
    //var container = $("#flot-chart-moving-line");
    //
    //// Determine how many data points to keep based on the placeholder's initial size;
    //// this gives us a nice high-res plot while avoiding more than one point per pixel.
    //
    //var maximum = container.outerWidth() / 10 || 300;
    //
    ////
    //
    //var data = [];
    //
    //function getRandomData() {
    //
    //    if (data.length) {
    //        data = data.slice(1);
    //    }
    //
    //    while (data.length < maximum) {
    //        var previous = data.length ? data[data.length - 1] : 50;
    //        var y = previous + Math.random() * 10 - 5;
    //        data.push(y < 0 ? 0 : y > 100 ? 100 : y);
    //    }
    //
    //    // zip the generated y values with the x values
    //
    //    var res = [];
    //    for (var i = 0; i < data.length; ++i) {
    //        res.push([i, data[i]])
    //    }
    //
    //    return res;
    //}
    //
    ////
    //
    //series = [{
    //    data: getRandomData(),
    //    lines: {
    //        fill: true,
    //        fillColor: "rgba(255,255,255,0.4)",
    //    },
    //}];
    //
    ////
    //
    //var plot = $.plot(container, series, {
    //    grid: {
    //        borderWidth: 0,
    //        minBorderMargin: 10,
    //        labelMargin: 10,
    //        backgroundColor: {
    //            colors: ["rgba(255, 255, 255,0)", "rgba(255, 255, 255,0)"]
    //        },
    //        margin: {
    //            top: 0,
    //            bottom: 0,
    //            left: 0,
    //            right: 0
    //        },
    //        markings: function(axes) {
    //            var markings = [];
    //            var xaxis = axes.xaxis;
    //            for (var x = Math.floor(xaxis.min); x < xaxis.max; x += xaxis.tickSize * 2) {
    //                markings.push({
    //                    xaxis: {
    //                        from: x,
    //                        to: x + xaxis.tickSize
    //                    },
    //                    color: "rgba(255, 255, 255, 0)"
    //                });
    //            }
    //            return markings;
    //        }
    //    },
    //    xaxis: {
    //        tickFormatter: function() {
    //            return "";
    //        }
    //    },
    //    yaxis: {
    //        min: 10,
    //        max: 110,
    //        show: false
    //    },
    //    legend: {
    //        show: false
    //    },
    //    colors: ["#fff"]
    //
    //});
    //
    //// Update the random dataset at 25FPS for a smoothly-animating chart
    //
    //setInterval(function updateRandom() {
    //    series[0].data = getRandomData();
    //    plot.setData(series);
    //    plot.draw();
    //}, 500);


    //Chat Widget SlimScroll Box
    $(function () {
        $('.chat-widget').slimScroll({
            start: 'bottom',
            height: '300px',
            alwaysVisible: true,
            disableFadeOut: true,
            touchScrollStep: 50
        });
    });

    //Moment.js Time Display
    var datetime = null,
        date = null;

    var update = function () {
        date = moment(new Date())
        datetime.html(date.format('dddd<br>MMMM Do, YYYY<br>h:mm:ss A'));
    };

    $(document).ready(function () {
        datetime = $('#datetime')
        update();
        setInterval(update, 1000);
    });

    //Custom jQuery - Changes background on time tile based on the time of day.
    $(document).ready(function () {
        datetoday = new Date(); // create new Date()
        timenow = datetoday.getTime(); // grabbing the time it is now
        datetoday.setTime(timenow); // setting the time now to datetoday variable
        hournow = datetoday.getHours(); //the hour it is

        if (hournow >= 18) // if it is after 6pm
            $('div.tile-img').addClass('evening');
        else if (hournow >= 12) // if it is after 12pm
            $('div.tile-img').addClass('afternoon');
        else if (hournow >= 6) // if it is after 6am
            $('div.tile-img').addClass('morning');
        else if (hournow >= 0) // if it is after midnight
            $('div.tile-img').addClass('midnight');
    });

    //Vector Maps
    //$(function() {
    //    $('#map').vectorMap({
    //        map: 'world_mill_en',
    //        backgroundColor: 'transparent',
    //        regionStyle: {
    //            initial: {
    //                fill: '#bdc3c7'
    //            }
    //        },
    //        series: {
    //            regions: [{
    //                values: visitorData,
    //                scale: ['#bdc3c7', '#16a085'],
    //                normalizeFunction: 'polynomial'
    //            }]
    //        },
    //        onRegionLabelShow: function(e, el, code) {
    //            el.html(el.html() + ' (Total Visits - ' + visitorData[code] + ')');
    //        }
    //    });
    //});

    //To-Do List jQuery - Adds a strikethrough on checked items
    $('.checklist input:checkbox').change(function () {
        if ($(this).is(':checked'))
            $(this).parent().addClass('selected');
        else
            $(this).parent().removeClass('selected')
    });

    //Easy Pie Charts
    //$(function() {
    //    $('#easy-pie-1, #easy-pie-2, #easy-pie-3, #easy-pie-4').easyPieChart({
    //        barColor: "rgba(255,255,255,.5)",
    //        trackColor: "rgba(255,255,255,.5)",
    //        scaleColor: "rgba(255,255,255,.5)",
    //        lineWidth: 20,
    //        animate: 1500,
    //        size: 175,
    //        onStep: function(from, to, percent) {
    //            $(this.el).find('.percent').text(Math.round(percent));
    //        }
    //    });
    //
    //});

    //DataTables Initialization for Map Table Example
    //$(document).ready(function() {
    //    $('#map-table-example').dataTable();
    //});


</script>

</body>

</html>