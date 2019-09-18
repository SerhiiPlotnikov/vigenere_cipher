<?php

class BabbageAttack
{
//    private const ALPHABET = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    private const ALPHABET = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    private const MOST_COMMON_SYMBOL = 4;


    public function attack(string $text): string
    {
        $keyLength = $this->getKeyLength($text);
        $partsOfText = $this->devideTextByKey($keyLength, $text);
        $shifts = $this->getShiftForText($partsOfText);
        $this->findKey($shifts);
        $this->decodeText($shifts, $text, $keyLength);
        return '';
    }

    private function findKey(array $shifts)
    {
        $key = '';
        foreach ($shifts as $shift) {
            $key .= self::ALPHABET[$shift];
        }
        var_dump($key);
    }

    private function getKeyLength(string $text): int
    {
        $generalIS = [];
        for ($i = 2; $i < 15; $i++) {
            //получаем строку с каждым 2,3,4,5.. символом
            $newText = '';
            for ($j = 0, $k = 0; $j < mb_strlen($text); $j++, $k++) {
                if (strpos(self::ALPHABET, $text{$j}) === false) {
                    $k--;
                    continue;
                }
                if ($k % $i == 0)
                    $newText .= $text{$j};
            }
            //кол-во символов в строке
            $countSymbols = strlen($newText);
            //посчитаем кол-во каждой букві в єтой строке
            $symbols = [];
            for ($j = 0; $j < strlen($newText); $j++) {
                if (!array_key_exists($newText{$j}, $symbols)) {
                    $symbols[$newText{$j}] = 0;
                }
                $symbols[$newText{$j}] += 1;
            }
            //расчитаем ис для каждой букві
            $is = [];
            foreach ($symbols as $symbol => $count) {
                $is[$symbol] = $count * ($count - 1) / ($countSymbols * ($countSymbols - 1));
            }
            //общий индекс совместимости
            $sum = array_sum($is);
            $generalIS[$i] = $sum;
        }
        var_dump($generalIS);
        $max = array_search(max($generalIS), $generalIS);
        var_dump($max);
        return $max;
    }

    private function devideTextByKey(int $keyLength, string $text): array
    {
        $partsOfText = [];
        for ($i = 0; $i < $keyLength; $i++) {
            $part = '';
            for ($j = 0, $k = 0; $j < strlen($text); $j++, $k++) {
                if (strpos(self::ALPHABET, $text{$j}) === false and
                    $k % $keyLength == 0) {
                    $k--;
                    $part .= $text{$j};
                    continue;
                } elseif (strpos(self::ALPHABET, $text{$j}) === false) {
                    $k--;
                    continue;
                }
                if ($k % $keyLength == 0) {
                    $part .= $text{$j};
                }
            }
            $partsOfText[] = $part;
            //вырежем первый символ
            $text = substr_replace($text, '', 0, 1);
        }
        var_dump($partsOfText);
        return $partsOfText;
    }

    //находим сдвиг для каждой части
    private function getShiftForText(array $partsOfText): array
    {
        $shifts = [];
        for ($i = 0; $i < count($partsOfText); $i++) {
            //находим наиболее встречающийся символ в часте текста
            $symbols = [];
            for ($j = 0; $j < strlen($partsOfText[$i]); $j++) {
                if (strpos(self::ALPHABET, $partsOfText[$i]{$j}) === false) {
                    continue;
                }
                if (!array_key_exists($partsOfText[$i]{$j}, $symbols)) {
                    $symbols[$partsOfText[$i]{$j}] = 0;
                }
                $symbols[$partsOfText[$i]{$j}]++;
            }
            var_dump($symbols);
            $symbol = array_search(max($symbols), $symbols);
            var_dump($symbol);
            //Находим сдвиг от найденного символа до наиболее встречающегося
            var_dump(strpos(self::ALPHABET, $symbol));
            $posCurrentSymbol = strpos(self::ALPHABET, $symbol);
            $shifts[] = $posCurrentSymbol - self::MOST_COMMON_SYMBOL;
        }
        var_dump($shifts);
        return $shifts;
    }

    private function decodeText(array $shifts, string $text, int $keyLength): void
    {
        $decodedText = '';
        for ($i = 0,$k=0; $i < strlen($text); $i++,$k++) {
            if (strpos(self::ALPHABET, $text{$i}) === false) {
                $decodedText .= $text{$i};
                $k--;
                continue;
            }
            //получаем смещение для каждого символа
            $shift = $shifts[$k % $keyLength];
            //находим истинную букву

            //находим позицию текущей буквы
            $posCurrentSymbol = strpos(self::ALPHABET, $text{$i});
            var_dump($posCurrentSymbol);
            //истинній символ
            $truePosition = $posCurrentSymbol - $shift;
            $symbol = self::ALPHABET[$truePosition];
            $decodedText .= $symbol;
            if ($i == 200) break;
        }
        echo '<hr>';
        echo $decodedText;
    }
//    private function getKeyLength(string $text): void
//    {
//        $countArray = [];
//        $tempText = $text;
//        //Вірезаем все, что не в алфавите
////        $text = preg_replace('%[^A-Za-z]+%', '', $text);
//        $shuffle = 1;
//        //делаем свдиг
//        for ($i = 0; $i < 40; $i++) {
//
//            if ($i == 0 || strpos(self::ALPHABET, $text{$i}) === false) {
//                //не ведем подсчет
//                $removedPart = $tempText{0};
//                $tempText = substr_replace($tempText, '', 0, 1);
//                $tempText .= $removedPart;
//            continue;
//            }
//            //вірезаем кусок
//            $removedPart = $tempText{0};
//            $tempText = substr_replace($tempText, '', 0, 1);
//            $tempText .= $removedPart;
//            $count = 0;
//            $shuffle++;
//            for ($j = 0; $j < strlen($text); $j++) {
//                if ($text{$j} == $tempText{$j}) {
//                    $count++;
//                }
//            }
//            $countArray[$shuffle] = $count / strlen($text);
//        }
//        var_dump($countArray);
//    }
}

