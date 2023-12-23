<?php

define('MIN_VOLTAS_PARA_EXIBICAO', 4);

// Função para converter o tempo da volta para segundos
function converterTempoVoltaParaSegundos($tempoVolta)
{
    list($minutos, $segundos) = explode(":", $tempoVolta);
    return (int)$minutos * 60 + (float)$segundos;
}

// Função para formatar o tempo em segundos para o formato MM:SS.sss
function formatarTempo($tempoEmSegundos)
{
    $minutos = floor($tempoEmSegundos / 60);
    $segundos = $tempoEmSegundos - $minutos * 60;
    return sprintf("%02d:%06.3f", $minutos, $segundos);
}

// Função para ler o arquivo de corrida e retornar as linhas
function lerArquivoCorrida($caminhoArquivo)
{
    $linhas = file($caminhoArquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Pula a primeira linha
    array_shift($linhas);

    return $linhas;
}

// Função para inicializar os dados do piloto
function inicializarDadosPiloto($codigoPiloto, $nomePiloto)
{
    return [
        'codigoPiloto' => $codigoPiloto,
        'nomePiloto' => $nomePiloto,
        'numeroVoltasCompletadas' => 0,
        'tempoTotalCorrida' => 0,
        'melhorVolta' => PHP_FLOAT_MAX,
        'numeroMelhorVolta' => 0,
        'velocidadeMedia' => 0,
    ];
}

// Lê o arquivo de log
$arquivo = fopen("log.txt", "r") or die("Não foi possível abrir o arquivo!");
$linhas = lerArquivoCorrida("log.txt");

// Inicializa um array para armazenar os resultados da corrida
$resultadosCorrida = [];
// Inicializa um array para armazenar o número total de voltas de cada piloto
$totalVoltas = [];
// Inicializa um array para armazenar a velocidade média de cada piloto
$velocidadeMediaPiloto = [];

// Inicializa uma variável para armazenar a melhor volta da corrida
$melhorVoltaCorrida = [
    'tempoVolta' => PHP_FLOAT_MAX,
    'numeroVolta' => 0,
    'nomePiloto' => '',
];

// Lê o arquivo linha por linha
foreach ($linhas as $linha) {
    // Obtém a linha atual
    $dados = explode(" ", $linha);

    // Verifica se a linha possui dados suficientes antes de continuar
    if (count($dados) < 7) {
        continue;
    }

    // Extrai os dados necessários
    $codigoPiloto = $dados[1];
    $numeroVolta = $dados[4];
    $tempoVolta = $dados[5];
    $velocidadeMediaPorVolta = $dados[6];

    // Verifica se o piloto já está nos resultados da corrida
    if (!isset($resultadosCorrida[$codigoPiloto])) {
        // Encontra o nome do piloto corretamente usando uma expressão regular
        preg_match('/\d+ – (.+?)\s/', $linha, $matches);
        $nomePiloto = $matches[1];

        // Obtém apenas o código do piloto
        $codigoPiloto = substr($codigoPiloto, 0, 3);

        // Inicializa os dados do piloto
        $resultadosCorrida[$codigoPiloto] = inicializarDadosPiloto($codigoPiloto, $nomePiloto);

        // Inicializa o número total de voltas do piloto
        $totalVoltas[$codigoPiloto] = 0;
        // Inicializa a velocidade média do piloto como zero
        $velocidadeMediaPiloto[$codigoPiloto] = 0;
    }

    // Atualiza os dados do piloto
    $resultadosCorrida[$codigoPiloto]['numeroVoltasCompletadas']++;

    // Atualiza o número total de voltas do piloto
    $totalVoltas[$codigoPiloto] = max($totalVoltas[$codigoPiloto], $numeroVolta);

    // Calcula o tempo total de corrida
    $tempoVoltaNumerico = converterTempoVoltaParaSegundos($tempoVolta);
    $resultadosCorrida[$codigoPiloto]['tempoTotalCorrida'] += $tempoVoltaNumerico;

    // Atualiza a melhor volta de cada piloto
    if ($tempoVoltaNumerico < $resultadosCorrida[$codigoPiloto]['melhorVolta']) {
        $resultadosCorrida[$codigoPiloto]['melhorVolta'] = $tempoVoltaNumerico;
        $resultadosCorrida[$codigoPiloto]['numeroMelhorVolta'] = $numeroVolta;
    }

    // Atualiza a melhor volta da corrida
    if ($tempoVoltaNumerico < $melhorVoltaCorrida['tempoVolta']) {
        $melhorVoltaCorrida['tempoVolta'] = $tempoVoltaNumerico;
        $melhorVoltaCorrida['numeroVolta'] = $numeroVolta;
        $melhorVoltaCorrida['nomePiloto'] = $resultadosCorrida[$codigoPiloto]['nomePiloto'];
    }

    // Atualiza a velocidade média de cada piloto
    if ($tempoVoltaNumerico > 0) {
        $velocidadeMediaPorVolta = (float)$velocidadeMediaPorVolta;
        $velocidadeMediaPiloto[$codigoPiloto] += $velocidadeMediaPorVolta;
    }
}

// Calcula a velocidade média final de cada piloto
foreach ($resultadosCorrida as $codigoPiloto => $piloto) {
    $numeroVoltas = $piloto['numeroVoltasCompletadas'];

    if ($numeroVoltas > 0) {
        // Calcula a velocidade média final dividindo pela quantidade total de voltas
        $resultadosCorrida[$codigoPiloto]['velocidadeMedia'] = $velocidadeMediaPiloto[$codigoPiloto] / $numeroVoltas;
    }
}

// Ordena os resultados pela quantidade de voltas completadas e tempo total de corrida
usort($resultadosCorrida, function ($a, $b) {
    $aVoltas = $a['numeroVoltasCompletadas'];
    $bVoltas = $b['numeroVoltasCompletadas'];

    if ($aVoltas == $bVoltas) {
        $aTempo = $a['tempoTotalCorrida'];
        $bTempo = $b['tempoTotalCorrida'];
        return $aTempo <=> $bTempo;
    }

    return $bVoltas <=> $aVoltas;
});

// Encontra o vencedor da corrida (primeiro colocado)
$vencedor = reset($resultadosCorrida);

foreach ($resultadosCorrida as $posicao => $piloto) {
    // Adiciona uma posição de chegada
    $resultadosCorrida[$posicao]['posicaoChegada'] = $posicao + 1;

    // Exibe os resultados de todos os pilotos que completaram pelo menos MIN_VOLTAS_PARA_EXIBICAO voltas
    // ou exibe o último piloto mesmo que não tenha completado MIN_VOLTAS_PARA_EXIBICAO voltas
    if ($piloto['numeroVoltasCompletadas'] >= MIN_VOLTAS_PARA_EXIBICAO || $posicao === count($resultadosCorrida) - 1) {
        // Exibe os resultados
        echo "<br>Posição de chegada: " . $resultadosCorrida[$posicao]['posicaoChegada'] . "<br>";
        echo "Código do piloto: " . $resultadosCorrida[$posicao]['codigoPiloto'] . "<br>";
        echo "Nome do piloto: " . $resultadosCorrida[$posicao]['nomePiloto'] . "<br>";
        echo "Número de voltas completadas: " . $resultadosCorrida[$posicao]['numeroVoltasCompletadas'] . "<br>";
        echo "Tempo total de corrida: " . formatarTempo($resultadosCorrida[$posicao]['tempoTotalCorrida']) . "<br>";
        echo "Melhor volta: " . formatarTempo($resultadosCorrida[$posicao]['melhorVolta']) . " (Volta " . $resultadosCorrida[$posicao]['numeroMelhorVolta'] . ")<br>";

        // Exibe a velocidade média formatada
        $velocidadeMediaFormatada = number_format($resultadosCorrida[$posicao]['velocidadeMedia'], 1) . " km/h";
        echo "Velocidade média: " . $velocidadeMediaFormatada . "<br>";

        // Verifica se o piloto é o vencedor
        if ($posicao === 0) {
            echo "Vencedor<br>";
        } elseif ($posicao === count($resultadosCorrida) - 1) {
            echo "Piloto abandonou a corrida<br>";
        } else {
            // Calcula o tempo que cada piloto chegou após o primeiro
            $tempoAposVencedor = $piloto['tempoTotalCorrida'] - $resultadosCorrida[0]['tempoTotalCorrida'];
            echo "Tempo após o vencedor: " . formatarTempo($tempoAposVencedor) . "<br>";
        }
        echo "<br><br>";
    }
}

// Exibe a melhor volta da corrida
echo "Melhor volta da corrida: " . formatarTempo($melhorVoltaCorrida['tempoVolta']) .
     " (Volta " . $melhorVoltaCorrida['numeroVolta'] . ", Piloto " . $melhorVoltaCorrida['nomePiloto'] . ")<br><br>";

// Fecha o arquivo
fclose($arquivo);
?>