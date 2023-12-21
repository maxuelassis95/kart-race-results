<?php

// Converte o tempo de volta no formato mm:ss.sss para segundos
function converterTempoVoltaParaSegundos($tempoVolta)
{
    $partesTempo = explode(":", $tempoVolta);
    $minutos = (int)$partesTempo[0];
    $segundos = (float)$partesTempo[1];
    return $minutos * 60 + $segundos;
}

// Formata o tempo para o formato mm:ss.sss
function formatarTempo($tempoEmSegundos)
{
    $minutos = floor($tempoEmSegundos / 60);
    $segundos = $tempoEmSegundos - $minutos * 60;
    return sprintf("%02d:%06.3f", $minutos, $segundos);
}

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

    // Verifica se a linha possui dados suficientes antes de continuar
    $dados = explode(" ", $linha);
    if (count($dados) < 7) {
        continue;
    }

    // Extrai os dados necessários
    $codigoPiloto = $dados[1];
    $numeroVolta = $dados[4];
    $tempoVolta = $dados[5];

    // Verifica se o piloto já está nos resultados da corrida
    if (!isset($resultadosCorrida[$codigoPiloto])) {
        // Encontra o nome do piloto corretamente usando uma expressão regular
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
    $tempoVoltaNumerico = converterTempoVoltaParaSegundos($tempoVolta);
    $resultadosCorrida[$codigoPiloto]['tempoTotalCorrida'] += $tempoVoltaNumerico;
}

// Fecha o arquivo
fclose($arquivo);

// Transforma $resultadosCorrida em um array simples
$resultadosSimples = array_values($resultadosCorrida);

// Ordena os resultados pela quantidade de voltas completadas e tempo total de corrida
usort($resultadosCorrida, function ($a, $b) {
    if ($a['numeroVoltasCompletadas'] == $b['numeroVoltasCompletadas']) {
        return $a['tempoTotalCorrida'] <=> $b['tempoTotalCorrida'];
    }
    return $b['numeroVoltasCompletadas'] <=> $a['numeroVoltasCompletadas'];
});

// Imprime os resultados da corrida
echo "<br>Classificação:<br>";
echo "<br>Posição | Código | Nome | Voltas | Tempo Total<br>";
foreach ($resultadosCorrida as $posicao => $piloto) {
    // Adiciona uma posição de chegada
    $piloto['posicaoChegada'] = $posicao + 1;

    // Exibe os resultados de todos os pilotos que completaram pelo menos 4 voltas
    if ($totalVoltas[$piloto['codigoPiloto']] > 0) {


        // Exibe os resultados
        echo "<br>Posição de chegada: " . $piloto['posicaoChegada'] . "<br>";
        echo "Código do piloto: " . $piloto['codigoPiloto'] . "<br>";
        echo "Nome do piloto: " . $piloto['nomePiloto'] . "<br>";
        echo "Número de voltas completadas: " . $piloto['numeroVoltasCompletadas'] . "<br>";
        echo "Tempo total de corrida: " . formatarTempo($piloto['tempoTotalCorrida']) . "<br>";
        echo "<br><br>";
    }
}
