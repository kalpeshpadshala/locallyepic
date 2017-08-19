<div id="page-wrapper">
  <div class="page-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="page-title">
          <h1>
            Your Potential Customers
            <small>Where are Locally Epic users now?</small>
          </h1>
        </div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane active" id="map">
      <style>
      #map1 { width: 100%; height: 500px; border: 1px solid black; }
    	</style>
      <div id="map1"></div>
      <div class="error" id="errordiv" style="margin-top:25px">

      </div>
      <script src="/assets/js/map.js?v=3"></script>
      <script src="/assets/js/js.cookie.js"></script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDq-O_W4LW8qc-cpWxiBLWxo6Rdek87ufw&callback=initMap&libraries=places" async defer></script>
      <script src="/assets/js/jquery.geocomplete.js"></script>
      <script>
      $(function(){
        $("#searchcity").geocomplete().bind("geocode:result", function(event, result){
          //$.log("Result: " + result.formatted_address);
        }).bind("geocode:error", function(event, status){
          //$.log("ERROR: " + status);
        }).bind("geocode:multiple", function(event, results){
         //$.log("Multiple: " + results.length + " results found");
        });
        $("#find").click(function(){
          $("#geocomplete").trigger("geocode");
        });
        $("#examples a").click(function(){
          $("#geocomplete").val($(this).text()).trigger("geocode");
          return false;
        });
      });
      </script>
    </div>
  </div>
</div>

