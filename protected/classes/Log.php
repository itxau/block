<?php
class Log {
    // Singleton instance
    protected static $instance = NULL;

    // Path to save log files
    protected $_log_path=APP_ROOT.'logs'.DIRECTORY_SEPARATOR;

    // File permissions
    protected $_file_permissions = 0644;

    // Format of timestamp for log files
    protected $_date_fmt = 'Y-m-d H:i:s';

    protected function __construct() {
     
        if (!file_exists($this->_log_path)) {
            mkdir($this->_log_path, 0755, TRUE);
        }

    }

    public static function __callStatic($name, $arguments) {
        return static::writeLog($name, $arguments);
    }

    private static function getInstance() {
        if (static::$instance === NULL) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private static function writeLog($name, $messages) {
        $result = array();

        for ($i = 0, $size = count($messages); $i < $size; $i += 1) {
            $message = $messages[$i];

            if (is_array($message)) {
                $message = json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }

            if (is_string($message) || is_numeric($message)) {
                $result[] = $message;
            }
        }

        $instance = static::getInstance();
        $instance->write_log($name, implode(' ', $result) . "\n");
    }

    // Private clone method to prevent cloning of the instance of the *Singleton* instance.
    private function __clone() {}

    // Private unserialize method to prevent unserializing of the *Singleton* instance.
    private function __wakeup() {}

    /**
     * Write Log File
     * @param  string $name The error name
     * @param  string $msg   The error message
     * @return bool
     */
    private function write_log($name, $msg) {

        $filepath = $this->_log_path.$name.'_'.date('Ymd').'.log';

        $message = '';

        if (!file_exists($filepath)) {
            $newfile = TRUE;
        }

        if (!$fp = @fopen($filepath, 'ab')) {
            return FALSE;
        }

        flock($fp, LOCK_EX);

        // Instantiating DateTime with microseconds appended to initial date
        // is needed for proper support of this format
        if (strpos($this->_date_fmt, 'u') !== FALSE) {
            $microtime_full = microtime(TRUE);
            $microtime_short = sprintf('%06d', ($microtime_full - floor($microtime_full)) * 1000000);
            $date = new DateTime(date('Y-m-d H:i:s.' . $microtime_short, $microtime_full));
            $date = $date->format($this->_date_fmt);

        } else {
            $date = date($this->_date_fmt);
        }

        $message .= $this->_format_line($date, $msg);

        for ($written = 0, $length = strlen($message); $written < $length; $written += $result) {
            if (($result = fwrite($fp, substr($message, $written))) === FALSE) {
                break;
            }
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        if (isset($newfile) && $newfile === TRUE) {
            chmod($filepath, $this->_file_permissions);
        }

        return is_int($result);
    }

    /**
     * Format the log line.
     * @param  string $date    Formatted date string
     * @param  string $message The log message
     * @return string          Formatted log line with a new line character '\n' at the end
     */
    protected function _format_line($date, $message) {
        return "[{$date}]:\n{$message}\n";
    }
}
