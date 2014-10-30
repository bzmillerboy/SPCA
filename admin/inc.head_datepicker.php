<script type="text/javascript">
	$(function() {
		$(".date_toggled").datepicker({
			showOn: "button",
			buttonImage: "<?php echo CMS_ROOT; ?>images/icon-date.png",
			buttonImageOnly: true,
			showButtonPanel: true,
			dateFormat: 'm/d/yy'
		});
	});
</script>