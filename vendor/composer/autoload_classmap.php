<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Cache\\Adapter\\Common\\AbstractCachePool' => $vendorDir . '/cache/adapter-common/AbstractCachePool.php',
    'Cache\\Adapter\\Common\\CacheItem' => $vendorDir . '/cache/adapter-common/CacheItem.php',
    'Cache\\Adapter\\Common\\Exception\\CacheException' => $vendorDir . '/cache/adapter-common/Exception/CacheException.php',
    'Cache\\Adapter\\Common\\Exception\\CachePoolException' => $vendorDir . '/cache/adapter-common/Exception/CachePoolException.php',
    'Cache\\Adapter\\Common\\Exception\\InvalidArgumentException' => $vendorDir . '/cache/adapter-common/Exception/InvalidArgumentException.php',
    'Cache\\Adapter\\Common\\HasExpirationDateInterface' => $vendorDir . '/cache/adapter-common/HasExpirationDateInterface.php',
    'Cache\\Adapter\\Common\\Tests\\CacheItemTest' => $vendorDir . '/cache/adapter-common/Tests/CacheItemTest.php',
    'Cache\\Adapter\\Filesystem\\FilesystemCachePool' => $vendorDir . '/cache/filesystem-adapter/FilesystemCachePool.php',
    'Cache\\Taggable\\TaggableItemInterface' => $vendorDir . '/cache/taggable-cache/TaggableItemInterface.php',
    'Cache\\Taggable\\TaggablePSR6ItemAdapter' => $vendorDir . '/cache/taggable-cache/TaggablePSR6ItemAdapter.php',
    'Cache\\Taggable\\TaggablePSR6PoolAdapter' => $vendorDir . '/cache/taggable-cache/TaggablePSR6PoolAdapter.php',
    'Cache\\Taggable\\TaggablePoolInterface' => $vendorDir . '/cache/taggable-cache/TaggablePoolInterface.php',
    'Cache\\Taggable\\TaggablePoolTrait' => $vendorDir . '/cache/taggable-cache/TaggablePoolTrait.php',
    'Curl\\Curl' => $vendorDir . '/php-mod/curl/src/Curl/Curl.php',
    'GitStat\\GitHub' => $baseDir . '/src/GitHub.php',
    'League\\Flysystem\\AdapterInterface' => $vendorDir . '/league/flysystem/src/AdapterInterface.php',
    'League\\Flysystem\\Adapter\\AbstractAdapter' => $vendorDir . '/league/flysystem/src/Adapter/AbstractAdapter.php',
    'League\\Flysystem\\Adapter\\AbstractFtpAdapter' => $vendorDir . '/league/flysystem/src/Adapter/AbstractFtpAdapter.php',
    'League\\Flysystem\\Adapter\\Ftp' => $vendorDir . '/league/flysystem/src/Adapter/Ftp.php',
    'League\\Flysystem\\Adapter\\Ftpd' => $vendorDir . '/league/flysystem/src/Adapter/Ftpd.php',
    'League\\Flysystem\\Adapter\\Local' => $vendorDir . '/league/flysystem/src/Adapter/Local.php',
    'League\\Flysystem\\Adapter\\NullAdapter' => $vendorDir . '/league/flysystem/src/Adapter/NullAdapter.php',
    'League\\Flysystem\\Adapter\\Polyfill\\NotSupportingVisibilityTrait' => $vendorDir . '/league/flysystem/src/Adapter/Polyfill/NotSupportingVisibilityTrait.php',
    'League\\Flysystem\\Adapter\\Polyfill\\StreamedCopyTrait' => $vendorDir . '/league/flysystem/src/Adapter/Polyfill/StreamedCopyTrait.php',
    'League\\Flysystem\\Adapter\\Polyfill\\StreamedReadingTrait' => $vendorDir . '/league/flysystem/src/Adapter/Polyfill/StreamedReadingTrait.php',
    'League\\Flysystem\\Adapter\\Polyfill\\StreamedTrait' => $vendorDir . '/league/flysystem/src/Adapter/Polyfill/StreamedTrait.php',
    'League\\Flysystem\\Adapter\\Polyfill\\StreamedWritingTrait' => $vendorDir . '/league/flysystem/src/Adapter/Polyfill/StreamedWritingTrait.php',
    'League\\Flysystem\\Adapter\\SynologyFtp' => $vendorDir . '/league/flysystem/src/Adapter/SynologyFtp.php',
    'League\\Flysystem\\Config' => $vendorDir . '/league/flysystem/src/Config.php',
    'League\\Flysystem\\ConfigAwareTrait' => $vendorDir . '/league/flysystem/src/ConfigAwareTrait.php',
    'League\\Flysystem\\Directory' => $vendorDir . '/league/flysystem/src/Directory.php',
    'League\\Flysystem\\Exception' => $vendorDir . '/league/flysystem/src/Exception.php',
    'League\\Flysystem\\File' => $vendorDir . '/league/flysystem/src/File.php',
    'League\\Flysystem\\FileExistsException' => $vendorDir . '/league/flysystem/src/FileExistsException.php',
    'League\\Flysystem\\FileNotFoundException' => $vendorDir . '/league/flysystem/src/FileNotFoundException.php',
    'League\\Flysystem\\Filesystem' => $vendorDir . '/league/flysystem/src/Filesystem.php',
    'League\\Flysystem\\FilesystemInterface' => $vendorDir . '/league/flysystem/src/FilesystemInterface.php',
    'League\\Flysystem\\Handler' => $vendorDir . '/league/flysystem/src/Handler.php',
    'League\\Flysystem\\MountManager' => $vendorDir . '/league/flysystem/src/MountManager.php',
    'League\\Flysystem\\NotSupportedException' => $vendorDir . '/league/flysystem/src/NotSupportedException.php',
    'League\\Flysystem\\PluginInterface' => $vendorDir . '/league/flysystem/src/PluginInterface.php',
    'League\\Flysystem\\Plugin\\AbstractPlugin' => $vendorDir . '/league/flysystem/src/Plugin/AbstractPlugin.php',
    'League\\Flysystem\\Plugin\\EmptyDir' => $vendorDir . '/league/flysystem/src/Plugin/EmptyDir.php',
    'League\\Flysystem\\Plugin\\ForcedCopy' => $vendorDir . '/league/flysystem/src/Plugin/ForcedCopy.php',
    'League\\Flysystem\\Plugin\\ForcedRename' => $vendorDir . '/league/flysystem/src/Plugin/ForcedRename.php',
    'League\\Flysystem\\Plugin\\GetWithMetadata' => $vendorDir . '/league/flysystem/src/Plugin/GetWithMetadata.php',
    'League\\Flysystem\\Plugin\\ListFiles' => $vendorDir . '/league/flysystem/src/Plugin/ListFiles.php',
    'League\\Flysystem\\Plugin\\ListPaths' => $vendorDir . '/league/flysystem/src/Plugin/ListPaths.php',
    'League\\Flysystem\\Plugin\\ListWith' => $vendorDir . '/league/flysystem/src/Plugin/ListWith.php',
    'League\\Flysystem\\Plugin\\PluggableTrait' => $vendorDir . '/league/flysystem/src/Plugin/PluggableTrait.php',
    'League\\Flysystem\\Plugin\\PluginNotFoundException' => $vendorDir . '/league/flysystem/src/Plugin/PluginNotFoundException.php',
    'League\\Flysystem\\ReadInterface' => $vendorDir . '/league/flysystem/src/ReadInterface.php',
    'League\\Flysystem\\RootViolationException' => $vendorDir . '/league/flysystem/src/RootViolationException.php',
    'League\\Flysystem\\UnreadableFileException' => $vendorDir . '/league/flysystem/src/UnreadableFileException.php',
    'League\\Flysystem\\Util' => $vendorDir . '/league/flysystem/src/Util.php',
    'League\\Flysystem\\Util\\ContentListingFormatter' => $vendorDir . '/league/flysystem/src/Util/ContentListingFormatter.php',
    'League\\Flysystem\\Util\\MimeType' => $vendorDir . '/league/flysystem/src/Util/MimeType.php',
    'League\\Flysystem\\Util\\StreamHasher' => $vendorDir . '/league/flysystem/src/Util/StreamHasher.php',
    'Psr\\Cache\\CacheException' => $vendorDir . '/psr/cache/src/CacheException.php',
    'Psr\\Cache\\CacheItemInterface' => $vendorDir . '/psr/cache/src/CacheItemInterface.php',
    'Psr\\Cache\\CacheItemPoolInterface' => $vendorDir . '/psr/cache/src/CacheItemPoolInterface.php',
    'Psr\\Cache\\InvalidArgumentException' => $vendorDir . '/psr/cache/src/InvalidArgumentException.php',
    'Psr\\Log\\AbstractLogger' => $vendorDir . '/psr/log/Psr/Log/AbstractLogger.php',
    'Psr\\Log\\InvalidArgumentException' => $vendorDir . '/psr/log/Psr/Log/InvalidArgumentException.php',
    'Psr\\Log\\LogLevel' => $vendorDir . '/psr/log/Psr/Log/LogLevel.php',
    'Psr\\Log\\LoggerAwareInterface' => $vendorDir . '/psr/log/Psr/Log/LoggerAwareInterface.php',
    'Psr\\Log\\LoggerAwareTrait' => $vendorDir . '/psr/log/Psr/Log/LoggerAwareTrait.php',
    'Psr\\Log\\LoggerInterface' => $vendorDir . '/psr/log/Psr/Log/LoggerInterface.php',
    'Psr\\Log\\LoggerTrait' => $vendorDir . '/psr/log/Psr/Log/LoggerTrait.php',
    'Psr\\Log\\NullLogger' => $vendorDir . '/psr/log/Psr/Log/NullLogger.php',
    'Psr\\Log\\Test\\DummyTest' => $vendorDir . '/psr/log/Psr/Log/Test/LoggerInterfaceTest.php',
    'Psr\\Log\\Test\\LoggerInterfaceTest' => $vendorDir . '/psr/log/Psr/Log/Test/LoggerInterfaceTest.php',
);
