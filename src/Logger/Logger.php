<?php

namespace Logger;

use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\HipChatHandler;

class Logger {

    public $monolog;

    protected $logger_name = 'Logger';

    protected $stream_name = 'logger.log';

    protected $hipchat_room = 'Logger_Room';

    protected $hipchat_token = '';

    protected $backtrace = array();

    protected $mail_recipients = array();

    protected $mail_subject = "Logger";

    protected $mail_sender = "logger@example.com";

    private $backtrace_length = null;

    private $backtrace_offset = 0;

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
    protected function instantiate()
    {
        $this->monolog = new Monolog($this->logger_name);
    }

    /**
     * Set the logging channels.
     */
    protected function pushHandlers()
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
    protected function setBacktrace()
    {
        $trace  = debug_backtrace();

        $this->backtrace_length = ! is_null($this->backtrace_length) ? $this->backtrace_length : count($trace)-1;

        $this->backtrace = array();

        for($i = $this->backtrace_offset; $i < $this->backtrace_offset + $this->backtrace_length; $i++)
        {
            if(isset($trace[$i]['file']))
                $this->backtrace['_backtrace_level_' . $i] = $trace[$i]['file'] .':'. $trace[$i]['line'];
        }
    }

    /**
     * @param $length
     * @param int $offset
     */
    protected function setBacktraceLength($length, $offset=0)
    {
        $this->backtrace_length = $length;

        $this->backtrace_offset = $offset;
    }

}