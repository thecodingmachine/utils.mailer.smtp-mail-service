<?php
use Mouf\MoufManager;

// Controller declaration

MoufManager::getMoufManager()->declareComponent('smtpmailserviceinstall', 'Mouf\\Utils\\Mailer\\Controllers\\SmtpMailServiceInstallController', true);
MoufManager::getMoufManager()->bindComponents('smtpmailserviceinstall', 'template', 'moufInstallTemplate');
MoufManager::getMoufManager()->bindComponents('smtpmailserviceinstall', 'content', 'block.content');

