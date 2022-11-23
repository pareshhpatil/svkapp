<?php

/**
 * This is a base class for all object types within Swipez application.
 * This call instantiates the log object and other bootstrapping objects required for Swipez application.
 *
 * @author Shuhaid
 */
class SwipezLogger {

    public static $_logger = NULL;
    public static $path = NULL;

    function __construct($logpath_ = NULL) {
        self::$path = $logpath_;
    }

    public static function debug($class_ = NULL, $message_) {
        self::instantiate($class_);
        self::$_logger->debug($message_);
    }

    public static function info($class_, $message_) {
        self::instantiate($class_);
        self::$_logger->info($message_);
    }

    public static function warn($class_, $message_) {
        self::instantiate($class_);
        self::$_logger->warn($message_);
    }

    public static function error($class_, $message_) {
        self::instantiate($class_);
        self::$_logger->error($message_);
    }

    public static function instantiate($class_) {
        if (!isset(self::$_logger)) {
            // Include 'Config File'
            //require_once('log4php/Logger.php');
            require_once UTIL . "ConfigReader.php";
            $configReader = new ConfigReader();
            if (self::$path == NULL) {
                $logPath = $configReader->getLogConfig();
            } else {
                $logPath = self::$path;
            }

            Logger::configure($logPath);

            if (isset($class_)) {
                self::$_logger = Logger::getLogger($class_);
            } else {
                self::$_logger = Logger::getLogger(__CLASS__);
            }

            $env = $configReader->getEnv();
            $env = strtoupper($env);
            switch ($env) {
                case 'DEV':
                    self::$_logger->setLevel(LoggerLevel::getLevelDebug());
                    break;
                case 'QA':
                    self::$_logger->setLevel(LoggerLevel::getLevelInfo());
                    break;
                case 'PROD':
                    self::$_logger->setLevel(LoggerLevel::getLevelWarn());
                    break;
                default:
                    self::$_logger->setLevel(LoggerLevel::getLevelDebug());
                    break;
            }
        } else {
            if (isset($class_)) {
                self::$_logger = Logger::getLogger($class_);
            } else {
                self::$_logger = Logger::getLogger(__CLASS__);
            }
        }
    }

}
