<?php


/**
 * Class Tools
 */
class Tools {


    /**
     * @param $dir
     * @return array
     */
    public static function fetchDirs($dir) {

        $dirs = [];

        $c_dir = scandir($dir);
        foreach ($c_dir as $key => $value) {

            if ( ! in_array($value, [".", ".."])) {

                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $dirs[] = $dir . DIRECTORY_SEPARATOR . $value;
                }
            }
        }

        if ( ! empty($dirs)) {
            rsort($dirs, SORT_NATURAL | SORT_FLAG_CASE);
        }

        return $dirs;
    }


    /**
     * @param $dir
     */
    public static function removeDir($dir) {

        $it    = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($dir);
    }
}