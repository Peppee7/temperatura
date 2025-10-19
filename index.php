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
    $from = $_POST['from'];
    $to = $_POST['to'];

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
        $flag = false;
        return;
    } elseif ($from === $to) {
        echo "Nessuna conversione";
        $flag = false;
        return;
    } elseif (!isset($conversioni[$from][$to])) {
        echo "Combinazione non valida.";
        $flag = false;
        return;
    } else {
        $risultato = $conversioni[$from][$to]($temp);
        echo "Risultato: $temp ° $from = $risultato ° $to";
        $flag = true;
    }

    if ($flag == true) {
        $log = "Data e ora $dataOra da IP: $ip, : $porta . Conversione: $temp $from a $risultato $to \n";
    } elseif ($flag == false) {
        $log = "ERRORE. Data e ora $dataOra da IP: $ip, : $porta . Conversione non valida \n";
    }
    
    $handler = fopen('data/log.txt', 'a');
    fwrite($handler, $log);
    fclose($handler);

    ?>
</body>
</html>