<h1>Setting up your mail server</h1>

<p>You will need a mail server. This installation wizard will create a "smtpMailService" instance, and will add 6 config parameters:</p>
<ul>
	<li><b>MAIL_HOST</b>: The SMTP host to use.</li>
	<li><b>MAIL_PORT</b>: The SMTP server port.</li>
	<li><b>MAIL_AUTH</b>: The authentication mode. Can be one of: "", "plain", "login", "crammd5".</li>
	<li><b>MAIL_USERNAME</b>: The user to authenticate on the SMTP server (optional).</li>
	<li><b>MAIL_PASSWORD</b>: The password to access the SMTP server (optional).</li>
	<li><b>MAIL_SSL</b>: The ssl mode to use. Can be one of: "", "ssl", "tls".</li>
</ul>

<form action="configure">
	<button>Configure mail server</button>
</form>
<form action="skip">
	<button>Skip</button>
</form>