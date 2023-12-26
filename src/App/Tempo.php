<?php

/**
 * Converte o tempo de volta no formato MM:SS.SSS para segundos.
 *
 * @param string $tempoVolta Tempo de volta no formato MM:SS.SSS.
 *
 * @return float Tempo em segundos.
 *
 * @throws Exception Se houver um erro ao converter o tempo da volta para segundos.
 */
function converterTempoVoltaParaSegundos($tempoVolta)
{
    try {
        // Divide o tempo de volta em minutos e segundos.
        list($minutos, $segundos) = explode(":", $tempoVolta);

        // Verifica se os valores são numéricos.
        if (!is_numeric($minutos) || !is_numeric($segundos)) {
            throw new Exception("Erro ao converter o tempo da volta para segundos: formato inválido.");
        }

        // Converte o tempo para segundos.
        return (int)$minutos * 60 + (float)$segundos;

    } catch (Exception $e) {
        throw new Exception("Erro ao converter o tempo da volta para segundos: " . $e->getMessage());
    }
}

/**
 * Formata o tempo em segundos para o formato MM:SS.SSS.
 *
 * @param float $tempoEmSegundos Tempo em segundos.
 *
 * @return string Tempo formatado no formato MM:SS.SSS.
 *
 * @throws Exception Se houver um erro ao formatar o tempo.
 */
function formatarTempo($tempoEmSegundos)
{
    try {
        // Calcula minutos e segundos.
        $minutos = floor($tempoEmSegundos / 60);
        $segundos = $tempoEmSegundos - $minutos * 60;

        // Retorna o tempo formatado.
        return sprintf("%02d:%06.3f", $minutos, $segundos);
    } catch (Exception $e) {
        throw new Exception("Erro ao formatar tempo: " . $e->getMessage());
    }
}
