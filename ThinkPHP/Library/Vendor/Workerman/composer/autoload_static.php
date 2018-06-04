<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3789c78ca3f64e9d4611e161dad7faf0
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Workerman\\' => 10,
        ),
        'P' => 
        array (
            'PHPSocketIO\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Workerman\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/workerman-for-win',
        ),
        'PHPSocketIO\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/phpsocket.io-for-win/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3789c78ca3f64e9d4611e161dad7faf0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3789c78ca3f64e9d4611e161dad7faf0::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
