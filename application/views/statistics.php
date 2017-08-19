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
                                        <form action="<?php echo base_url(); ?>site/statistics/" method="GET" >
                                        <table>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr>
                                                <td>Search By :&nbsp;</td>
                                                <td>
                                                    <select id="chartyear" name="chartyear" class="form-control">
                                                        <?php
                                                            $cy=date('Y');
                                                            for($i=$cy;$i>=2014;$i--){
                                                        ?>
                                                                <option <?php if($gyear==$i){echo "selected";} ?>><?php echo $i; ?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>&nbsp;&nbsp;Per Page :&nbsp;</td>
                                                <td>
                                                    <select id="chartcat" name="chartcat" class="form-control">
                                                       
                                                                <option <?php if($gmonth=="Monthly"){echo "selected";} ?>>Monthly</option>
                                                                <option <?php if($gmonth=="Weekly"){echo "selected";} ?>>Weekly</option>
                                                                <option <?php if($gmonth=="Daily"){echo "selected";} ?>>Daily</option>
                                                      
                                                    </select>
                                                </td>
                                                <td>&nbsp;&nbsp;Per Page :&nbsp;</td>
                                                <td>
                                                    <select id="charttype" name="charttype" class="form-control">
                                                        <option value="line" <?php if($gtype=="line"){echo "selected";} ?>>Line</option>
                                                        <option value="col" <?php if($gtype=="col"){echo "selected";} ?>>Column Chart</option>
                                                    </select>
                                                </td>
                                                <td>&nbsp;&nbsp;<button type="submit" name="go" class="btn btn-primary">GO</button></td>
                                            </tr>
                                        </table>
                                        </form>
                                    </div>
                        </header>
                        <div class="contents">
                            <div id="container" style="margin: 0 auto"></div>
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