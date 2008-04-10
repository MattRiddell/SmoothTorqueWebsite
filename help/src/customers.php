<p align="left">Here is where you specify additional users of the predictive dialer. The initial screen displays all the existing users of the system.</p>

<p align="left">To add a new user to the system. Click on the Add Customer button. The next page you will be able to define the attributes of the new customer. Those attributes are summerised in the table below. All fields are mandatory.</p>

<table>
	<tr>
		<th bgcolor="#000044"><font color="#ffffff"><b>Field</b></font></th>
		<th bgcolor="#000044"><font color="#ffffff"><b>Description</b></font></th>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Customer Name</td>
		<td  bgcolor="#eeeeee">The name of the customer</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Customer Details</td>
		<td  bgcolor="#eeeeee">A one line summary of the customer</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Maximum Calls per Second</td>
		<td  bgcolor="#eeeeee">Limits the rate of calls that the dialer can make</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Maximum Channels</td>
		<td  bgcolor="#eeeeee">Limits the number of channels that the dialer can use</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Username</td>
		<td  bgcolor="#eeeeee">The login name that the customer uses</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Password</td>
		<td  bgcolor="#eeeeee">The password that the customer logs in with</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Address Lines 1 and 2<br />City<br />State<br />Zip<br />Country</td>
		<td  bgcolor="#eeeeee">The physical address of the customer</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Phone and Fax</td>
		<td  bgcolor="#eeeeee">The phone and fax numbers of the customer</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Email</td>
		<td  bgcolor="#eeeeee">The Email address of the customer</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Website</td>
		<td  bgcolor="#eeeeee">The URL of the customer's business</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Customer Type</td>
		<td  bgcolor="#eeeeee">Selects between a normal customer or an administrator<br />
		<br />
		A normal customer is only able to start/stop/add their own campaigns/numbers/messages
		whereas an administrator has full control of the system and is able to
		add additional customers and administrators.  There is also an account manager which is able
		to only see the customers page and update their data.  Account managers cannot run campaigns</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Queue Name</td>
		<td  bgcolor="#eeeeee">This is the queue that wqill be used when a campaign uses Queue Mode </td>

	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Trunk</td>
		<td  bgcolor="#eeeeee">The trunk that the customer will use</td>
	</tr>
</table>
<br />From the customers page, you also have access to additional information:<br />
<br />
<table>
	<tr>
		<th bgcolor="#000044"><font color="#ffffff"><b>Icon</b></font></th>
		<th bgcolor="#000044"><font color="#ffffff"><b>Description</b></font></th>
	</tr>
   	<tr>
		<td  bgcolor="#eeeeee"><img src="/images/pencil.png" align="left"></td>
		<td  bgcolor="#eeeeee">Edit the information for this customer</td>
	</tr>
   	<tr>
		<td  bgcolor="#eeeeee"><img src="/images/lock_edit.png" align="left"></td>
		<td  bgcolor="#eeeeee">Change the customer's password</td>
	</tr>

   	<tr>
		<td  bgcolor="#eeeeee"><img src="/images/table.png" align="left"></td>
		<td  bgcolor="#eeeeee">View the Call Detail Records (CDR) for this customer</td>
	</tr>
<?if ( $config_values['USE_BILLING'] == "YES") {?>
   	<tr>
		<td  bgcolor="#eeeeee"><img src="/images/cart_edit.png" align="left"></td>
		<td  bgcolor="#eeeeee">View or change the billing information for this customer</td>
	</tr>
<?}?>
   	<tr>
		<td  bgcolor="#eeeeee"><img src="/images/delete.png" align="left"></td>
		<td  bgcolor="#eeeeee">Delete this customer</td>
	</tr>


</table>
