<?php
use Mouf\MoufManager;

// Controller declaration

MoufManager::getMoufManager()->declareComponent('smtpmailserviceinstall', 'SmtpMailServiceInstallController', true);
MoufManager::getMoufManager()->bindComponents('smtpmailserviceinstall', 'template', 'installTemplate');
?>