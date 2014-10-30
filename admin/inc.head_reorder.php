<script>
	function init_sort() {
		$("#sortable").sortable({
			containment: 'parent',
			handle: '.handle',
			forcePlaceholderSize: true,
			placeholder: 'sort-highlight',
			revert: '100',
			tolerance: 'pointer',
			update: function(event, ui) {
				var sort_order = '';
				$('#sortable div').each(function(i) { 
					if ($(this).attr('alt') > 0) sort_order = sort_order +  $(this).attr('alt')  + '|';
				});
				
				//alert(sort_order);
				
				$.ajax({
					type: "GET",
					data: {
						sort_order: sort_order,
						reorder: '1',
						ajax: '1'
					}
				});
			}	
		});
	}
	
	$(document).ready(function() {
		init_sort();
	});
</script>