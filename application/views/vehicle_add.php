<!DOCTYPE html>
<html lang="en">
<head>

</head>
<body>

<div id="container">
    <?php echo validation_errors(); ?>
	<form method="post" action="<?php echo site_url('vehicle_add/add');?>">
        <label>Name</label><input name="name" type="text"/>
        <label>SID</label><input name="SID" type="text"/>
        <label>Type</label><input name="vehicle_type_ID" type="text"/>
       <input VALUE="Submit" type="submit"/>
	</form>
</div>

</body>
</html>