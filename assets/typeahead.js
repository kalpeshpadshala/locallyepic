$(document).ready(function(){

var salesPeople = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  
  remote: '/gogo/ajax_salesperson?q=%QUERY'
  });

	/*
  prefetch: '/admin/ajax_merchants.cfm',
  var orderSet = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: 'ajax_orders.cfm?ts='
  });*/
 
  salesPeople.initialize();
  /*orderSet.initialize();*/
 
$(' .typeaheadsp').typeahead(null, {
  name: 'sales-people',
  displayKey: 'value',
  source: salesPeople.ttAdapter(),
  minLength: 3
});


		
});