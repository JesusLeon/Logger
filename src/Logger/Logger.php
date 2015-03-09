<?php

namespace Logger;

use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\HipChatHandler;

class Logger {

    public $monolog;

    private $logger_name = 'Logger';

    private $stream_name = 'logger.log';

    private $hipchat_room = 'Logger_Room';

    private $hipchat_token = '';

    private $backtrace = array();

    private $mail_recipients = array();

    private $mail_subject = "Logger";

    private $mail_sender = "logger@example.com";

    /**
     * @param null $logger_name
     * @param null $stream_name
     */
    function __construct($logger_name=null, $stream_name=null)
    {
        $this->setLoggerName($logger_name);

        $this->setStreamName($stream_name);

        $this->instantiate();

        $this->pushHandlers();
    }

    /**
     * @param $stream_name
     */
    public function setStreamName($stream_name)
    {
        $this->stream_name = $stream_name ?: $this->stream_name;
    }

    /**
     * @param $logger_name
     */
    public function setLoggerName($logger_name)
    {
        $this->logger_name = $logger_name ?: $this->logger_name;
    }

    /**
     * Create Monolog instance.
     */
    private function instantiate()
    {
        $this->monolog = new Monolog($this->logger_name);
    }

    /**
     * Set the logging channels.
     */
    private function pushHandlers()
    {
        $this->monolog->pushHandler(new StreamHandler($this->stream_name, Monolog::DEBUG));

        $this->monolog->pushHandler(new NativeMailerHandler($this->mail_recipients,$this->mail_subject, $this->mail_sender, Monolog::ERROR));

        $this->monolog->pushHandler(new HipChatHandler($this->hipchat_token, $this->hipchat_room, $this->logger_name, true, Monolog::INFO));
    }

    /**
     * @param $message
     * @param array $context
     * @return mixed
     */
    public function debug($message, array $context = array())
    {
        return $this->addRecord(Monolog::DEBUG, $message, $context);
    }

    /**
     * Adds a log record at the INFO level.
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function info($message, array $context = array())
    {
        return $this->addRecord(Monolog::INFO, $message, $context);
    }

    /**
     * Adds a log record at the NOTICE level.
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function notice($message, array $context = array())
    {
        return $this->addRecord(Monolog::NOTICE, $message, $context);
    }

    /**
     * Adds a log record at the WARNING level.
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function warn($message, array $context = array())
    {
        return $this->addRecord(Monolog::WARNING, $message, $context);
    }

    /**
     * Adds a log record at the ERROR level.
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function error($message, array $context = array())
    {
        return $this->addRecord(Monolog::ERROR, $message, $context);
    }

    /**
     * Adds a log record at the CRITICAL level.
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function critical($message, array $context = array())
    {
        return $this->addRecord(Monolog::CRITICAL, $message, $context);
    }

    /**
     * Adds a log record at the ALERT level.
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function alert($message, array $context = array())
    {
        return $this->addRecord(Monolog::ALERT, $message, $context);
    }

    /**
     * Adds a log record at the EMERGENCY level.
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function emergency($message, array $context = array())
    {
        return $this->addRecord(Monolog::EMERGENCY, $message, $context);
    }

    /**
     * Adds a log record.
     *
     * @param  integer $level   The logging level
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function addRecord($level, $message, array $context = array())
    {
        $this->setBacktrace();

        $context = array_merge($context, $this->backtrace);

        $this->monolog->addRecord($level, $message, $context);
    }

    /**
     * Add small debugging information.
     */
    private function setBacktrace()
    {
        $trace  = debug_backtrace();

        if(isset($trace[2]['file']))
            $this->backtrace['_backtrace_level_2'] = $trace[2]['file'] .':'. $trace[2]['line'];

        if(isset($trace[3]['file']))
            $this->backtrace['_backtrace_level_3'] = $trace[3]['file'] .':'. $trace[3]['line'];

        if(isset($trace[4]['file']))
            $this->backtrace['_backtrace_level_4'] = $trace[4]['file'] .':'. $trace[4]['line'];

        if(isset($trace[5]['file']))
            $this->backtrace['_backtrace_level_5'] = $trace[5]['file'] .':'. $trace[5]['line'];
    }

}