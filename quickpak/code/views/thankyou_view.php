<DIV class="CONTENT_ALL_TOP"><img src="/assets/image/big_content_top.jpg" width="942" height="9" /></DIV><DIV style="clear:both;"></DIV>
			
<DIV class="CONTENT_ALL_MIDDLE"><table width="830px" align="center" class="pad">
	<tr>
		<td align="center" colspan="4">
			<h2>BuzyBee, Inc. | 15507 S. Normandie Ave, Suite 243 | Gardena, CA 90247</h2><br /><a href="mailto:teamone@buzybeeinc.com">teamone@buzybeeinc.com</a>
			
		</td>
	</tr>
	<tr height="20px">
		<td colspan="2"><br /><h2>Order Date:</h2><br /><?= date("m/d/Y") ?></td>
		<td colspan="2" align="left"><br /><h2>Order Number:</h2><br /><?= $order['transaction'] ?></td>
	</tr>
	<tr>
		<td valign="top"><br /><h2>Bill To:</h2></td>
		<td><br />
			<?= htmlentities($order['billFirst'], ENT_QUOTES)." ".htmlentities($order['billLast'], ENT_QUOTES) ?><br />
			<?= htmlentities($order['billAddress1'], ENT_QUOTES) ?><br />
			<?= htmlentities($order['billAddress2'], ENT_QUOTES) ?><br />
			<?= htmlentities($order['billCity'], ENT_QUOTES).", ".htmlentities($order['billState'], ENT_QUOTES)." ".htmlentities($order['billZip'], ENT_QUOTES) ?><br />
			<?= htmlentities($order['billCountry'], ENT_QUOTES) ?>
		</td>
		<td valign="top"><br /><h2>Ship To:</h2></td>
		<td><br />
			<?= htmlentities($order['shipFirst'], ENT_QUOTES)." ".htmlentities($order['shipLast'], ENT_QUOTES) ?><br />
			<?= htmlentities($order['shipAddress1'], ENT_QUOTES) ?><br />
			<?= htmlentities($order['shipAddress2'], ENT_QUOTES) ?><br />
			<?= htmlentities($order['shipCity'], ENT_QUOTES).", ".htmlentities($order['shipState'], ENT_QUOTES)." ".htmlentities($order['shipZip'], ENT_QUOTES) ?><br />
			<?= htmlentities($order['shipCountry'], ENT_QUOTES) ?>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<table width="100%">
				<tr>
					<th align="left"><br /><h2>Product Code</h2><br /></th>
				  <th align="left"><br /><h2>Description</h2><br /></th>
				  <th align="left"><br /><h2>Price (each)</h2><br /></th>
				  <th align="left"><br /><h2>Quantity</h2><br /></th>
					<th align="left"><br /><h2>Extended</h2><br /></th>
				</tr>
				<?php
				$amount = 0;
				foreach($order['contents'] as $item) { ?>
					<tr>

						<td align="left"><?= htmlentities($item['code'], ENT_QUOTES) ?></td>
						<td align="left"><?= htmlentities($item['name'], ENT_QUOTES) ?></td>
						<td align="left"><?= ($item['code'] == 'ref_code') ? '('.number_format(htmlentities($item['cost'], ENT_QUOTES), 2).')' : number_format(htmlentities($item['cost'], ENT_QUOTES), 2) ?></td>
						<td align="left"><?= htmlentities($item['quantity'], ENT_QUOTES) ?></td>
						<td align="left"><?= ($item['code'] == 'ref_code') ? '('.number_format(htmlentities($item['sub'], ENT_QUOTES), 2).')' : number_format(htmlentities($item['sub'], ENT_QUOTES), 2) ?></td>

					</tr>
				<?php
				} ?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<table width="100%">
				<tr>
					<td><br />
						<p><h2>Shipping Option:</h2><br />
						  <h2>Cardholder's Name:</h2><br />
						  <h2>Credit Card (Last Four):</h2><br />
						  <h2>Expiration:</h2><br />
					    <h2>E-Mail:</h2></p>
					</td>
					<td><br />
						<p><?= ($order['shipping'] == 3.5) ? 'Domestic Shipping' : 'Priority Shipping' ?><br />
						<?= htmlentities($order['billFirst'], ENT_QUOTES)." ".htmlentities($order['billLast'], ENT_QUOTES) ?><br />
						<?= htmlentities($order['ccNumber'], ENT_QUOTES) ?><br />
						<?= htmlentities($order['ccExpiration'], ENT_QUOTES) ?><br />
						<?= htmlentities($order['userEmail'], ENT_QUOTES) ?></p>
					</td>
					<td><br />
						<p><br />
						  <h2>Sub-Total:</h2><br />
						  <h2>Sales Tax:</h2><br />
						  <h2>Shipping/Handling:</h2><br />
					    <h2>Total:</h2>
					</td>
					<td align="right"><br />
						<p><br /><?= number_format($subtotal, 2) ?><br />
						<?= number_format($tax, 2) ?><br />
						<?= number_format($shipping, 2) ?><br />
						<?= number_format($total, 2) ?></p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="4"><br />
			Your credit card will not be charged until your order ships.<br />
			Please allow 2-6 weeks for delivery.<br />
			All orders plus applicable tax, 30-day money back guarantee (minus S&H)<br />
			"DO NOT REPLY to this email, it is sent from an unmonitored email box".<br />
			<strong>Thank you for your order!<strong>
		</td>
	</tr>
</table></DIV><DIV style="clear:both;"></DIV>

<DIV class="CONTENT_ALL_BOTTOM"><img src="/assets/image/big_content_bottom.jpg" width="942" height="9" /></DIV><DIV style="clear:both;"></DIV>