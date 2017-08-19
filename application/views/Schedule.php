<script type="text/javascript">
function delete_row(id){
            //alert($(this).attr("id"));
            var r = confirm("Are You sure you want to delete the row");
            if (r == true) {
                ajax("delete_schedule", id);
            } 
}

        function ajax(action, id) {


            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>site/"+action,
                data: {id: id},
                success: function(response){
                    if(action=="delete_schedule"){
       
                        if (response == "1") {
                            //alert("Record successfully delete");
                            location.reload();
//                            $("a[id='" + id + "']").closest("tr").effect("highlight", {
//                                color: '#4BADF5'
//                            }, 1000);
//                            $("a[id='" + id + "']").closest("tr").fadeOut();

                        }
                    }

                }

            });
        }
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
                            <h2 class="heading">Arrange Push Schedule</h2>
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

                                <form action="<?php echo base_url(); ?>site/Schedule" method="POST">  
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
                                                    
                                                    <textarea onkeyup="textCounter(this,'counter',107);" rows="3" class="form-control" id="schedule_text" name="schedule_text"><?php echo set_value('schedule_text'); ?></textarea>
                                                    <span>character  remain <strong id="counter">107</strong></span>
                                                    <?php echo form_error('schedule_text'); ?>
                                                </td>
                                            </tr>
                                           
                                           
                                            <tr>
                                                <td class="col-md-4"></td>
                                                <td class="col-md-8"><button type="submit" name="Schedule" class="btn btn-primary">Submit</button></td>
                                            </tr>


                                        </tbody>
                                    </table>
                                </form>   
                            </div>
                            <div class="clearfix"></div>
                        </div>
                         <div class="contents">
                                    <a class="togglethis">Toggle</a>
                                    <section>
                                        <table class="table table-condensed">
                                            <thead>
                                                <tr>
                                                    
                                                    <th>id</th>
                                                    <th>schedule text</th>
                                                    <th>date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                                <?php
                                                    foreach($info as $v){
                                                        
                                                      
                                                ?>
                                                
                                                        <tr>
                                                          
                                                            <td><?php echo $v['id']; ?></td>
                                                            <td><?php echo $v['schedule_text']; ?></td>
                                                            <td><?php echo $v['date']; ?></td>
                                                          
                                                            <td>
                                                                <a href="<?php echo base_url(); ?>site/edit_schedule/?id=<?php echo $v['id']; ?>"><img src="<?php echo base_url(); ?>assets/images/edit.png" /></a>&nbsp;&nbsp;&nbsp;
                                                                <a href="javascript:void(0);" id="del_<?php echo $v['id']; ?>" onclick="javascript:delete_row(this.id);"><img src="<?php echo base_url(); ?>assets/images/del.png" /></a>
                                                            </td>  
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                                
                                                
                                                
                                            </tbody>
                                        </table>
                   
                                        
                                        
                                    </section>
                                </div>
                    </div>
                </div>
            </div>
            <!-- Row End -->
        </div>
    </div>
    <!-- Content Section End -->
</div>