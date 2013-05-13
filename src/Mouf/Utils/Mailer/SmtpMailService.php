<?php
namespace Mouf\Utils\Mailer;

require_once 'Zend/Mail.php';
require_once 'Zend/Mail/Transport/Smtp.php';

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
	 * @var Zend_Mail_Transport_Smtp
	 */
	private $zendMailTransport;
	
	/**
	 * Sends the mail passed in parameter.
	 *
	 * @param MailInterface $mail The mail to send.
	 */
	public function send(MailInterface $mail) {
		$this->initZendMailTransport();
		
		$zendMail = new Zend_Mail($mail->getEncoding());

		if ($mail->getBodyText() != null) {
			$zendMail->setBodyText($mail->getBodyText());
		}
		if ($mail->getBodyHtml() != null) {
			$zendMail->setBodyHtml($mail->getBodyHtml());
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
			$zendAttachment = $zendMail->createAttachment($attachment->getFileContent(), $attachment->getMimeType(), $attachment_disposition, $encoding, $attachment->getFileName());
			$zendAttachment->id = $attachment->getContentId();
		}
		

		$zendMail->send($this->zendMailTransport);

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
		if (!empty($this->port)) {
			$config['port'] = $this->port;
		}
		if (!empty($this->auth)) {
			$config['auth'] = $this->auth;
		}
		if (!empty($this->userName)) {
			$config['username'] = $this->userName;
		}
		if (!empty($this->password)) {
			$config['password'] = $this->password;
		}
		if (!empty($this->ssl)) {
			$config['ssl'] = $this->ssl;
		} 
		
		$this->zendMailTransport = new Zend_Mail_Transport_Smtp($this->host, $config);
	}
}
?>