//Tokenfield Initialization and Demo Data
$('#tokenfield').tokenfield({})

$('#tokenfield-autocomplete').tokenfield({
    autocomplete: {
        source: ['red', 'blue', 'green', 'yellow', 'violet', 'brown', 'purple', 'black', 'white'],
        delay: 100
    },
    showAutocompleteOnFocus: true
})

$("#tokenfield-typeahead").tokenfield({
    typeahead: {
        name: "tags",
        local: ["red", "blue", "green", "yellow", "violet", "brown", "purple", "black", "white"]
    }
});

//Max Length Plugin Initialization and Custom Functions
$(document).ready(function() {
    $('input#basicMax').maxlength({
        alwaysShow: true,
        warningClass: "label green",
        limitReachedClass: "label orange"
    });

    $('textarea#textareaMax').maxlength({
        alwaysShow: true,
        warningClass: "label green",
        limitReachedClass: "label orange"
    });

    $('input#thresholdMax').maxlength({
        threshold: 20,
        warningClass: "label green",
        limitReachedClass: "label orange"
    });

});

//Time Picker Initialization and Custom Functions
$(document).ready(function() {
    $('#timepicker1').timepicker('setTime', new Date());
    

    setTimeout(function() {
        $('#timeDisplay').text($('#timepicker1').val());
    }, 0);

    $('#timepicker1').on('changeTime.timepicker', function(e) {
        $('#timeDisplay').text(e.time.value);
    });
    
    $('#timepicker2').timepicker('setTime', new Date());
    

    setTimeout(function() {
        $('#timeDisplay').text($('#timepicker2').val());
    }, 0);

    $('#timepicker2').on('changeTime.timepicker', function(e) {
        $('#timeDisplay').text(e.time.value);
    });
    
    
    $('#timepicker1_edit').timepicker();
    

    setTimeout(function() {
        $('#timeDisplay').text($('#timepicker1_edit').val());
    }, 0);

    $('#timepicker1_edit').on('changeTime.timepicker', function(e) {
        $('#timeDisplay').text(e.time.value);
    });
    
    $('#timepicker2_edit').timepicker();
    

    setTimeout(function() {
        $('#timeDisplay').text($('#timepicker2_edit').val());
    }, 0);

    $('#timepicker2_edit').on('changeTime.timepicker', function(e) {
        $('#timeDisplay').text(e.time.value);
    });
    
});

$('#sandbox-container input').datepicker({
    autoclose: true,
    todayHighlight: true,
    startDate:new Date(),
    format: "mm/dd/yy",
}).on('changeDate',function(event){

    var deal_start = $('#deal_start').val();

    $('#datepicker_start_0').val(deal_start);
    $('#datepicker_start_1').val(deal_start);
    $('#datepicker_start_2').val(deal_start);
    $('#datepicker_start_3').val(deal_start);
    $('#datepicker_start_4').val(deal_start);
    $('#datepicker_start_5').val(deal_start);
    $('#datepicker_start_6').val(deal_start);
  });


$('#sandbox-codntainer1 input').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "yyyy-mm-dd",
    startDate:new Date(),

});

// $('#sandbox-container input').change(function () {
//     console.log($('#deal_start').val());
// });

