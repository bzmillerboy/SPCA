<link type="text/css" href="<?php echo CMS_ROOT; ?>js/ajaxupload/fileuploader.css" rel="stylesheet" />
<script src="<?php echo CMS_ROOT; ?>js/ajaxupload/fileuploader.js" type="text/javascript"></script>
<script>        
	$(function() {      
		var uploader = new qq.FileUploader({
			element: document.getElementById('<?php echo $field; ?>_upload'),
			action: '<?php echo CMS_ROOT; ?>js/ajaxupload/upload.php',
			// additional data to send, name-value pairs
			params: {
<?php
	if ($path != '') echo "path: '" . $path ."'";
?>
			},
			// validation
			allowedExtensions: ['pdf'],
			multiple: false,
			// each file size limit in bytes - this option isn't supported in all browsers
			sizeLimit: 20000000, // max size
			debug: true,
			onComplete: function(id, fileName, responseJSON){
				var response = $.parseJSON(responseJSON);
				
				$('#<?php echo $field; ?>_upload_div').css('display', 'none');
				$('#<?php echo $field; ?>_preview').html('<img src="<?php echo CMS_ROOT; ?>images/icon-swf.png" height="24" width="24" border="0" align="absmiddle" /> ' + response.filename);
				$('#<?php echo $field; ?>_remove_div').css('display', 'block');
				$('#<?php echo $field; ?>').val('/upload/<?php echo $path; ?>/' + response.filename);
			}
		}); 
		
		$('#<?php echo $field; ?>_remove').click(function(e) {
			e.preventDefault();
			$('#<?php echo $field; ?>_upload_div').css('display', 'block');
			$('#<?php echo $field; ?>_preview').html('');
			$('#<?php echo $field; ?>_remove_div').css('display', 'none');
			$('#<?php echo $field; ?>').val('');
		});          
	});
</script>