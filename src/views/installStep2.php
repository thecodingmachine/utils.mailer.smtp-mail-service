<?php use Mouf\Utils\Common\MoufHelpers\MoufHtmlHelper;

/* @var $this SmtpMailServiceInstallController */ ?>
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

<div class="control-group">
	<button class="btn" id="postfixDefault">Use Postfix default (Linux system)</button>
	<button class="btn" id="gmailDefault">Use Gmail default (test machine on Windows)</button>
</div>

<form action="install" class="form-horizontal">
	<input type="hidden" id="selfedit" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit) ?>" />
	
	<div class="control-group">
		<label class="control-label" for="host">Host:</label>
		<div class="controls">
			<input type="text" id="host" name="host" value="<?php echo plainstring_to_htmlprotected($this->host) ?>" />
			<span class="help-block">The IP address or URL of your SMTP server. This is usually 'localhost'.</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="port">Port:</label>
		<div class="controls">
			<input type="text" id="port" name="port" value="<?php echo plainstring_to_htmlprotected($this->port) ?>" />
			<span class="help-block">The port of the SMTP server. Keep this empty to use default port.</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="auth">The authentication mode:</label>
		<div class="controls">
			<select id="auth" name="auth">
				<option value=""></option>
				<option value="plain">plain</option>
				<option value="login">login</option>
				<option value="crammd5">crammd5</option>
			</select>
			<span class="help-block">The authentication mode. Keep empty is you use a Postfix server on localhost.</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="user">User:</label>
		<div class="controls">
			<input type="text" id="user" name="user" value="<?php echo plainstring_to_htmlprotected($this->user) ?>" />
			<span class="help-block">The user to connect to the SMTP server (optional).</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="password">Password:</label>
		<div class="controls">
			<input type="text" id="password" name="password" value="<?php echo plainstring_to_htmlprotected($this->password) ?>" />
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="auth">SSL mode:</label>
		<div class="controls">
			<select id="ssl" name="ssl">
				<option value=""></option>
				<option value="ssl">ssl</option>
				<option value="tls">tls</option>
			</select>
			<span class="help-block">The SSL mode to use (optional).</span>
		</div>
	</div>
	
<?php 
MoufHtmlHelper::drawInstancesDropDown("Logger", "logger", "Mouf\\Utils\\Log\\LogInterface", false, $this->loggerInstanceName);
?>
	
	<div class="control-group">
		<div class="controls">
			<button class="btn btn-primary" name="action" value="install" type="submit">Next</button>
		</div>
	</div>
</form>