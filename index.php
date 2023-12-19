<?php

// Abre o arquivo de log
$arquivo = fopen("log.txt", "r") or die("Não foi possível abrir o arquivo!");

// Pula a primeira linha
fgets($arquivo);

// Inicializa um array para armazenar os resultados da corrida
$resultadosCorrida = [];

// Lê o arquivo linha por linha
while (!feof($arquivo)) {

    // Obtém a linha atual
    $linha = fgets($arquivo);

    // Separa a linha em partes
    $partes = explode(" ", $linha);

    // Extrai os dados necessários
    $hora = $partes[0];
    $codigoPiloto = $partes[1];
    $numeroVolta = $partes[2];
    $tempoVolta = $partes[3];
    $velocidadeMediaVolta = $partes[4];

    // Parse o nome do piloto
    $nomePiloto = substr($codigoPiloto, 4);
    $codigoPiloto = substr($codigoPiloto, 0, 3);

    // Verifica se o piloto já está nos resultados da corrida
    if (!isset($resultadosCorrida[$codigoPiloto])) {

        // Inicializa os dados do piloto
        $resultadosCorrida[$codigoPiloto] = [
            'codigoPiloto' => $codigoPiloto,
            'nomePiloto' => $nomePiloto,
            'numeroVoltasCompletadas' => 0,
            'tempoTotalCorrida' => 0,
            'notas' => ''
        ];
    }

    // Atualiza os dados do piloto
    $resultadosCorrida[$codigoPiloto]['numeroVoltasCompletadas']++;

    // Calcula o tempo total de corrida
    if ($resultadosCorrida[$codigoPiloto]['numeroVoltasCompletadas'] == 4) {
        $resultadosCorrida[$codigoPiloto]['tempoTotalCorrida'] += floatval($tempoVolta);
    } else {
        $resultadosCorrida[$codigoPiloto]['tempoTotalCorrida'] += floatval($tempoVolta);
    }

    // Se o piloto completou 4 voltas, termine a corrida
    if ($resultadosCorrida[$codigoPiloto]['numeroVoltasCompletadas'] == 4) {
        break;
    }
}

// Fecha o arquivo
fclose($arquivo);

// Imprime os resultados da corrida
foreach ($resultadosCorrida as $piloto) {

    // Adiciona uma posição de chegada
    $piloto['posicaoChegada'] = count($resultadosCorrida) - $piloto['numeroVoltasCompletadas'];

    // Exibe os resultados
    echo "Posição de chegada: " . $piloto['posicaoChegada'] . "<br>";
    echo "Código do piloto: " . $piloto['codigoPiloto'] . "<br>";
    echo "Nome do piloto: " . $piloto['nomePiloto'] . "<br>";
    echo "Número de voltas completadas: " . $piloto['numeroVoltasCompletadas'] . "<br>";
    echo "Tempo total de corrida: " . gmdate("H:i:s", $piloto['tempoTotalCorrida']) . "<br>";
    echo "<br><br>";
}

?>
