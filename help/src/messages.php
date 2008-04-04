<p align="left">SmoothTorque is capable of two types of campaigns; telephone and fax. <br />
<br />
This is where the messages are uploaded for your campaigns. <br />
<br />
For a telephone campaign, you may need to upload up to three different messages.
One for humans, one for answer machines, and one for the DNC confirmation.
For the fax campaigns, you only need to upload a tiff image of the fax you
would like to send out.</p>

<p align="left">For voice messages, they must be wav format. Any recording
software will be able to save as wav format.</p>

<p align="left">It's recommended that you name the messages for easy
identification later on. The website allows you to play the messages
directly from the browser.</p>

<p align="left">Messages will be automatically converted to the appropriate
file format for telephone calls.</p>

<?
if ($level==sha1("level100")){
?>
<p align="left">There is a cron job that runs every minute and copies the
converted files (sln format) to the Asterisk voice servers.</p>
<?
}
?>
