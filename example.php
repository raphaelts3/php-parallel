<?php

require_once('WorkerManager.php');
require_once('Worker.php');

class SleepThenEcho implements Worker
{
    private $time;
    private $start;


    public function __construct($time)
    {
        $this->start = microtime(true);
        $this->time = intval($time);
    }


    public function getCommand()
    {
        return 'php test.php';
    }

    public function getTime()
    {
        return $this->time;
    }


    public function done($stdout, $stderr)
    {
        echo 'done ';
        echo str_replace(array("\r\n", "\n", "\r"), ' ', var_export(array(
            'command' => $this->getCommand(),
            'stdout'  => $stdout,
            'stderr'  => $stderr,
            'time'    => (microtime(true) - $this->start) / 1000.0
        ), true)), PHP_EOL;
    }


    public function fail($stdout, $stderr, $status)
    {
        echo 'fail ';
        echo str_replace(array("\r\n", "\n", "\r"), ' ', var_export(array(
            'command' => $this->getCommand(),
            'stdout'  => $stdout,
            'stderr'  => $stderr,
            'status'  => $status,
        ), true)), PHP_EOL;
    }
}

$start = microtime(true);
$manager = new WorkerManager();

for ($i = -1; $i < 10; $i++) {
    $manager->attach(new SleepThenEcho($i));
}

echo (microtime(true) - $start) / 1000.0;

while (0 < count($manager)) {
    $manager->listen();
}
echo (microtime(true) - $start) / 1000.0;