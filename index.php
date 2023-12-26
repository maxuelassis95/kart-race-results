<?php
require_once 'src/App/Corrida.php';

/**
 * Exibe as informações da corrida na tabela de resultados.
 *
 * @param object $piloto Objeto representando o piloto.
 * @param int $posicao Posição do piloto na corrida.
 * @param array $resultadosCorrida Array contendo os resultados da corrida.
 * @param object $vencedor Objeto representando o piloto vencedor.
 * @return string Retorna a linha formatada da tabela.
 */
function exibirInformacoes($piloto, $posicao, $resultadosCorrida, $vencedor) {
    $piloto->posicaoChegada = $posicao + 1;
    $linha = "<tr>";
    $linha .= "<td>" . htmlspecialchars($piloto->posicaoChegada) . "</td>";
    $linha .= "<td>" . htmlspecialchars($piloto->getCodigo()) . "</td>";
    $linha .= "<td>" . htmlspecialchars($piloto->getNome()) . "</td>";
    $linha .= "<td>" . htmlspecialchars($piloto->getNumeroVoltasCompletadas()) . "</td>";
    $linha .= "<td>" . htmlspecialchars(formatarTempo($piloto->getTempoTotalCorrida())) . "</td>";
    $linha .= "<td>" . htmlspecialchars(formatarTempo($piloto->getMelhorVolta())) . " (Volta " . htmlspecialchars($piloto->getNumeroMelhorVolta()) . ")</td>";
    $linha .= "<td>" . htmlspecialchars(number_format($piloto->getVelocidadeMedia(), 1)) . " km/h</td>";
    $linha .= "<td>";

    if ($posicao === 0) {
        $linha .= "<span class='vencedor'>Vencedor</span>";
    } elseif ($posicao === count($resultadosCorrida) - 1) {
        $linha .= "<span class='abandonou'>Piloto abandonou a corrida</span>";
    } else {
        $tempoAposVencedor = $piloto->getTempoTotalCorrida() - $vencedor->getTempoTotalCorrida();
        $linha .= "Tempo após o vencedor: <span class='tempo-apos-vencedor'>" . htmlspecialchars(formatarTempo($tempoAposVencedor)) . "</span>";
    }

    $linha .= "</td>";
    $linha .= "</tr>";
    return $linha;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Corrida</title>
    <link rel="stylesheet" type="text/css" href="resources/css/app.css">
</head>
<body>
    <h1>Resultados da Corrida</h1>
    <table>
        <tr>
            <th>Posição de chegada</th>
            <th>Código do piloto</th>
            <th>Nome do piloto</th>
            <th>Número de voltas completadas</th>
            <th>Tempo total de corrida</th>
            <th>Melhor volta</th>
            <th>Velocidade média</th>
            <th>Informações adicionais</th>
        </tr>
        <?php foreach ($resultadosCorrida as $posicao => $piloto) {
            echo exibirInformacoes($piloto, $posicao, $resultadosCorrida, $vencedor);
        } ?>
    </table>
    <div class="melhor-volta">
        <strong>Melhor volta da corrida:</strong> <?php echo formatarTempo($melhorVoltaCorrida['tempoVolta']); ?>
        (Volta <?php echo $melhorVoltaCorrida['numeroVolta']; ?>, Piloto <?php echo $melhorVoltaCorrida['nomePiloto']; ?>)
    </div>
</body>
</html>