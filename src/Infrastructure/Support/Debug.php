<?php

namespace App\Infrastructure\Support;

class Debug
{
    /**
     * Custom
     */
    public static function log($values = null): void
    {
        $dir  = dirname(__DIR__, 2);
        $path = '/var/log/';
        $file = $dir.$path.'debug.log';
        $rewrite = false;
        $emptyMessage = 'Null or empty content ¯\_(ツ)_/¯';

        if (is_array($values) && isset($values['config'])) {
            ! isset($values['dir']) ? : $dir = $values['dir'];
            ! isset($values['path']) ? : $path = $values['path'];
            ! isset($values['file']) ? : $file = $values['file'];
            ! isset($values['rewrite']) ? : $rewrite = $values['rewrite'];

	        if (! isset($values['values'])) {
                $values = 'with debug config settings, key "values" must be set!';
            }

        }

        $values = ! empty($values) ? $values : $emptyMessage;

        if ($rewrite) {
            file_put_contents($file, '');
        }

        file_put_contents($file, "\n[".date("Y.m.d H:i:s")."]↴\n".print_r($values, true)."\n", FILE_APPEND | LOCK_EX);
    }
}
