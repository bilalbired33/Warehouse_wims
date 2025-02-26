<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Env
{
    public static function changeable()
    {
        return collect(self::load(self::getChangeable()))->transform(function ($i, $k) {
            $v = explode('=', $i);

            return ['key' => $v[0], 'value' => empty($v[1]) || $v[1] == 'null' ? '' : trim($v[1], '"')];
        })->values();
    }

    public static function load($string = null)
    {
        return explode("\n", trim($string ?: self::read()));
    }

    public static function read()
    {
        return file_get_contents(base_path('.env'));
    }

    public static function update($data, $changeable = true)
    {
        if (empty($data) || ! is_array($data) || ! is_file(base_path('.env'))) {
            return false;
        }

        if ($changeable) {
            return self::updateChangeable($data);
        }

        return self::updateData($data);

        return false;
    }

    private static function getChangeable($string = null)
    {
        $str = Str::after($string ?: self::read(), '# changeable start');

        return Str::before($str, '# changeable end');
    }

    private static function putContents($contents)
    {
        file_put_contents(base_path('.env'), $contents, LOCK_EX);

        return true;
    }

    private static function updateChangeable($data)
    {
        $change = [];
        foreach ($data as $key => $val) {
            $change[] = $key . '=' . (Str::contains($val, ' ') ? '"' . $val . '"' : $val);
        }
        $changeable = "\n" . implode("\n", $change) . "\n";
        $contents = str_replace(self::getChangeable(), $changeable, self::read());

        return self::putContents($contents);
    }

    private static function updateData($data)
    {
        $env = self::load();
        foreach ($data as $dKey => $dValue) {
            foreach ($env as $eKey => $eValue) {
                $entry = explode('=', $eValue, 2);
                $env[$eKey] = $entry[0] == $dKey ? $dKey . '=' . (Str::contains($dValue, ' ') ? '"' . $dValue . '"' : $dValue) : $eValue;
            }
        }
        $contents = implode("\n", $env);

        return self::putContents($contents);
    }
}
