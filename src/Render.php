<?php


class Render
{
    public function execute($file, $data)
    {
        $base = file_get_contents('../views/base.html');
        $content = file_get_contents('../views/'.$file);

        foreach ($data as $key => $value) {
            $content = preg_replace('/{{'.$key.'}}/',$value,$content);
        }

        $base = preg_replace('/{%BASE%}/',$content,$base);
        return $base;
    }
}