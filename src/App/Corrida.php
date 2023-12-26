<?php

require_once '../src/App/Piloto.php';
require_once '../src/App/Arquivo.php';
require_once '../src/App/Tempo.php';

/**
 * Define o número mínimo de voltas para exibição.
 */
define('MIN_VOLTAS_PARA_EXIBICAO', 4);

/**
 * Lê o arquivo de corrida e retorna as linhas como um array.
 *
 * @param string $caminhoArquivo Caminho do arquivo de log.
 *
 * @return array Linhas do arquivo.
 */
$linhas = lerArquivoCorrida("../log.txt");

/**
 * Array para armazenar os resultados da corrida.
 *
 * @var Piloto[] $resultadosCorrida
 */
$resultadosCorrida = [];

/**
 * Informações sobre a melhor volta da corrida.
 *
 * @var array $melhorVoltaCorrida
 */
$melhorVoltaCorrida = [
    'tempoVolta' => PHP_FLOAT_MAX,
    'numeroVolta' => 0,
    'nomePiloto' => '',
];

foreach ($linhas as $linha) {
    $dados = explode(" ", $linha);

    if (count($dados) < 7) {
        continue;
    }

    $codigoPiloto = $dados[1];
    $numeroVolta = $dados[4];
    $tempoVolta = $dados[5];
    $velocidadeMediaPorVolta = $dados[6];

    if (!isset($resultadosCorrida[$codigoPiloto])) {
        preg_match('/\d+ – (.+?)\s/', $linha, $matches);
        $nomePiloto = $matches[1];

        $codigoPiloto = substr($codigoPiloto, 0, 3);

        /**
         * Inicializa os dados do piloto.
         *
         * @var Piloto $piloto
         */
        $resultadosCorrida[$codigoPiloto] = inicializarDadosPiloto($codigoPiloto, $nomePiloto);
    }

    $tempoVoltaNumerico = converterTempoVoltaParaSegundos($tempoVolta);
    $resultadosCorrida[$codigoPiloto]->atualizarDados($numeroVolta, $tempoVoltaNumerico, (float)$velocidadeMediaPorVolta);

    if ($tempoVoltaNumerico < $melhorVoltaCorrida['tempoVolta']) {
        $melhorVoltaCorrida['tempoVolta'] = $tempoVoltaNumerico;
        $melhorVoltaCorrida['numeroVolta'] = $numeroVolta;
        $melhorVoltaCorrida['nomePiloto'] = $resultadosCorrida[$codigoPiloto]->getNome();
    }
}

foreach ($resultadosCorrida as $codigoPiloto => $piloto) {
    $numeroVoltas = $piloto->getNumeroVoltasCompletadas();
    if ($numeroVoltas > 0) {
        $piloto->calcularVelocidadeMedia();
    }
}

usort($resultadosCorrida, function ($a, $b) {
    $aVoltas = $a->getNumeroVoltasCompletadas();
    $bVoltas = $b->getNumeroVoltasCompletadas();

    if ($aVoltas == $bVoltas) {
        $aTempo = $a->getTempoTotalCorrida();
        $bTempo = $b->getTempoTotalCorrida();
        return $aTempo <=> $bTempo;
    }

    return $bVoltas <=> $aVoltas;
});

/**
 * Piloto vencedor da corrida.
 *
 * @var Piloto $vencedor
 */
$vencedor = reset($resultadosCorrida);
