<?php
namespace SchamsNet\DocErrorReport\Utility;

/**
 * Filesystem utility class
 */
class FileSystemUtility
{
    /**
     * Copy directory recursively
     *
     * @access public
     * @param string Source directory path
     * @param string Destination directory path
     * @return bool
     */
    public static function copyDirectoryRecursively($source, $destination): bool
    {
        if (is_dir($source)) {
            $dir = opendir($source);
            @mkdir($destination);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($source . '/' . $file)) {
                        self::copyDirectoryRecursively($source . '/' . $file, $destination . '/' . $file);
                    } else {
                        copy($source . '/' . $file, $destination . '/' . $file);
                    }
                }
            }
            closedir($dir);
            return true;
        }
        return false;
    }
}
