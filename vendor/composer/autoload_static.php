<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4c3a172c8c05c3ca2298f7eb3159cd10
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4c3a172c8c05c3ca2298f7eb3159cd10::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4c3a172c8c05c3ca2298f7eb3159cd10::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4c3a172c8c05c3ca2298f7eb3159cd10::$classMap;

        }, null, ClassLoader::class);
    }
}
