<script type="text/javascript">
 function textCounter(field,field2,maxlimit)
{
 var countfield = document.getElementById(field2);
 if ( field.value.length > maxlimit ) {
  field.value = field.value.substring( 0, maxlimit );
  return false;
 } else {
    
  var aa= maxlimit - field.value.length;
   $("#"+field2).html(aa);
 }
}    
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
                            <h2 class="heading">Edit Schedule</h2>
                            <?php
                            if ($message) {
                                ?>
                                <br/>
                                <div class="alert alert-success">
                                    <?php echo $message; ?>
                                </div>
                                <?php
                            }
                            ?>

                        </header>
                        <div class="contents">
                            <a class="togglethis">Toggle</a>
                            <div class="table-box">

                                <form action="<?php echo base_url(); ?>site/edit_schedule" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="col-md-4">Description</th>
                                                <th class="col-md-8">Form Elements</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          
                                            <tr>
                                                <td class="col-md-4">Schedule text</td>
                                           
                                                 <td class="col-md-8">
                                                    
                                                    <textarea onkeyup="textCounter(this,'counter',107);" rows="3" class="form-control" name="schedule_text"><?php echo $info['schedule_text']; ?></textarea>
                                                    <span>character  remain <strong id="counter"><?php echo (107 - strlen($info['schedule_text'])); ?></strong></span>
                                                    <?php echo form_error('schedule_text'); ?>
                                                </td>
                                            </tr>
                                           
                                           
                                            <tr>
                                                <td class="col-md-4"></td>
                                                <td class="col-md-8"><button type="submit" name="edit_schedule" class="btn btn-primary">Submit</button></td>
                                            </tr>


                                        </tbody>
                                    </table>
                                </form>   
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