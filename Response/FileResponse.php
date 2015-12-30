<?php

namespace Wucdbm\Bundle\WucdbmBundle\Response;

use Symfony\Component\HttpFoundation\Response;

class FileResponse extends Response {

    private $fileName;

    public function __construct($fileName, $status = 200, $headers = array()) {
        parent::__construct('', $status, $headers);
        $this->fileName = $fileName;
    }

    public function sendContent() {
        $file = fopen($this->fileName, 'rb');
        $out = fopen('php://output', 'wb');
        stream_copy_to_stream($file, $out);
        fclose($out);
        fclose($file);

        return $this;
    }
}