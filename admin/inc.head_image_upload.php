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
	if ($thumb != '') echo "thumb: '" . $thumb . "', ";
	if ($width != '') echo "width: '" . $width . "', ";
	if ($height != '') echo "height: '" . $height . "', ";
	if ($path != '') echo "path: '" . $path ."'";
?>
			},
			// validation
			allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
			multiple: false,
			// each file size limit in bytes - this option isn't supported in all browsers
			sizeLimit: 20000000, // max size
			debug: true,
			onComplete: function(id, fileName, responseJSON){
				var response = $.parseJSON(responseJSON);
				
				$('#<?php echo $field; ?>_upload_div').css('display', 'none');
				$('#<?php echo $field; ?>_preview').html('<img id="<?php echo $field; ?>_thumb" src="/upload/<?php echo $path; ?>/' + response.filename + '" width="<?php echo $return_width; ?>" border="0">');
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