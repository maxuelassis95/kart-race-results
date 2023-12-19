<?php

// Abre o arquivo de log
$arquivo = fopen("log.txt", "r") or die("Não foi possível abrir o arquivo!");

// Pula a primeira linha
fgets($arquivo);

// Inicializa um array para armazenar os resultados da corrida
$resultadosCorrida = [];

// Inicializa um array para armazenar o número total de voltas de cada piloto
$totalVoltas = [];

// Lê o arquivo linha por linha
while (!feof($arquivo)) {
    // Obtém a linha atual
    $linha = fgets($arquivo);

    // Separa a linha em partes
    $partes = explode(" ", $linha);

    // Extrai os dados necessários
    $codigoPiloto = $partes[1];
    $numeroVolta = $partes[2];
    $tempoVolta = $partes[3];

    // Verifica se o piloto já está nos resultados da corrida
    if (!isset($resultadosCorrida[$codigoPiloto])) {
        // Encotra o nome do piloto corretamente usando uma expressão regular
        preg_match('/\d+ – (.+?)\s/', $linha, $matches);
        $nomePiloto = $matches[1];

        // Obtém apenas o código do piloto
        $codigoPiloto = substr($codigoPiloto, 0, 3);

        // Inicializa os dados do piloto
        $resultadosCorrida[$codigoPiloto] = [
            'codigoPiloto' => $codigoPiloto,
            'nomePiloto' => $nomePiloto,
            'numeroVoltasCompletadas' => 0,
            'tempoTotalCorrida' => 0,
        ];

        // Inicializa o número total de voltas do piloto
        $totalVoltas[$codigoPiloto] = 0;
    }

    // Atualiza os dados do piloto
    $resultadosCorrida[$codigoPiloto]['numeroVoltasCompletadas']++;

    // Atualiza o número total de voltas do piloto
    $totalVoltas[$codigoPiloto] = max($totalVoltas[$codigoPiloto], $numeroVolta);

    // Calcula o tempo total de corrida
    $resultadosCorrida[$codigoPiloto]['tempoTotalCorrida'] += floatval($tempoVolta);

    // Se o piloto completou todas as voltas, termine a corrida
    if ($totalVoltas[$codigoPiloto] == 4) {
        break;
    }
}

// Fecha o arquivo
fclose($arquivo);

// Ordena os resultados pela quantidade de voltas completadas e tempo total de corrida
usort($resultadosCorrida, function ($a, $b) {
    if ($a['numeroVoltasCompletadas'] == $b['numeroVoltasCompletadas']) {
        return $a['tempoTotalCorrida'] <=> $b['tempoTotalCorrida'];
    }
    return $b['numeroVoltasCompletadas'] <=> $a['numeroVoltasCompletadas'];
});

// Imprime os resultados da corrida
foreach ($resultadosCorrida as $posicao => $piloto) {
    // Adiciona uma posição de chegada
    $piloto['posicaoChegada'] = $posicao + 1;

    // Exibe os resultados
    echo "Posição de chegada: " . $piloto['posicaoChegada'] . "<br>";
    echo "Código do piloto: " . $piloto['codigoPiloto'] . "<br>";
    echo "Nome do piloto: " . $piloto['nomePiloto'] . "<br>";
    echo "Número de voltas completadas: " . $piloto['numeroVoltasCompletadas'] . "<br>";
    echo "Tempo total de corrida: " . gmdate("H:i:s", $piloto['tempoTotalCorrida']) . "<br>";
    echo "<br><br>";
}

