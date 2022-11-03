<?php


/**************************************************************************
*
* Title:			config reader class
* Version:       	1.0
* Description:   This class reads the configuration file once and allows access through a public methods
*
*************************************************************************/

class ConfigReader
{
	// property declaration
	private $_configFile = "";
	private $_configObj = '';
	private $_env = '';

	//-------------------------------------------------------------------------
	// Constructor
	//-------------------------------------------------------------------------
	public function __construct()
	{
		$this->_configFile = file_get_contents(LIB . 'config/swipezconfig.json');
		$this->_configObj = json_decode($this->_configFile);
		$this->_env = getenv('ENV');
	}

	public function getDBConfig()
	{
		$env = $this->_env;

		$dbArr['URL']=$this->_configObj->$env->REPOSITORY->DB1;
		$dbArr['USER']=$this->_configObj->$env->REPOSITORY->DB2;
		$dbArr['CRED']=$this->_configObj->$env->REPOSITORY->DB3;
		$dbArr['SCHEMA']=$this->_configObj->$env->REPOSITORY->DB4;

		return $dbArr;
	}

	public function getLogConfig()
	{
		$env = $this->_env;
		$logPath = $this->_configObj->$env->LOG->CONF;

		return $logPath;
	}

        public function getEnv()
        {
            return $this->_env;
        }
}
