<?php

if (PHP_SAPI !== 'cli') {
    throw new Exception('Bad SAPI! Need cli.');
}


require_once 'classes/Tools.php';

$options = getopt('d:c:h', [
    'dir:',
    'count:',
    'help',
]);

if ((isset($options['d']) || isset($options['dir'])) &&
    (isset($options['c']) || isset($options['count'])) &&
    ( ! isset($options['h']) && ! isset($options['help']))
) {
    $option_dir   = isset($options['dir'])      ? realpath($options['dir']) : realpath($options['d']);
    $option_count = isset($options['count'])    ? $options['count']         : $options['c'];


    try {
        if ( ! is_dir($option_dir)) {
            throw new Exception("Incorrect parameter dir: Not found directory '{$option_dir}'");
        }
        if ( ! is_numeric($option_count)) {
            throw new Exception("Incorrect parameter count: Not valid number '{$option_count}'");
        }
        if ($option_count <= 0) {
            throw new Exception("Incorrect parameter count: value must be greater than zero");
        }

        $dirs = Tools::fetchDirs($option_dir);

        $count_dirs  = 1;
        $remove_dirs = 0;
        $missed_dirs = 0;

        if ( ! empty($dirs)) {
            foreach ($dirs as $dir) {

                if ($option_count < $count_dirs++) {
                    Tools::removeDir($dir);
                    echo 'remove: ' . $dir . PHP_EOL;
                    $remove_dirs++;

                } else {
                    echo 'missed: ' . $dir . PHP_EOL;
                    $missed_dirs++;
                }
            }
        }

        echo '-------------------' .PHP_EOL;
        echo 'Directory deleted: ' . $remove_dirs . PHP_EOL;
        echo 'Missing directories: ' . $missed_dirs . PHP_EOL;
        echo 'Done.' . PHP_EOL;

    } catch (Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }

} else {
    echo implode(PHP_EOL, [
        'Backup remover',
        'Usage: php backupremover.php [OPTIONS]',
        'Required arguments:',
        "\t-d\t--dir\t\tInspection directory",
        "\t-c\t--count\t\tCount of valid backups",
        'Optional arguments:',
        "\t-h\t--help\t\tHelp info",
        "Examples of usage:",
        "php backupremover.php -d /var/backups/ -c 5",
    ]) . PHP_EOL;
}