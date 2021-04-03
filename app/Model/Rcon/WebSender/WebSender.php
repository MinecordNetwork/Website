<?php

declare(strict_types=1);

namespace App\Model\Rcon\WebSender;

class WebSender
{
    /** @var resource */
    private $socket;

    public function __construct(
        private string $host,
        private int $port,
        private string $password,
        private int $timeout = 3
    ) {
        $this->host = gethostbyname($host);
    }

    public function __destruct()
    {
        if ($this->socket) {
            $this->disconnect();
        }
    }

    public function connect(): bool
    {
        $this->socket = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
        if (!$this->socket) {
            return false;
        }
        
        @$this->writeRawByte(1);
        @$this->writeString(hash("SHA512", $this->readRawInt().$this->password));
        
        return $this->readRawInt() === 1;
    }

    public function sendCommand($command): bool
    {
        $this->writeRawByte(2);
        $this->writeString(base64_encode($command));
        
        return $this->readRawInt() === 1;
    }

    public function sendMessage($message): bool
    {
        $this->writeRawByte(4);
        $this->writeString(base64_encode($message));
        
        return $this->readRawInt() === 1;
    }

    public function disconnect(): bool
    {
        if (!$this->socket) {
            return false;
        }
        
        return $this->writeRawByte(3);
    }

    private function writeRawInt($i): bool
    {
        return (bool) @fwrite($this->socket, pack("N", $i), 4);
    }

    private function writeRawByte($b): bool
    {
        return (bool) @fwrite($this->socket, strrev(pack("C", $b)));
    }

    private function writeString($string): void
    {
        $array = str_split($string);
        $this->writeRawInt(count($array));
        
        foreach ($array as $cur){
            $v = ord($cur);
            $this->writeRawByte((0xff & ($v >> 8)));
            $this->writeRawByte((0xff & $v));
        }
    }

    private function readRawInt()
    {
        $a = $this->readRawByte();
        $b = $this->readRawByte();
        $c = $this->readRawByte();
        $d = $this->readRawByte();
        $i = ((($a & 0xff) << 24) | (($b & 0xff) << 16) | (($c & 0xff) << 8) | ($d & 0xff));
        if ($i > 2147483648) {
            $i -= 4294967296;
        }

        return $i;
    }

    private function readRawByte()
    {
        $up = unpack("Ci", fread($this->socket, 1));
        $b = $up["i"];
        if ($b > 127) {
            $b -= 256;
        }

        return $b;
    }
}
