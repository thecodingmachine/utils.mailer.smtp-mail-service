<?php /* @var $this SmtpMailServiceInstallController */ ?>
<script type="text/javascript" charset="utf-8">


jQuery(function(){
	jQuery("#postfixDefault").click(function() {
		jQuery("#host").val('localhost');
		jQuery("#port").val('25');
		jQuery("#auth").val('');
		jQuery("#user").val('');
		jQuery("#password").val('');
		jQuery("#ssl").val('');
	});
	jQuery("#gmailDefault").click(function() {
		jQuery("#host").val('smtp.gmail.com');
		jQuery("#port").val('587');
		jQuery("#auth").val('login');
		jQuery("#user").val('your gmail address');
		jQuery("#password").val('your gmail password');
		jQuery("#ssl").val('tls');
	});
})


</script>

<h1>Configure your SMTP server</h1>

<button id="postfixDefault">Use Postfix default (Linux system)</button>
<button id="gmailDefault">Use Gmail default (test machine on Windows)</button>

<form action="install">
<input type="hidden" id="selfedit" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit) ?>" />

<p>The IP address or URL of your SMTP server. This is usually 'localhost'.</p>
<div>
	<label for="host">Host:</label>
	<input type="text" id="host" name="host" value="<?php echo plainstring_to_htmlprotected($this->host) ?>" />
</div>
<p>The port of the SMTP server. Keep this empty to use default port.</p>
<div>
	<label for="port">Port:</label>
	<input type="text" id="port" name="port" value="<?php echo plainstring_to_htmlprotected($this->port) ?>" />
</div>
<p>The authentication mode. Keep empty is you use a Postfix server on localhost.</p>
<div>
	<label for="auth">The authentication mode:</label>
	<select id="auth" name="auth">
		<option value=""></option>
		<option value="plain">plain</option>
		<option value="login">login</option>
		<option value="crammd5">crammd5</option>
	</select>
</div>
<p>The user to connect to the SMTP server (optional).</p>
<div>
	<label for="user">User:</label>
	<input type="text" id="user" name="user" value="<?php echo plainstring_to_htmlprotected($this->user) ?>" />
</div>
<div>
	<label for="password">Password:</label>
	<input type="text" id="password" name="password" value="<?php echo plainstring_to_htmlprotected($this->password) ?>" />
</div>
<p>The SSL mode to use (optional).</p>
<div>
	<label for="auth">SSL mode:</label>
	<select id="ssl" name="ssl">
		<option value=""></option>
		<option value="ssl">ssl</option>
		<option value="tls">tls</option>
	</select>
</div>

<?php 
MoufHtmlHelper::drawInstancesDropDown("Logger", "logger", "LogInterface", false, $this->loggerInstanceName);
?>


<div>
	<button name="action" value="install" type="submit">Next</button>
</div>
</form>