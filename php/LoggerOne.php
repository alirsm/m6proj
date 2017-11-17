<?php

/*
 * $Author: paericksen $
 * $Date: 2016-04-20 16:26:38 -0400 (Wed, 20 Apr 2016) $
 * $Revision: 11 $
 * $URL: https://svn.voicelab.bhnis.net/BHNIS_Logger/trunk/src/Logger.php $
 *
 * please commit changes to SVN
 *
 * svn commit -m “your comments” [file.name]
 */

class LoggerOne {

	public $logEnabled;

	public $conEnabled;

	public $logLevel;

	public $conLevel;

	public $logDir;

	public $logFile;

	const DEBUG = 4;

	const INFO = 3;

	const WARN = 2;

	const ERROR = 1;

	const CRITICAL = 0;

	public function __construct($logEnabled = true, $conEnabled = false, $logLevel = self::DEBUG, $conLevel = self::DEBUG, $logFile = null){
		$this->logEnabled = function_exists('posix_getuid') ? $logEnabled : false;
		$this->conEnabled = $conEnabled;
		$this->logLevel = $logLevel;
		$this->conLevel = $conLevel;
		//$this->logFile = empty($logFile) ? basename($_SERVER['SCRIPT_NAME']) . '.log' : basename($logFile);
		//$this->logDir = empty($logDir) ? realpath('../logs') : realpath($logDir);

		//$this->logFile = "{$this->logDir}/{$this->logFile}";
		$this->logFile = "/tmp/m6ui.log";
	}

	private function logger($level, $msg){
		$m = date("Y-m-d H:i:s") . " {$msg}\n";

		if($this->logEnabled){
			if($this->logLevel >= $level){
				try{
					file_put_contents($this->logFile, $m, FILE_APPEND);
				}
				catch(Exception $e){
					echo ("could not open file {$this->logFile}");
				}
			}

			// make sure other users can write to the log file if we own it
			if(file_exists($this->logFile)){
				if(posix_getuid() == fileowner($this->logFile)){
					chmod($this->logFile, 0666);
				}
			}
		}

		if($this->conEnabled){
			if($this->conLevel >= $level){
				print($m);
			}
		}
	}

	public function debug($msg){
		self::logger(self::DEBUG, "DEBUG $msg");
	}

	public function info($msg){
		self::logger(self::INFO, "INFO $msg");
	}

	public function warn($msg){
		self::logger(self::WARN, "WARN $msg");
	}

	public function error($msg){
		self::logger(self::ERROR, "ERROR $msg");
	}

	public function critical($msg){
		self::logger(self::CRITICAL, "CRITICAL $msg");
	}

	public function d($msg){
		self::debug($msg);
	}

	public function i($msg){
		self::info($msg);
	}

	public function w($msg){
		self::warn($msg);
	}

	public function e($msg){
		self::error($msg);
	}

	public function c($msg){
		self::critical($msg);
	}
}

