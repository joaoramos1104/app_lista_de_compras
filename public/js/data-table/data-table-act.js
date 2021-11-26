(function ($) {
 "use strict";
	
	$(document).ready(function () {
		$('#data-table-basic').DataTable({
			"language": {
				"lengthMenu": "Exibir _MENU_ Por Página",
				"zeroRecords": "Nothing found - sorry",
				"info": " Página _PAGE_ de _PAGES_",
				"infoEmpty": "No records available",
				"infoFiltered": "(filtered from _MAX_ total records)",
				"sSearchPlaceholder": "Buscar..."
				
				
			}
			
			
		});
		var table = $('.data-table').DataTable();

		table
			.column('0:visible')
			.order('desc')
			.draw();
			
	});

 
})(jQuery); 
