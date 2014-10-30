<script src="<?php echo CMS_ROOT; ?>js/tinymce/jquery.tinymce.min.js"></script>
<script src="<?php echo CMS_ROOT; ?>js/tinymce/tinymce.min.js"></script>
<script src="<?php echo CMS_ROOT; ?>js/ajaxupload/fileuploader.js"></script>
<script>
	$(function() {
		$('textarea.editor').tinymce({
			skin : 'meow',
			plugins: ["paste link image code contextmenu table"],
			content_css: '', <!-- <?php echo HTTP_DOMAIN; ?>css/main.css -->
			relative_urls : false,
			document_base_url : "/",
			menubar: false,
			contextmenu: "link image inserttable | cell row column deletetable",
			toolbar: "bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image | styleselect | code",
			style_formats: [
				{title : 'Paragraph', block : 'p'},
				{title : 'Header', block : 'h3'},
				{title : 'Subheader', block : 'h4'},
				{title : 'Image on Left', selector : 'img', classes : 'image-on-left'},
				{title : 'Image on Right', selector : 'img', classes : 'image-on-right'}
			]
		});
	});

	function get_synopsis() {
		var content = $('#content').html();
		var patt1=/<p[^>]*>(.*?)<\/p>/m;
		var first_p = content.match(patt1)[0];
		// Replace escaped <'s with real ones...
		first_p = first_p.replace(/&(lt|gt);/g, function (strMatch, p1){
 		 	return (p1 == "lt")? "<" : ">";
 		});
		// Strip all tags
 		first_p = first_p.replace(/<\/?[^>]+(>|$)/g, "");
		first_p = first_p.replace(/&nbsp;/gi, " ");
		$('#synopsis').val(first_p);
	}
</script>
<!--<script language="javascript" type="text/javascript" src="<?php echo CMS_ROOT; ?>js/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">
	$().ready(function() {
		$('textarea.editor').tinymce({
			// Location of TinyMCE script
			script_url : '<?php echo CMS_ROOT; ?>js/tiny_mce/tiny_mce.js',
			// General options
			theme : "advanced",
			plugins : "paste, inlinepopups, jbimages, jbfiles",

			// Theme options
			theme_advanced_buttons1 :"bold,italic,bullist,numlist,|,link,unlink,jbfiles,|,image,jbimages,|,styleselect,formatselect,|,code",
			theme_advanced_buttons2 :"",
			theme_advanced_buttons3 :"",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : false,
			theme_advanced_resizing_use_cookie : false,

			// Example content CSS
			content_css : "<?php echo HTTP_DOMAIN; ?>css/main.css",
			relative_urls : false,
			document_base_url : "/",
			dialog_type : "modal",
			paste_auto_cleanup_on_paste : true,
            paste_remove_styles: true,
			paste_remove_spans: true,
            paste_remove_styles_if_webkit: true,
            paste_strip_class_attributes: "all",
			theme_advanced_blockformats : "p,h2,h3",
			oninit : resize_mce,
			style_formats : [
				{title : 'Image on Left', selector : 'img', classes : 'image-on-left'},
				{title : 'Image on Right', selector : 'img', classes : 'image-on-right'}
			]
		});
	});

	var resize_mce = function() {
		$('.mceLayout').css('width', '100%');
	}

	$(window).resize(resize_mce).resize();


</script>
-->