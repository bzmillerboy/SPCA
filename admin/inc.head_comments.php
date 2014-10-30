<script type="text/javascript">
	$(function() {
		var comment_div = $('#comment_email_area');
<?php
	if ($o->comments == 0) {
?>
		comment_div.hide();
<?php
	}
?>
		$('#comments').change(function(){
			if ($(this).is(':checked')) {
				comment_div.slideDown(100);
			} else {
				comment_div.slideUp(100);	
			}
		});
	});
</script>