<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita23ef034e3fb82924a7cfdcfa4433634
{
    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'Jaybizzle\\CrawlerDetect\\' => 24,
        ),
        'D' => 
        array (
            'DNSBL\\' => 6,
        ),
        'A' => 
        array (
            'Acme\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Jaybizzle\\CrawlerDetect\\' => 
        array (
            0 => __DIR__ . '/..' . '/jaybizzle/crawler-detect/src',
        ),
        'DNSBL\\' => 
        array (
            0 => __DIR__ . '/..' . '/jbboehr/dnsbl/src',
        ),
        'Acme\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita23ef034e3fb82924a7cfdcfa4433634::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita23ef034e3fb82924a7cfdcfa4433634::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
