<?php

class Queue
{
    private $dir;

    public function __construct($dir)
    {
        if (!is_writable($dir)) {
            throw new Exception('Jobs directory must be writable.');
        }

        $this->dir = rtrim($dir, '/');
    }

    public function put($name, $args = [])
    {
        $file = sprintf('%s/%s.json', $this->dir, uniqid());
        $data = [
            'name' => $name,
            'file' => $file,
            'args' => $args,
            'created_at' => time(),
        ];
        return file_put_contents($file, json_encode($data));
    }

    public function pull()
    {
        for (;;) {
            $files = glob($this->dir . '/*.json');
            if (!empty($files)) {
                uasort($files, function ($a, $b) {
                    return filemtime($a) > filemtime($b);
                });

                $file = array_shift($files);
                $content = file_get_contents($file);
                unlink($file);
                return json_decode($content);
            }
            usleep(500000);
        }
    }

    public function remove($job)
    {
        unlink($job->file);
    }

}
