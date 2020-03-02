<?php
class Cron
{
    private static $_var = array();
    private static $_run = true;

    public static function controller($controller){
        self::$_var['controller'] = $controller;
        self::$_var['date'] = getdate();
        return new static();
    }

    public static function run(){
      if(self::$_run && isset(self::$_var['controller'])){
        if(defined('CRON_JOBS') && CRON_JOBS){
          include('cron/cron_'.self::$_var['controller'].'.php');
        }
      }
      return self::$_run;
    }

    public static function minute($val){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['minutes']) == intval($val);
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

    public static function hour($val){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['hours']) == intval($val);
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

    public static function day($val){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['mday']) == intval($val);
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

    public static function weekDay($val){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['wday']) == intval($val);
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

    public static function month($val){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['mon']) == intval($val);
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

    public static function year($val){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['year']) == intval($val);
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

    public static function yearDay($val){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['yday']) == intval($val);
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

    public static function everyMinute($val=1){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['minutes'])%intval($val) == 0;
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

    public static function everyHour($val=1){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['hours'])%intval($val)==0;
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

    public static function everyDay($val=1){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['mday'])%intval($val)==0;
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

    public static function everyWeekDay($val=1){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['wday'])%intval($val)==0;
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

    public static function everyMonth($val=1){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['mon'])%intval($val) == 0;
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

    public static function everyYear($val=1){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['year'])%intval($val)==0;
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

    public static function everyYearDay($val=1){
      if(self::$_run){
        self::$_run = intval(self::$_var['date']['yday'])%intval($val)==0;
      }
      $GLOBALS['cron'][self::$_var['controller']] = self::$_run;
      return new static();
    }

}
