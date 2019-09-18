<?php

class Vigenere
{
    private const KEY = 'PLOTNIKOV';
    private $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

    private  $table = [];

    public function __construct()
    {
        $this->getTable();
    }

    private function getTable(): void
    {
        $size = strlen($this->alphabet);
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0, $k = $i; $j < $size; $j++, $k++) {
                $this->table[$i][$j] = $k < $size ? $this->alphabet{$k} : $this->alphabet{$k - $size};
            }
        }
    }

    //repeat key as out input text
    private function makeRepeatedKey(string $text): string
    {
        $repeatedKey = '';
        $k = 0;
        for ($i = 0; $i < strlen($text); $i++) {
            if (strpos($this->alphabet, $text{$i}) === false) {
                $repeatedKey .= $text{$i};
                continue;
            }
            $pos = $k % strlen(self::KEY);
            $repeatedKey .= self::KEY[$pos];
            $k++;
        }
        return $repeatedKey;
    }

    public function encode(string $text): string
    {
        $repeatedKey = $this->makeRepeatedKey($text);
        $encodedText = '';
        for ($i = 0; $i < strlen($text); $i++) {
            if (strpos($this->alphabet, $text{$i}) === false) {
                $encodedText .= $text{$i};
                continue;
            }
            $columnPos = strpos($this->alphabet, $text{$i});
            $rowPos = strpos($this->alphabet, $repeatedKey{$i});
            $encodedText .= $this->table[$columnPos][$rowPos];
        }
        return $encodedText;
    }

    public function decode(string $encodedString): string
    {
        $decodedText = '';
        $repeatedKey = $this->makeRepeatedKey($encodedString);
        for ($i = 0; $i < strlen($encodedString); $i++) {
            if (strpos($this->alphabet, $encodedString{$i}) === false) {
                $decodedText .= $encodedString{$i};
                continue;
            }
            $rowPos = strpos($this->alphabet, $repeatedKey{$i});
            //находим индекс в строке rowPos, соттветствующий  символу с зашифрованной строки
            $colPos = array_search($encodedString{$i}, $this->table[$rowPos]);
            $decodedText .= $this->alphabet{$colPos};
        }
        return $decodedText;
    }
}