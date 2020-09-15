<?php

if (!function_exists('mb_ucfirst') && extension_loaded('mbstring')) {
    /**
     * mb_ucfirst - преобразует первый символ в верхний регистр
     * @param string $str - строка
     * @param string $encoding - кодировка, по-умолчанию UTF-8
     * @return string
     */
    function mb_ucfirst($str, $encoding = 'UTF-8')
    {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
            mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }
}

function tempNumber($num) {
    if($num == 0) {
        return '';
    } elseif ($num < 0) {
        return '-';
    } elseif ($num > 0) {
        return '+';
    }
}

$curl_location = curl_init();

curl_setopt_array($curl_location, array(
    CURLOPT_URL => "https://freegeoip.app/json/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "accept: application/json",
        "content-type: application/json"
    ),
));

$response_location = curl_exec($curl_location);
$err_location = curl_error($curl_location);

curl_close($curl_location);

if ($err_location) {

    echo "А где твой IP?";
    exit();

} else {

    $response_location = json_decode($response_location, true);

    $api_weather = '79e13d52cee6925b98939b15fb005209';

    $curl_weather = curl_init();

    curl_setopt_array($curl_weather, array(

        CURLOPT_URL => "https://api.openweathermap.org/data/2.5/weather?lat=" . $response_location['latitude'] . "&lon=" . $response_location['longitude'] . "&lang=" . $response_location['country_code'] . "&units=metric&appid=" . $api_weather,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "x-rapidapi-host: community-open-weather-map.p.rapidapi.com",
            "x-rapidapi-key: SIGN-UP-FOR-KEY"
        ),
    ));

    $response_weather = curl_exec($curl_weather);
    $err_weather = curl_error($curl_weather);

    curl_close($curl_weather);

    if ($err_weather) {

        echo "Что-то совсем погоды никакой...";
        exit();

    } else {

        $response_weather = json_decode($response_weather, true);

        $city = $response_weather['name'];
        $description = mb_ucfirst($response_weather['weather'][0]['description']);
        $temp = tempNumber($response_weather['main']['temp']) . round($response_weather['main']['temp']);
        $temp_like = tempNumber($response_weather['main']['feels_like']) . round($response_weather['main']['feels_like']);
        $pressure = round($response_weather['main']['pressure'] * 0.75);
        $wind = $response_weather['wind']['speed'];
        $humidity = $response_weather['main']['humidity'];
        $icon = 'https://openweathermap.org/img/wn/' . $response_weather['weather'][0]['icon'] . '@2x.png';

        echo '<pre>';
        //var_dump($response_weather);
        echo '</pre>';

    }
}


?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
          integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <style>
        .igor-copy {
            width: 80px;
            opacity: 0.2;
            transition: 0.5s;
        }
        .igor-copy:hover {
            opacity: 1;
        }
    </style>

    <title>Погода</title>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card">
                <div class="card-header text-center">
                    <?= $city ?>
                </div>
                <div class="card-body">

                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <td>
                                <?= $description ?>
                                <h2><?= $temp ?>°</h2>
                                <span class="small ">Ощущается на <?= $temp_like ?>°</span>
                            </td>
                            <td><img src="<?= $icon ?>" class="float-left img-thumbnail" alt="" style="max-width: 90px;"></td>
                        </tr>
                        <tr>
                            <td>Ветер</td>
                            <td><?= $wind ?> м/с</td>
                        </tr>
                        <tr>
                            <td>Влажность</td>
                            <td><?= $humidity ?> %</td>
                        </tr>
                        <tr>
                            <td>Давление</td>
                            <td><?= $pressure ?> мм рт. ст.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <div class="row my-4 justify-content-center">
                <div class="col-auto">
                    <a href="https://igor.vip/" target="_blank"><img src="https://igor.vip/igor-copy.svg" alt="IGOR.VIP" class="igor-copy"></a>
                </div>
            </div>
</div>

</body>
</html>
