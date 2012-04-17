$(document).ready(function() {
	
  $('#dtas_users_id').multiSelect({
    selectableHeader : '<input type="text" id="dtas-users-search" autocomplete="off" placeholder="filter" />',
    selectedHeader : '<h5>Selection</h5>'
  });

  $('input#dtas-users-search').quicksearch('#ms-dtas_users_id .ms-selectable li');
	
	$('input[name=radioName]:checked', '#myForm').val()
	

});