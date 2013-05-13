The SmtpMailService
===================

The <code>SmtpMailService</code> is used to send mails using a SMTP mail server.
The instance of the class takes one compulsory parameter: <b>host</b>, which is the address of the server.


By default, on Linux systems, it is likely you will use the local mail server (host=127.0.0.1). You will have a "sendmail" or "postfix" server installed
on your machine.
If you are performing your developments on a Windows machine, it is quite likely that you will not have an SMTP server on your machine. You will 
therefore have to use a remote server. To access the remote server, you will certainly have to use login/passwords, etc...

When this package is installed, it will create a default "smtpMailService" instance and will ask you to configure this instance.

Tip: using your gmail account to send mails
-------------------------------------------

In a development environment, it can be useful to use you gmail account. Here are the settings:

- host =&gt; 'smtp.gmail.com'
- ssl =&gt; 'tls'
- port =&gt; 587
- auth =&gt; 'login'
- username => <em>Your gmail mail address</em>
- password => <em>Your password</em>


Example use
-----------

To see how to send a mail, refer to the Introduction to Mouf's mail architecture