<?php


namespace Psr\Log;

use Psr\Log;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;


class Logger implements LoggerInterface
{
    private $loggers;

    public function emergency($message, array $context = array()):void
    {

        /**
         * Action must be taken immediately.
         *
         * Example: Entire website down, database unavailable, etc. This should
         * trigger the SMS alerts and wake you up.
         *
         * @param string $message = "Total crapp";
         * @param array $context
         * @return void
         */
       foreach ($this->loggers as $logger) {
           $logger->emergency($message, $context);
       }
    }

    public function alert($message, array $context = array()):void
    {

        $this->log(LogLevel::ALERT, $message, $context);

        /**
         * Critical conditions.
         *
         * Example: Application component unavailable, unexpected exception.
         *
         * @param string $message
         * @param array $context
         * @return void
         */
    }

    public function critical($message, array $context = array()):void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);

        /**
         * Runtime errors that do not require immediate action but should typically
         * be logged and monitored.
         *
         * @param string $message
         * @param array $context
         * @return void
         */
    }

    public function error($message, array $context = array()):void
    {
        $this->log(LogLevel::ERROR, $message, $context);
        /**
         * Exceptional occurrences that are not errors.
         *
         * Example: Use of deprecated APIs, poor use of an API, undesirable things
         * that are not necessarily wrong.
         *
         * @param string $message
         * @param array $context
         * @return void
         */
    }

    public function warning($message, array $context = array()):void
    {
        $this->log(LogLevel::WARNING, $message, $context);
        /**
         * Normal but significant events.
         *
         * @param string $message
         * @param array $context
         * @return void
         */
    }
    public function notice($message, array $context = array()):void
    {

        $this->log(LogLevel::NOTICE, $message, $context);
        /**
         * Interesting events.
         *
         * Example: User logs in, SQL logs.
         *
         * @param string $message
         * @param array $context
         * @return void
         */
    }

    public function info($message, array $context = array()):void
    {
        $this->log(LogLevel::INFO, $message, $context);
        /**
         * Detailed debug information.
         *
         * @param string $message
         * @param array $context
         * @return void
         */
    }

    public function debug($message, array $context = array()):void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
        /**
         * Logs with an arbitrary level.
         *
         * @param mixed $level
         * @param string $message
         * @param array $context
         * @return void
         */


    }

    public function log($level, $message, array $context = array())
    {
        // $d=mktime();
        $time = "Time: ".date("Y-m-d h:i:sa");
        $level = " Level:".$level." ";

        file_put_contents(DIR."/Var/Log/logFile.txt",$this->interpolate($time.$level.$message."\n", $context), FILE_APPEND | LOCK_EX);
    }

    /**
     * Interpolates context values into the message placeholders.
     */
    private function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }




//// a message with brace-delimited placeholder names
//$message = "User {username} created";
//
//// a context array of placeholder names => replacement values
//$context = array('username' => 'bolivar');
//
//// echoes "User bolivar created"
//echo interpolate($message, $context);

}