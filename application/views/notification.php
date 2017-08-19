<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script>
    $(function () {
    $('#container').highcharts(<?php echo $data_str; ?>);
});
</script>
<div class="right-sec">
    <!-- Right Section Header Start -->
    <?php $this->load->view('top'); ?>
    <!-- Right Section Header End -->
    <!-- Content Section Start -->
    <div class="content-section">
        <div class="container-liquid">
            <div class="row">
                <div class="col-xs-12">
                    <div class="sec-box">
                        <a class="closethis">Close</a>
                        <header>
                            <h2 class="heading">Statistics</h2>
                             <div>
                                        <form action="/site/statistics/" method="POST">
                                        <table>
                                            <tbody><tr><td>&nbsp;</td></tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;Start Time :&nbsp;</td>
                                                <td>
                                                    <input type="text" name="deal_start" id="deal_start" value="">
                                                </td>
                                                <td>&nbsp;&nbsp;End Time :&nbsp;</td>
                                                <td>
                                                    <input type="text" name="deal_end" id="deal_end" value="">
                                                </td>
                                                <td>&nbsp;&nbsp;<button type="submit" class="btn btn-primary">GO</button></td>
                                            </tr>
                                        </tbody></table>
                                        </form>
                                    </div>
                        </header>
                        <div class="contents">
                            <div id="container" style="margin: 0 auto">
                                                                <div class="col-xs-12">
                                    <div class="sec-box">
                                        <a class="closethis">Close</a>
                                        <header>
                                            <h2 class="heading">Notification List</h2>
                                        </header>
                                        <div class="contents">
                                            <a class="togglethis">Toggle</a>
                                            <section>
                                                <table class="table table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th>Deal Name</th>
                                                            <th>User Name</th>
                                                            <th>Shop Name</th>
                                                            <th>Category Name</th>
                                                            <th>Text</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                                                                                        <tr>
                                                                    <td>Bakers Dozen</td>
                                                                    <td>Michele</td>
                                                                    <td>Doughnut Man</td>
                                                                    <td>Doughnut Shops</td>
                                                                    <td>Bakers Dozen</td>
                                                                    <td>2015-01-29</td>
                                                                </tr>
                                                                                                                        <tr>
                                                                    <td>test</td>
                                                                    <td>Michele</td>
                                                                    <td>Doughnut Man</td>
                                                                    <td>Doughnut Shops</td>
                                                                    <td>test</td>
                                                                    <td>2015-02-03</td>
                                                                </tr>
                                                        


                                                    </tbody>
                                                </table>
                                                <ul class="pagination pagination-sm">
                                                    <?php if(isset($data)){?>
                                                    <li><a href="<?php echo base_url(); ?>site/select_shop/?scat=<?php echo $data['shop_cats'];?>&scountry=<?php echo $data['country_id'];?>&sstate=<?php echo $data['state_id'];?>&scity=<?php echo $data['city_id'];?>&add_shop=&page_no=<?php echo $prev; ?>">«</a></li>
                                                    <?php
                                                        for($i=$prev;$i<=$next;$i++){  
                                                    ?>
                                                    <li <?php if($curr_page==$i){echo 'class="active"';} ?>><a href="<?php echo base_url(); ?>site/select_shop/?scat=<?php echo $data['shop_cats'];?>&scountry=<?php echo $data['country_id'];?>&sstate=<?php echo $data['state_id'];?>&scity=<?php echo $data['city_id'];?>&add_shop=&page_no=<?php echo $i; ?>" <?php if($curr_page==$i){echo 'class="active"';} ?>><?php echo $i; ?></a></li>
                                                    <?php 
                                                        }
                                                    ?>
                                                    <li><a href="<?php echo base_url(); ?>site/select_shop/?scat=<?php echo $data['shop_cats'];?>&scountry=<?php echo $data['country_id'];?>&sstate=<?php echo $data['state_id'];?>&scity=<?php echo $data['city_id'];?>&add_shop=&page_no=<?php echo $next; ?>">»</a></li>
                                                    <?php }?>
                                                </ul>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row End -->
        </div>
    </div>
    <!-- Content Section End -->
</div>