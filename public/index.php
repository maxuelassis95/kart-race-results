<?php

require_once '../src/App/Corrida.php';

// Exibir informações dos pilotos.
foreach ($resultadosCorrida as $posicao => $piloto) {

     // Define a posição de chegada do piloto.
     $piloto->posicaoChegada = $posicao + 1;

    // Verifica se o piloto completou o número mínimo de voltas ou é o último na corrida.
    if ($piloto->getNumeroVoltasCompletadas() >= MIN_VOLTAS_PARA_EXIBICAO || $posicao === count($resultadosCorrida) - 1) {       

        // Exibe informações do piloto.
        echo "<br>Posição de chegada: " . $piloto->posicaoChegada . "<br>";
        echo "Código do piloto: " . $piloto->getCodigo() . "<br>";
        echo "Nome do piloto: " . $piloto->getNome() . "<br>";
        echo "Número de voltas completadas: " . $piloto->getNumeroVoltasCompletadas() . "<br>";
        echo "Tempo total de corrida: " . formatarTempo($piloto->getTempoTotalCorrida()) . "<br>";
        echo "Melhor volta: " . formatarTempo($piloto->getMelhorVolta()) . " (Volta " . $piloto->getNumeroMelhorVolta() . ")<br>";

        // Formata a velocidade média com uma casa decimal e exibe.
        $velocidadeMediaFormatada = number_format($piloto->getVelocidadeMedia(), 1) . " km/h";
        echo "Velocidade média: " . $velocidadeMediaFormatada . "<br>";

        // Exibe informações específicas para o vencedor, piloto que abandonou ou outros pilotos.
        if ($posicao === 0) {
            echo "Vencedor<br>";
        } elseif ($posicao === count($resultadosCorrida) - 1) {
            echo "Piloto abandonou a corrida<br>";
        } else {
            // Calcula o tempo do piloto em relação ao vencedor.
            $tempoAposVencedor = $piloto->getTempoTotalCorrida() - $vencedor->getTempoTotalCorrida();
            echo "Tempo após o vencedor: " . formatarTempo($tempoAposVencedor) . "<br>";
        }
        echo "<br><br>";
    }
}

// Exibe a melhor volta da corrida.
echo "Melhor volta da corrida: " . formatarTempo($melhorVoltaCorrida['tempoVolta']) .
    " (Volta " . $melhorVoltaCorrida['numeroVolta'] . ", Piloto " . $melhorVoltaCorrida['nomePiloto'] . ")<br><br>";
