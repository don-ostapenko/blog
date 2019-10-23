<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5446f4a9c8b7012696ef7761570d0c69
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MyProject\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MyProject\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/MyProject',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Parsedown' => 
            array (
                0 => __DIR__ . '/..' . '/erusev/parsedown',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5446f4a9c8b7012696ef7761570d0c69::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5446f4a9c8b7012696ef7761570d0c69::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit5446f4a9c8b7012696ef7761570d0c69::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}