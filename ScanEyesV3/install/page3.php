<?php
if (@!$_POST['tos']) {
	echo "Setup can not continue, License agreement refused";
	exit();
}
if (@$_POST['rrtos']) {
	$unsubmittedRRAPIKEY = file_get_contents('http://activate.scaneyes.us/api.php?req=accept');
}else{
	$unsubmittedRRAPIKEY = "tos not accepted";
}


?>
<html>
	<head>
		<title>ScanEyes Install Step 3/5</title>
		<link rel="stylesheet" type="text/css" href="assets/style3.css">
	</head>
	<body>
		<form action="index.php?step=4" id="msform" method="POST">
			<ul id="progressbar">
				<li class="active">Admin and notification settings</li>
				<li>ScanEyes General settings</li>
				<li>Sharing and Analytics</li>
				<li>User settings</li>
				<li>LogRecorder settings</li>
			</ul>

			<!-- SCANEYES ADMIN SETTINGS -->

			<fieldset>
				<h2 class="fs-title">ScanEyes Setup Page 3/5</h2>
				<h3 class="fs-subtitle">Hover over a field to show more info</h3>
				Enter the username you would like to use as the ScanEyes webmaster
				<input name="adminusername" type="text" placeholder="Admin" title="you will use this username to log into ScanEyes"><br>
				Enter a password
				<input name="adminpassword" type="password" title="you will use this password with the username above to log into ScanEyes"><br>
				First Name: <input name="fn" type="text" placeholder="John"> 
				Last Name: <input name="ln" type="text" placeholder="Smith">
				Enter your email address for receiving ScanEyes Alerts
				<input name="globaladminemail" type="email" placeholder="myemail@example.com" title="This email address will allow the administrator(yourself) to receive alerts including, but not limited to, server errors and SDR errors"><br>
				Create a domain specific gmail account, this account will send password reset links, and account activation links to users.
				<input name="gmailaddr" type="email" title="You can set up a GMail account, and enter the credentials here and below, this is the email that will be sending alerts to the Administrator, and mailing activation codes to new users" placeholder="sdrscan-notify@gmail.com"><br>
				Enter the password for the G-Mail account (not encrypted)
				<input name="gmailpass" title="Enter the E-Mail password for the account above" type="password">
				<input type="button" name="next" class="next action-button" value="Next" />
			</fieldset>
			
			<!-- SCANEYES GENERAL SETTINGS -->

			<fieldset>
			<h2 class="fs-title">ScanEyes Setup Page 3.1/5</h2>
				Domain or IP Address
				<input name="domain" type="text" title="this field is used for making links work" placeholder="sub.mydomain.com OR 123.456.678.901"><br>
				Page encryption
				<select name="httpmethod" title="leave default unless you've editied your vhosts file">
					<option value="http://" selected="selected">NONE</option>
					<option value="https://">SSL/TLS</option>
				</select><br>
				Enable a Message of Day banner that can be displayed on the homepage
				<select name="motdenabled">
					<option value="no" selected="selected">No</option>
					<option value="yes">Yes</option>
				</select><br>
				MOTD Title
				<input name="motdtitle" type="text" value="ScanEyes v3 MOTD"><br>
				MOTD Body(html allowed)
				<textarea name="motdbody">This is the default message for ScanEyes.</textarea><br>
				Enter your local timezone for the server and SDR setup
				<select name="date_default_timezone_set">
					<option value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option>
					<option value="America/Adak">(GMT-10:00) Hawaii-Aleutian</option>
					<option value="Etc/GMT+10">(GMT-10:00) Hawaii</option>
					<option value="Pacific/Marquesas">(GMT-09:30) Marquesas Islands</option>
					<option value="Pacific/Gambier">(GMT-09:00) Gambier Islands</option>
					<option value="America/Anchorage">(GMT-09:00) Alaska</option>
					<option value="America/Ensenada">(GMT-08:00) Tijuana, Baja California</option>
					<option value="Etc/GMT+8">(GMT-08:00) Pitcairn Islands</option>
					<option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US & Canada)</option>
					<option value="America/Denver">(GMT-07:00) Mountain Time (US & Canada)</option>
					<option value="America/Chihuahua">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
					<option value="America/Dawson_Creek">(GMT-07:00) Arizona</option>
					<option value="America/Belize">(GMT-06:00) Saskatchewan, Central America</option>
					<option value="America/Cancun">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
					<option value="Chile/EasterIsland">(GMT-06:00) Easter Island</option>
					<option value="America/Chicago">(GMT-06:00) Central Time (US & Canada)</option>
					<option value="America/New_York" selected="selected">(GMT-05:00) Eastern Time (US & Canada)</option>
					<option value="America/Havana">(GMT-05:00) Cuba</option>
					<option value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
					<option value="America/Caracas">(GMT-04:30) Caracas</option>
					<option value="America/Santiago">(GMT-04:00) Santiago</option>
					<option value="America/La_Paz">(GMT-04:00) La Paz</option>
					<option value="Atlantic/Stanley">(GMT-04:00) Faukland Islands</option>
					<option value="America/Campo_Grande">(GMT-04:00) Brazil</option>
					<option value="America/Goose_Bay">(GMT-04:00) Atlantic Time (Goose Bay)</option>
					<option value="America/Glace_Bay">(GMT-04:00) Atlantic Time (Canada)</option>
					<option value="America/St_Johns">(GMT-03:30) Newfoundland</option>
					<option value="America/Araguaina">(GMT-03:00) UTC-3</option>
					<option value="America/Montevideo">(GMT-03:00) Montevideo</option>
					<option value="America/Miquelon">(GMT-03:00) Miquelon, St. Pierre</option>
					<option value="America/Godthab">(GMT-03:00) Greenland</option>
					<option value="America/Argentina/Buenos_Aires">(GMT-03:00) Buenos Aires</option>
					<option value="America/Sao_Paulo">(GMT-03:00) Brasilia</option>
					<option value="America/Noronha">(GMT-02:00) Mid-Atlantic</option>
					<option value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</option>
					<option value="Atlantic/Azores">(GMT-01:00) Azores</option>
					<option value="Europe/Belfast">(GMT) Greenwich Mean Time : Belfast</option>
					<option value="Europe/Dublin">(GMT) Greenwich Mean Time : Dublin</option>
					<option value="Europe/Lisbon">(GMT) Greenwich Mean Time : Lisbon</option>
					<option value="Europe/London">(GMT) Greenwich Mean Time : London</option>
					<option value="Africa/Abidjan">(GMT) Monrovia, Reykjavik</option>
					<option value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
					<option value="Europe/Belgrade">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
					<option value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
					<option value="Africa/Algiers">(GMT+01:00) West Central Africa</option>
					<option value="Africa/Windhoek">(GMT+01:00) Windhoek</option>
					<option value="Asia/Beirut">(GMT+02:00) Beirut</option>
					<option value="Africa/Cairo">(GMT+02:00) Cairo</option>
					<option value="Asia/Gaza">(GMT+02:00) Gaza</option>
					<option value="Africa/Blantyre">(GMT+02:00) Harare, Pretoria</option>
					<option value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option>
					<option value="Europe/Minsk">(GMT+02:00) Minsk</option>
					<option value="Asia/Damascus">(GMT+02:00) Syria</option>
					<option value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
					<option value="Africa/Addis_Ababa">(GMT+03:00) Nairobi</option>
					<option value="Asia/Tehran">(GMT+03:30) Tehran</option>
					<option value="Asia/Dubai">(GMT+04:00) Abu Dhabi, Muscat</option>
					<option value="Asia/Yerevan">(GMT+04:00) Yerevan</option>
					<option value="Asia/Kabul">(GMT+04:30) Kabul</option>
					<option value="Asia/Yekaterinburg">(GMT+05:00) Ekaterinburg</option>
					<option value="Asia/Tashkent">(GMT+05:00) Tashkent</option>
					<option value="Asia/Kolkata">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
					<option value="Asia/Katmandu">(GMT+05:45) Kathmandu</option>
					<option value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</option>
					<option value="Asia/Novosibirsk">(GMT+06:00) Novosibirsk</option>
					<option value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</option>
					<option value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
					<option value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk</option>
					<option value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
					<option value="Asia/Irkutsk">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
					<option value="Australia/Perth">(GMT+08:00) Perth</option>
					<option value="Australia/Eucla">(GMT+08:45) Eucla</option>
					<option value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
					<option value="Asia/Seoul">(GMT+09:00) Seoul</option>
					<option value="Asia/Yakutsk">(GMT+09:00) Yakutsk</option>
					<option value="Australia/Adelaide">(GMT+09:30) Adelaide</option>
					<option value="Australia/Darwin">(GMT+09:30) Darwin</option>
					<option value="Australia/Brisbane">(GMT+10:00) Brisbane</option>
					<option value="Australia/Hobart">(GMT+10:00) Hobart</option>
					<option value="Asia/Vladivostok">(GMT+10:00) Vladivostok</option>
					<option value="Australia/Lord_Howe">(GMT+10:30) Lord Howe Island</option>
					<option value="Etc/GMT-11">(GMT+11:00) Solomon Is., New Caledonia</option>
					<option value="Asia/Magadan">(GMT+11:00) Magadan</option>
					<option value="Pacific/Norfolk">(GMT+11:30) Norfolk Island</option>
					<option value="Asia/Anadyr">(GMT+12:00) Anadyr, Kamchatka</option>
					<option value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</option>
					<option value="Etc/GMT-12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
					<option value="Pacific/Chatham">(GMT+12:45) Chatham Islands</option>
					<option value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa</option>
					<option value="Pacific/Kiritimati">(GMT+14:00) Kiritimati</option>
				</select><br>
				Maximum calls per playlist
				<input name="maxcpp" type="number" min="1" max="1024" value="200" title="Max:1024"><br>
				Maximum days queryable (higher = slower)
				<input name="maxdq" type="number" min="1" max="9999" value="120" title="Showing more results, makes database reads slower for other people"><br>
				Maximum results per page
				<input name="maxrpp" type="number" min="1" max="500" value="200" title="Max:500"><br>
				<input type="button" name="previous" class="previous action-button" value="Previous" />
				<input type="button" name="next" class="next action-button" value="Next" />
			</fieldset>

			<!-- SCANEYES SHARING AND ANALYTICS SETTINGS -->

			<fieldset>
				<h2 class="fs-title">ScanEyes Setup Page 3.2/5</h2>
				Enable Google Analytics
				<select name="gaenabled">
					<option value="no" selected="selected">No</option>
					<option value="yes">Yes</option>
				</select><br>
				Google Analytics Code
				<input name="uacode" type="text" value="UA-########-#"><br>
				Users can share playlists and calls to Facebook, G+, and Reddit
				<select name="shareenabled">
					<option value="no">No</option>
					<option value="yes" selected="selected">Yes</option>
				</select><br>
				<input type="button" name="previous" class="previous action-button" value="Previous" />
				<input type="button" name="next" class="next action-button" value="Next" />
			</fieldset>

			<!-- SCANEYES USER SETTINGS -->

			<fieldset>
				<h2 class="fs-title">ScanEyes Setup Page 3.3/5</h2>
				Allow guests to create accounts
				<select name="acctcreateenabled">
					<option value="no">No</option>
					<option value="yes" selected="selected">Yes</option>
				</select><br>
				Allow guests to listen to live streams
				<select name="gueststream">
					<option value="no" selected="selected">No</option>
					<option value="yes">Yes</option>
				</select>
				<h1>User access levels</h1>
				<table id="users">
					<thead>
						<tr>
							<th>Group ID</th>
							<th>GroupName</th>
							<th>Abilities</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>Guest</td>
							<td>User level of a newly registered member, can browse/search</td>
						</tr>
						<tr>
							<td>2</td>
							<td>Streamer</td>
							<td>Streaming allowed, can make personal notes</td>
						</tr>
						<tr>
							<td>3</td>
							<td>Administrator</td>
							<td>Can restart system services, delete rename TGs,RIDs, and calls, manage users</td>
						</tr>
						<tr>
							<td>4</td>
							<td>Global Admin</td>
							<td>Can delete Administrators, change important settings</td>
						</tr>
					</tbody>
				</table>
				Minimum user level required to make playlists
				<input name="minguestpllvl" type="number" min="1" max="4" value="1"><br>
				Minimum user level required to browse talkgroups
				<input name="mintgidbrowselvl" type="number" min="1" max="4" value="1"><br>
				Minimum user level required to use search
				<input name="mincallbrowselvl" type="number" min="1" max="4" value="1"><br>
				<input type="button" name="previous" class="previous action-button" value="Previous" />
				<input type="button" name="next" class="next action-button" value="Next" />
			</fieldset>

			<!-- SCANEYES LOGRECORDER SETTINGS -->

			<fieldset>
				<h2 class="fs-title">ScanEyes Setup Page 3.4/5</h2>
				<br>
				<img src="assets/sid.png">
				<br>The SID can be found in the URL of the RRDB page
				Radioreference Database SystemID
				<input name="rrdbsid" type="text" placeholder="7337"><br>
				Radioreference API Key (prefilled)
				<input name="rrapikey" type="password" value="<?php echo $unsubmittedRRAPIKEY ?>"><br>
				Digital voice decoder ptions for DSD/DSDPlus <a target="_blank" href="http://pastebin.com/4FbWtbKm">Guide</a>
				<input name="dsdoptions" type="text" value="-f1 -dr1"><br>
				Sound extension (e.g. [.wav | .mp3])
				<input name="sndext" type="text" value=".mp3"><br>
				Windows Audio Device number, selects which audio input logrecorder will record from
				<input name="wad" type="number" value="2"><br>
				Full location and filename to sdrsharptrunking.log
				<input name="trunkloc" type="text" placeholder="C:/xampp/htdocs/UniTrunker/sdrsharptrunking.log"><br>
				Location to save calls to (please leave default)
				<input name="callsavedir" type="text" value="../ScanEyesV3/calls/"><br>
				Location for ScanEyes to look for calls (please leave default)
				<input name="sccallsavedir" type="text" value="calls"><br>
				<input type="button" name="previous" class="previous action-button" value="Previous" />
				<input type="submit" name="submit" class="action-button" value="Submit" />
			</fieldset>
		</form>
		<!-- jQuery -->
		<script src="assets/jquery-1.9.1.min.js" type="text/javascript"></script>
		<!-- jQuery easing plugin -->
		<script src="assets/jquery.easing.min.js" type="text/javascript"></script>
		<script language="javascript" type="text/javascript" src="assets/paginator.js"></script>
	</body>
</html>