<?php

declare(strict_types=1);

namespace App\Service;

class TextCleaner
{
    public function cleanText(string $text): string
    {
        // Удалить пробелы между последовательными знаками препинания
        $text = preg_replace('/([.!?])\s*([.!?])/u', '$1$2', $text);

        // Удалить пробел перед знаками препинания
        $text = preg_replace('/\s+([.,!?;:])/u', '$1', $text);

        // Добавить один пробел после знака препинания (если нет)
        $text = preg_replace('/([.,!?;:])(?!\s|$)/u', '$1 ', $text);

        // Удалить лишние пробелы между словами
        $text = preg_replace('/ +/', ' ', $text);

        // Удалить пробелы в начале и конце текста
        $text = trim($text);

        // Сделать первую букву каждого предложения заглавной
        $final_text = '';
        $capitalize = true;
        $length = mb_strlen($text, 'UTF-8');
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1, 'UTF-8');
            if (preg_match('/\p{L}/u', $char)) {
                if ($capitalize) {
                    $final_text .= mb_strtoupper($char, 'UTF-8');
                    $capitalize = false;
                } else {
                    $final_text .= $char;
                }
            } else {
                $final_text .= $char;
                if ($char == '.' || $char == '!' || $char == '?') {
                    $capitalize = true;
                }
            }
        }

        return $final_text;
    }
}
