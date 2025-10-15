<html>
<body>
    <h2>Convertitore di Temperatura</h2>

    <form method="post" action="">
        <label>Temperatura:</label>
        <input type="text" name="temp" required>

        <br><br>

        <label>Da:</label>
        <select name="from">
            <option value="C">Celsius</option>
            <option value="F">Fahrenheit</option>
            <option value="K">Kelvin</option>
        </select>

        <label>A:</label>
        <select name="to">
            <option value="C">Celsius</option>
            <option value="F">Fahrenheit</option>
            <option value="K">Kelvin</option>
        </select>

        <br><br>
        <button type="submit" name="converti">Converti</button>
    </form>

    <br><br>

    <?php
    $conversioni = [
        'C' => [
            'F' => fn($t) => ($t * 9/5) + 32,
            'K' => fn($t) => $t + 273.15
        ],
        'F' => [
            'C' => fn($t) => ($t - 32) * 5/9,
            'K' => fn($t) => ($t - 32) * 5/9 + 273.15
        ],
        'K' => [
            'C' => fn($t) => $t - 273.15,
            'F' => fn($t) => ($t - 273.15) * 9/5 + 32
        ]
    ];
    
    $from = $_POST['from'];
    $to = $_POST['to'];

    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'IP sconosciuto';
    $porta = isset($_SERVER['REMOTE_PORT']) ? $_SERVER['REMOTE_PORT'] : 'Porta sconosciuta';

    
    $dataOra = date('Y-m-d H:i:s');

    if (!isset($_POST['converti'])) {
        echo "";
        return;
    }

    
    $temp = str_replace(',', '.', $_POST['temp']);

    if (!is_numeric($temp)) {
        echo "Inserisci un numero valido.";
        return;
    } elseif ($from === $to) {
        echo "nessuna conversione";
        return;
    } elseif (!isset($conversioni[$from][$to])) {
        echo "Combinazione non valida.";
        return;
    } else {
        $risultato = $conversioni[$from][$to]($temp);
        echo "Risultato: {$_POST['temp']}° $from = $risultato° $to";
    }


    $log = "Data e ora $dataOra da IP: $ip, : $porta . Conversione da $from a $to : \n";

    
    $handler = fopen('data/log.txt', 'a');
    fwrite($handler, $log);
    fclose($handler);

    ?>
</body>
</html>