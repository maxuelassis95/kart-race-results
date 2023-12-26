<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Corrida</title>
    <link rel="stylesheet" type="text/css" href="../resources/css/app.css">
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

        <?php
        require_once '../src/App/Corrida.php';

        // Exibir informações dos pilotos.
        foreach ($resultadosCorrida as $posicao => $piloto) {

            // Define a posição de chegada do piloto.
            $piloto->posicaoChegada = $posicao + 1;

            // Verifica se o piloto completou o número mínimo de voltas ou é o último na corrida.
            if ($piloto->getNumeroVoltasCompletadas() >= MIN_VOLTAS_PARA_EXIBICAO || $posicao === count($resultadosCorrida) - 1) {
                echo "<tr>";
                echo "<td>" . $piloto->posicaoChegada . "</td>";
                echo "<td>" . $piloto->getCodigo() . "</td>";
                echo "<td>" . $piloto->getNome() . "</td>";
                echo "<td>" . $piloto->getNumeroVoltasCompletadas() . "</td>";
                echo "<td>" . formatarTempo($piloto->getTempoTotalCorrida()) . "</td>";
                echo "<td>" . formatarTempo($piloto->getMelhorVolta()) . " (Volta " . $piloto->getNumeroMelhorVolta() . ")</td>";
                echo "<td>" . number_format($piloto->getVelocidadeMedia(), 1) . " km/h</td>";
                echo "<td>";

                if ($posicao === 0) {
                    echo "<span class='vencedor'>Vencedor</span>";
                } elseif ($posicao === count($resultadosCorrida) - 1) {
                    echo "<span class='abandonou'>Piloto abandonou a corrida</span>";
                } else {
                    $tempoAposVencedor = $piloto->getTempoTotalCorrida() - $vencedor->getTempoTotalCorrida();
                    echo "Tempo após o vencedor: <span class='tempo-apos-vencedor'>" . formatarTempo($tempoAposVencedor) . "</span>";
                }

                echo "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>

    <div class="melhor-volta">
        <strong>Melhor volta da corrida:</strong> <?php echo formatarTempo($melhorVoltaCorrida['tempoVolta']); ?>
        (Volta <?php echo $melhorVoltaCorrida['numeroVolta']; ?>, Piloto <?php echo $melhorVoltaCorrida['nomePiloto']; ?>)
    </div>

</body>

</html>
