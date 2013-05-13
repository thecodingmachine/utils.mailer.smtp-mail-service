<?php
namespace Mouf\Utils\Mailer\Controllers;

use Mouf\Mvc\Splash\Controllers\Controller;

/**
 * The controller managing the install process.
 * It will query the database details.
 *
 * @Component
 */
class SmtpMailServiceInstallController extends Controller {
	public $selfedit;
	
	/**
	 * The active MoufManager to be edited/viewed
	 *
	 * @var MoufManager
	 */
	public $moufManager;
	
	/**
	 * The template used by the main page for mouf.
	 *
	 * @Property
	 * @Compulsory
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * Displays the first install screen.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function defaultAction($selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
				
		$this->template->addContentFile(dirname(__FILE__)."/../../../../views/installStep1.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Skips the install process.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only)
	 */
	public function skip($selfedit = "false") {
		InstallUtils::continueInstall($selfedit == "true");
	}

	protected $host;
	protected $port;
	protected $user;
	protected $password;
	protected $loggerInstanceName;
	
	/**
	 * Displays the second install screen.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function configure($selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$this->host = "localhost";
		$this->port = "25";
		$this->user = "";
		$this->password = "";
		$this->loggerInstanceName = "errorLogLogger";
				
		$this->template->addContentFile(dirname(__FILE__)."/../../../../views/installStep2.php", $this);
		$this->template->draw();
	}
	
	
	
	/**
	 * Action to create the database connection.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only)
	 */
	public function install($host, $port, $user, $password, $auth, $ssl, $logger, $selfedit = "false") {
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$moufManager = $this->moufManager;
		$configManager = $moufManager->getConfigManager();
		
		$constants = $configManager->getMergedConstants();
		
		if (!isset($constants['MAIL_HOST'])) {
			$configManager->registerConstant("MAIL_HOST", "string", "localhost", "The SMTP host to use (the IP address or URL of the database server). For instance, use 'smtp.gmail.com' to connect to gmail SMTP servers.");
		}
		
		if (!isset($constants['MAIL_PORT'])) {
			$configManager->registerConstant("MAIL_PORT", "int", "25", "The SMTP server port (the port of the SMTP server, keep empty to use default port). For instance, use '587' to connect to gmail SMTP servers.");
		}
		
		if (!isset($constants['MAIL_AUTH'])) {
			$configManager->registerConstant("MAIL_AUTH", "string", "", "The authentication mechanism for the SMTP server. Can be one of '', 'plain', 'login', 'crammd5'. For instance, use 'login' to connect to gmail SMTP servers.");
		}
		
		if (!isset($constants['MAIL_USERNAME'])) {
			$configManager->registerConstant("MAIL_USERNAME", "string", "", "The username to connect to the SMTP server. For gmail, use your gmail address.");
		}
		
		if (!isset($constants['MAIL_PASSWORD'])) {
			$configManager->registerConstant("MAIL_PASSWORD", "string", "", "The password to connect to the SMTP server. For gmail, use your gmail password.");
		}
		
		if (!isset($constants['MAIL_SSL'])) {
			$configManager->registerConstant("MAIL_SSL", "string", "", "The SSL mode to use, to connect to the SMTP server, if any. Can be one of '', 'ssl', 'tls'. For gmail, use 'tls'.");
		}
		
		if (!$moufManager->instanceExists("smtpMailService")) {
			$moufManager->declareComponent("smtpMailService", "SmtpMailService");
			$moufManager->setParameter("smtpMailService", "host", "MAIL_HOST", "config");
			$moufManager->setParameter("smtpMailService", "port", "MAIL_PORT", "config");
			$moufManager->setParameter("smtpMailService", "userName", "MAIL_USERNAME", "config");
			$moufManager->setParameter("smtpMailService", "password", "MAIL_PASSWORD", "config");
			$moufManager->setParameter("smtpMailService", "auth", "MAIL_AUTH", "config");
			$moufManager->setParameter("smtpMailService", "ssl", "MAIL_SSL", "config");
			$moufManager->bindComponent("smtpMailService", "log", $logger);
		}
		
		$configPhpConstants = $configManager->getDefinedConstants();
		$configPhpConstants['MAIL_HOST'] = $host;
		$configPhpConstants['MAIL_PORT'] = $port;
		$configPhpConstants['MAIL_USERNAME'] = $user;
		$configPhpConstants['MAIL_PASSWORD'] = $password;
		$configPhpConstants['MAIL_AUTH'] = $auth;
		$configPhpConstants['MAIL_SSL'] = $ssl;
		$configManager->setDefinedConstants($configPhpConstants);
		
		$moufManager->rewriteMouf();		
		
		InstallUtils::continueInstall($selfedit == "true");
	}
	
}