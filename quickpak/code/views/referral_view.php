<DIV class="CONTENT_ALL_TOP"><img src="/assets/image/big_content_top.jpg" width="942" height="9" /></DIV>
			
<DIV class="CONTENT_ALL_MIDDLE">

<DIV class="small_red"><?php echo validation_errors(); ?></DIV>

<form action="/referral/process" method="post">
	<table id="bag_quantity">
		<tr>
			<td>Email1:</td>
			<td>Name1:</td>
		</tr>
		<tr>
			<td><input name="email1" type="text" value="<?= htmlentities(set_value('email1'), ENT_QUOTES) ?>" /></td>
			<td><input name="name1" type="text" value="<?= htmlentities(set_value('name1'), ENT_QUOTES) ?>" /></td>
		</tr>
		<tr>
			<td>Email2:</td>
			<td>Name2:</td>
		</tr>
		<tr>
			<td><input name="email2" type="text" value="<?= htmlentities(set_value('email2'), ENT_QUOTES) ?>" /></td>
			<td><input name="name2" type="text" value="<?= htmlentities(set_value('name2'), ENT_QUOTES) ?>" /></td>
		</tr>
		<tr>
			<td>Email3:</td>
			<td>Name3:</td>
		</tr>
		<tr>
			<td><input name="email3" type="text" value="<?= htmlentities(set_value('email3'), ENT_QUOTES) ?>" /></td>
			<td><input name="name3" type="text" value="<?= htmlentities(set_value('name3'), ENT_QUOTES) ?>" /></td>
		</tr>
		<tr>
			<td>Email4:</td>
			<td>Name4:</td>
		</tr>
		<tr>
			<td><input name="email4" type="text" value="<?= htmlentities(set_value('email4'), ENT_QUOTES) ?>" /></td>
			<td><input name="name4" type="text" value="<?= htmlentities(set_value('name4'), ENT_QUOTES) ?>" /></td>
		</tr>
		<tr>
			<td>Email5:</td>
			<td>Name5:</td>
		</tr>
		<tr>
			<td><input name="email5" type="text" value="<?= htmlentities(set_value('email5'), ENT_QUOTES) ?>" /></td>
			<td><input name="name5" type="text" value="<?= htmlentities(set_value('name5'), ENT_QUOTES) ?>" /></td>
		</tr>
		<tr>
			<td>Your Email:</td>
			<td>Your Name:</td>
		</tr>
		<tr>
			<td><input name="referrer" type="text" value="<?= htmlentities(set_value('referrer'), ENT_QUOTES) ?>" /></td>
			<td><input name="referrer_name" type="text" value="<?= htmlentities(set_value('referrer_name'), ENT_QUOTES) ?>" /></td>
		</tr>
	</table>
	<input name="email_check" type="hidden" />
	<input type="submit" value="continue" />
</form></DIV>

<DIV class="CONTENT_ALL_BOTTOM"><img src="/assets/image/big_content_bottom.jpg" width="942" height="9" /></DIV>