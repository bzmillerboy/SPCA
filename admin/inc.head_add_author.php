<script type="text/javascript">
	$(function() {
		var author_div = $('#add-author-area');
		author_div.hide();

		$('#add-author').click(function(e) {
			e.preventDefault();
			author_div.html('<table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 5px;"><tr><td style="padding-right:5px;"><label for="author_first_name" class="text_only">First Name</label><input type="text" id="author_first_name" class="form_element" /></td><td style="padding-left:5px;"><label for="author_last_name" class="text_only">Last Name</label><input type="text" id="author_last_name" class="form_element" /></td></tr></table><img src="<?php echo CMS_ROOT; ?>images/add.png" height="16" width="16" alt="Add Author" border="0" style="position:relative; top:3px;"> <a id="add-author-button" href="#" style="font-size: 13px;">Add Author</a>');
			author_div.css('text-align', 'left');
		
			$('#add-author-button').click(function(e) {
				e.preventDefault();
				$.ajax({
					type: "GET",
					data: {
						add_author: '1',
						first_name: $('#author_first_name').val(),
						last_name: $('#author_last_name').val(),
						ajax: '1'
					},
					beforeSend: function() {
						author_div.html('<img src="<?php echo CMS_ROOT; ?>images/ajax-loader.gif">');
						author_div.css('text-align', 'center');	
					}
				}).done(function(html) {
					$('#author_id').html(html);
					author_div.hide();
				});
			});
			author_div.slideToggle(100);
		});
	});
</script>