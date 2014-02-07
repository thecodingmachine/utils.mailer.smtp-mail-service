The SmtpMailService
===================

The `SmtpMailService` is used to send mails using a SMTP mail server.

The `SmtpMailService` is designed to be simple to use. Behind the scene, the <code>SmtpMailService</code>
is using the more complex (and more powerful) ZendFramework 2 mail service.

Install
-------
This package is part of the [Mouf PHP framework](http://mouf-php.com). As such, it comes with a nice graphical installer.

![Install screen](doc/images/smtp_install.png)

You can configure the settings to connect to your SMTP server.

There is one compulsory parameter: <b>host</b>, which is the address of the server.

By default, on Linux systems, it is likely you will use the local mail server (host=127.0.0.1). You will have a "sendmail" or "postfix" server installed
on your machine.
If you are performing your developments on a Windows machine, it is quite likely that you will not have an SMTP server on your machine. You will 
therefore have to use a remote server. To access the remote server, you will certainly have to use login/passwords, etc...

When this package is installed, it will create a default "smtpMailService" and put in this instance all the parameters you have provided.

<div class="alert alert-info">Note: nothing prevents you from creating several instances of the SmtpMailService class
with different parameters (in the rare case you have an application that needs to connect to several SMTP servers).</div>

After installation, you will see that a number of constants have been added to your `config.php` file.
When deploying on other servers, you can of course change those constants to adapt to the settings of the server.

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

Below is a sample code you can use to send a mail.

```php
use Mouf\Utils\Mailer\Mail;
use Mouf\Utils\Mailer\SmtpMailService;

// First, let's create the mail object
$mail = new Mail();
$mail->setTitle("My mail");
$mail->setBodyText("This is my mail!");
$mail->setBodyHtml("This is my &lt;b&gt;mail&lt;/b&gt;!");
$mail->setFrom(new MailAddress("my@server.com", "Server"));
$mail->addToRecipient(new MailAddress("david@email.com", "David"));

// Let's get the instance of the service
$mailService = Mouf::getSmtpMailService();

// Finally, we send the mail
$mailService->send($mail);
```

To learn more about how to send mails, refer to the [Introduction to Mouf's mail architecture](http://mouf-php.com/packages/mouf/utils.mailer.mail-interface/README.md).
