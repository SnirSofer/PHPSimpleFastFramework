<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitac001b3ac409196649d20903aa1724a8
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Medoo\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Medoo\\' => 
        array (
            0 => __DIR__ . '/..' . '/catfan/medoo/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitac001b3ac409196649d20903aa1724a8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitac001b3ac409196649d20903aa1724a8::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
