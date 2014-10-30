<div id="header">
	<div id="user_info">Hello, <?php echo $login->get_first_name(); ?>! <a href="<?php echo CMS_ROOT; ?>logout.php" style="font-size:10px;">Logout?</a></div>
	<a href="<?php echo CMS_ROOT; ?>" class="logo"></a>
</div>
<ul id="menu">
	<li><a href="<?php echo CMS_ROOT; ?>"<?php if (DATABASE == 'dashboard') echo(' class="on"'); ?>>Dashboard</a></li>
    <li><a href="<?php echo CMS_ROOT; ?>news"<?php if (DATABASE == 'news') echo(' class="on"'); ?>>News</a></li>
    <li><a href="<?php echo CMS_ROOT; ?>events"<?php if (DATABASE == 'events') echo(' class="on"'); ?>>Events</a></li>   
</ul>
<div id="admin_body">