<?php
namespace Mouf\Utils\Mailer;

use Mouf\Utils\Log\LogInterface;

use Zend\Mail\Message;

use Zend\Mail\Transport\SmtpOptions;

use Zend\Mail\Transport\Smtp;

use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
/**
 * This class sends mails using the Zend Framework SMTP mailer.<br/>
 * <br/>
 * Note: if you are running a Windows machine, and therefore don't have an SMTP server, 
 * for testing purpose, you can use your gmail account:<br/>
 * <br/>
 * <ul>
 * <li>host => 'smtp.gmail.com'</li>
 * <li>ssl => 'tls'</li>
 * <li>port => 587</li>
 * <li>auth => 'login'</li>
 * <li>username => <em>Your gmail mail address</em></li>
 * <li>password => <em>Your password</em></li>
 * </ul>
 * Note: For secured mail that use the tls or ssl encrypting, the php_openssl extension must be installed.
 * 
 * @Component
 */
class SmtpMailService implements MailServiceInterface {
	
	/**
	 * The SMTP host to use.
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $host = "127.0.0.1";
	
	/**
	 * The logger to use.
	 *
	 * @Property
	 * @Compulsory
	 * @var LogInterface
	 */
	public $log;
	
	/**
	 * The authentication mode.
	 * Can be one of: "", "plain", "login", "crammd5"
	 *
	 * @Property
	 * //@OneOf("plain", "login", "crammd5")
	 * @var string
	 */
	public $auth;
	
	/**
	 * The user to authenticate.
	 *
	 * @Property
	 * @var string
	 */
	public $userName;
	
	/**
	 * The password.
	 *
	 * @Property
	 * @var string
	 */
	public $password;
	
	/**
	 * The port to use.
	 *
	 * @Property
	 * @var int
	 */
	public $port;
	
	/**
	 * The SSL mode to use, if any.
	 *
	 * @Property
	 * // @OneOf("ssl", "tls")
	 * @var int
	 */
	public $ssl;
	
	/**
	 * The Zend mail transport.
	 *
	 * @var Smtp
	 */
	private $zendMailTransport;
	
	/**
	 * Sends the mail passed in parameter.
	 *
	 * @param MailInterface $mail The mail to send.
	 */
	public function send(MailInterface $mail) {
		$this->initZendMailTransport();
		
		$zendMail = new Message();
		
		$zendMail->setEncoding($mail->getEncoding());

		$parts = array();
		
		if ($mail->getBodyText() != null) {
			$text = new MimePart($mail->getBodyText());
			$text->type = "text/plain";
			$text->encoding = $mail->getEncoding();
			$parts[]  = $text;
		}
		if ($mail->getBodyHtml() != null) {
			$bodyHtml = new MimePart($mail->getBodyHtml());
			$bodyHtml->type = "text/html;charset=".$mail->getEncoding();
			$bodyHtml->encoding = $mail->getEncoding();
			$parts[]  = $bodyHtml;
		}
		
		if ($mail->getFrom()) {
			$zendMail->setFrom($mail->getFrom()->getMail(), $mail->getFrom()->getDisplayAs());
		}
		$zendMail->setSubject($mail->getTitle());
		foreach ($mail->getToRecipients() as $recipient) {
			$zendMail->addTo($recipient->getMail(), $recipient->getDisplayAs());
		}
		foreach ($mail->getCcRecipients() as $recipient) {
			$zendMail->addCc($recipient->getMail(), $recipient->getDisplayAs());
		}
		foreach ($mail->getBccRecipients() as $recipient) {
			$zendMail->addBcc($recipient->getMail(), $recipient->getDisplayAs());
		}
		foreach ($mail->getAttachements() as $attachment) {
			$encodingStr = $attachment->getEncoding();
			switch ($encodingStr) {
				case "ENCODING_7BIT":
					$encoding = Zend_Mime::ENCODING_7BIT;
					break;
				case "ENCODING_8BIT":
					$encoding = Zend_Mime::ENCODING_8BIT;
					break;
				case "ENCODING_QUOTEDPRINTABLE":
					$encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE;
					break;
				case "ENCODING_BASE64":
					$encoding = Zend_Mime::ENCODING_BASE64;
					break;
			}
			$attachment_disposition = $attachment->getAttachmentDisposition();
			switch ($attachment_disposition) {
				case "inline":
					$attachment_disposition = Zend_Mime::DISPOSITION_INLINE;
					break;
				case "attachment":
					$attachment_disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
					break;
				case "":
				case null:
					$attachment_disposition = null;
					break;
				default:
					throw new Exception("Invalid attachment disposition for mail. Should be one of: 'inline', 'attachment'");
			}
			
			$attachment = new MimePart($attachment->getFileContent());
			$attachment->type = $attachment->getMimeType();
			$attachment->disposition = $attachment_disposition;
			$attachment->encoding = $encoding;
			$attachment->filename = $attachment->getFileName();
			$attachment->id = $attachment->getContentId();
			
			$parts[] = $attachment;
		}
		

		$body = new MimeMessage();
		$body->setParts($parts);
		$zendMail->setBody($body);
		
		if ($mail->getBodyText() != null && $mail->getBodyHtml() != null) {
			$zendMail->getHeaders()->get('content-type')->setType('multipart/alternative');
		}

		$this->zendMailTransport->send($zendMail);

		// Let's log the mail:
		$recipients = array_merge($mail->getToRecipients(), $mail->getCcRecipients(), $mail->getBccRecipients());
		$recipientMails = array();
		foreach ($recipients as $recipient) {
			$recipientMails[] = $recipient->getMail();
		}
		$this->log->debug("Sending mail to ".implode(", ", $recipientMails).". Mail subject: ".$mail->getTitle());
	}
	
	
	private function initZendMailTransport() {
		if ($this->zendMailTransport != null) {
			return;
		}
		
		$config = array();
		if (!empty($this->host)) {
			$config['name'] = $this->host;
			$config['host'] = $this->host;
		}
		if (!empty($this->port)) {
			$config['port'] = $this->port;
		}
		if (!empty($this->auth)) {
			$config['connection_class'] = $this->auth;
		}
		if (!empty($this->userName)) {
			$config['connection_config']['username'] = $this->userName;
		}
		if (!empty($this->password)) {
			$config['connection_config']['password'] = $this->password;
		}
		if (!empty($this->ssl)) {
			$config['connection_config']['ssl'] = $this->ssl;
		} 
		
		
		$this->zendMailTransport = new Smtp();
		$options   = new SmtpOptions($config);

		$this->zendMailTransport->setOptions($options);
		
	}
}
?>