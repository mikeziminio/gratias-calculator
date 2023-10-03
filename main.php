<?php

require __DIR__ . '/vendor/autoload.php';

function calculateGratiasPrice($values) {

    $spreadsheetId = '1jvkYoL6mcurZzYNS4dy-68GI0hJ9k9KVZcGasienaBA';

    $client = new Google_Client();
    $client->setAuthConfig('gratias-65a9dfe08721.json'); // Путь к вашему JSON-ключу сервисного аккаунта
    $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
    $client->setSubject('gratias@gratias.iam.gserviceaccount.com');
    $service = new Google_Service_Sheets($client);

    // Записываем 8 ячеек ввода

    $range = 'io!B1:B8';

    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values,
    ]);

    $params = [
        'valueInputOption' => 'RAW',
    ];

    $result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);

    if(!$result || $result->getUpdatedCells() !== count($values)) {
        echo "Ошибка обновления google таблицы";
        return false;
    }

    // Получаем результат из одной ячейки

    $range = 'io!B9';

    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();

    if (empty($values)) {
        echo "Значение не найдено.";
        return false;
    } else {
        $readValue = $values[0][0];
        return $readValue;
    }

}
