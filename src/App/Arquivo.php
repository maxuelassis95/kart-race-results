<?php

/**
 * Lê o conteúdo de um arquivo de corrida e retorna suas linhas.
 *
 * @param string $caminhoArquivo Caminho do arquivo de corrida.
 *
 * @return array Array contendo as linhas do arquivo.
 *
 * @throws Exception Se houver um erro ao ler o arquivo de corrida.
 */
function lerArquivoCorrida($caminhoArquivo)
{
    try {
        // Lê as linhas do arquivo, ignorando novas linhas e linhas vazias.
        $linhas = file($caminhoArquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Verifica se houve falha ao ler o arquivo.
        if ($linhas === false) {
            throw new Exception("Erro ao ler o arquivo de corrida: falha ao ler o arquivo.");
        }

        // Remove a primeira linha (cabeçalho) se existir.
        array_shift($linhas);

        return $linhas;
    } catch (Exception $e) {
        throw new Exception("Erro ao ler o arquivo de corrida: " . $e->getMessage());
    }
}
