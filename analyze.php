<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileName = $_FILES['file']['name'];

    if ($_FILES["file"]["size"] > 2 * 1024 * 1024) {
        exit("Ваш файл слишком большой!");
    }

    if ($_FILES['file']["error"] > 0) {
        exit("Файл загружен с ошибкой " . $_FILES['file']['error']);
    }

    if (mime_content_type($_FILES['file']['tmp_name']) !== "text/plain") {
        exit("Файл не является текстовым документом .txt");
    }

    if (move_uploaded_file($_FILES["file"]["tmp_name"], "files/" . basename($fileName))) {
        echo "Файл " . basename($fileName) . " был успешно загружен.";
    } else {
        exit("К сожалению, у нас не получилось загрузить ваш файл");
    }

    $length = 0;
    $lengthNoSpaces = 0;

    $wordsCount = 0;
    $strokesCount = 0;

    $charsCount = [];

    $avgWordsCount = 0;

    $longestWord = '';
    $shortestStroke = '';

    $file = file("files/" . basename($fileName));

    $strokesCount = count($file);

    foreach ($file as $line) {
        $words = explode(" ", $line);

        $length += strlen($line);
        $lengthNoSpaces += strlen(implode("", $words));
        $wordsCount += count($words);

        foreach ($words as $word) {
            if (strlen($word) >= strlen($longestWord)) {
                $longestWord = $word;
            }
        }

        for ($i = 0; $i < strlen($line); $i++) {
            if (!isset($charsCount[$line[$i]])) {
                $charsCount[$line[$i]] = 0;
            }
            $charsCount[$line[$i]] += 1;
        }

        if (strlen($line) < strlen($shortestStroke) || $shortestStroke === '' || $line != '') {
            $shortestStroke = $line;
        }
    }

    $avgWordsCount = $wordsCount / $strokesCount;

    print_r("<br>Общее количество символов (включая пробелы и знаки препинания): " . $length .
    "<br>Общее количество символов (без пробелов): " . $lengthNoSpaces .
    "<br>Общее количество слов: " . $wordsCount .
    "<br>Общее количество строк: " . $strokesCount .
    "<br>Среднее количество слов в строке: " . $avgWordsCount .
    "<br>Самое длинное слово в тексте: '" . $longestWord .
    "'<br>Самая короткая строка в тексте: '" . $shortestStroke .
    "'<br>Частота использования каждого символа:<br>");

    arsort($charsCount);
    foreach ($charsCount as $char => $count) {
        echo "'$char' (код: " . (ord($char)) . ") : $count<br>";
    }
}