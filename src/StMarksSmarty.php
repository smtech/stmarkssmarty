<?php

/** StMarksSmarty and related classes */

namespace smtech\StMarksSmarty;

use \Battis\BootstrapSmarty\BootstrapSmarty;

/**
 * A wrapper for Smarty to set (and maintain) defaults
 *
 * @author Seth Battis <SethBattis@stmarksschool.org>
 **/
class StMarksSmarty extends BootstrapSmarty {
	
	const KEY = 'StMarkSmarty';
	
	private $isFramed = false;
	
	public static function getSmarty($template = null, $config = null, $compile = null, $cache = null) {
		if (self::$singleton === null) {
			self::$singleton = new self($template, $config, $compile, $cache);
		}
		return self::$singleton;
	}

	public function __construct($template = null, $config = null, $compile = null, $cache = null) {
		parent::__construct($template, $config, $compile, $cache);
		
		$this->addTemplateDir(__DIR__ . '/../templates', self::KEY);
		$this->addStylesheet(
			(
				!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ?
					'http://' :
					'https://'
			) .
			$_SERVER['SERVER_NAME'] . preg_replace("|^{$_SERVER['DOCUMENT_ROOT']}(.*)/src$|", '$1/css/StMarksSmarty.css', __DIR__),
			self::KEY
		);
	}
	
	public function setFramed($isFramed) {
		$this->isFramed = (bool) $isFramed;
	}
	
	public function isFramed() {
		return $this->isFramed;
	}
	
	public function display($template = 'page.tpl', $cache_id = null, $compile_id = null, $parent = null) {
		if (isset($GLOBALS['metadata'])) {
			if (!isset($GLOBALS['metadata']['APP_URL'])) {
				$GLOBALS['metadata']['APP_URL'] =
				(
					!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ?
						'http://' :
						'https://'
				) .
				$_SERVER['SERVER_NAME'] . preg_replace("|^{$_SERVER['DOCUMENT_ROOT']}(.*)$|", '$1', str_replace('/vendor/smtech/stmarkssmarty', '', __DIR__));
			}
			if (!isset($GLOBALS['metadata']['APP_NAME'])) {
				$GLOBALS['metadata']['APP_NAME'] = 'St. Mark&rsquo;s School';
			}
			$this->assign('metadata', $GLOBALS['metadata']);
		}
		
		if ($this->isFramed()) {
			$this->addStylesheet(
				(
					!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ?
						'http://' :
						'https://'
				) .
				$_SERVER['SERVER_NAME'] . preg_replace("|^{$_SERVER['DOCUMENT_ROOT']}(.*)/src$|", '$1/css/StMarksSmarty.css?isFramed=true', __DIR__),
				self::KEY
			);
		}
		$this->assign('isFramed', $this->isFramed());
		
		parent::display($template, $cache_id, $compile_id, $parent);
	}
}

/**
 * All exceptions thrown by StMarkSmarty
 *
 * @author Seth Battis <SethBattis@stmarksschool.org>
 **/
class StMarksSmarty_Exception extends \Battis\BootstrapSmarty\BootstrapSmarty_Exception {
}
	
?>