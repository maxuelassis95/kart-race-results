<?php

/**
 * Classe que representa um piloto em uma corrida.
 */
class Piloto
{
    /**
     * @var string Código único do piloto.
     */
    private $codigo;

    /**
     * @var string Nome do piloto.
     */
    private $nome;

    /**
     * @var int Número de voltas completadas pelo piloto.
     */
    private $numeroVoltasCompletadas = 0;

    /**
     * @var float Tempo total de corrida do piloto.
     */
    private $tempoTotalCorrida = 0;

    /**
     * @var float Melhor tempo de volta do piloto.
     */
    private $melhorVolta = PHP_FLOAT_MAX;

    /**
     * @var int Número da melhor volta do piloto.
     */
    private $numeroMelhorVolta = 0;

    /**
     * @var float Velocidade média do piloto.
     */
    private $velocidadeMedia = 0;

    /**
     * @var int Posição de chegada do piloto.
     */
    public $posicaoChegada;

    /**
     * Construtor da classe Piloto.
     *
     * @param string $codigo Código único do piloto.
     * @param string $nome   Nome do piloto.
     */
    public function __construct($codigo, $nome)
    {
        $this->codigo = $codigo;
        $this->nome = $nome;
    }

    /**
     * Obtém o código do piloto.
     *
     * @return string Código do piloto.
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Obtém o nome do piloto.
     *
     * @return string Nome do piloto.
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Obtém o número de voltas completadas pelo piloto.
     *
     * @return int Número de voltas completadas.
     */
    public function getNumeroVoltasCompletadas()
    {
        return $this->numeroVoltasCompletadas;
    }

    /**
     * Obtém o tempo total de corrida do piloto.
     *
     * @return float Tempo total de corrida.
     */
    public function getTempoTotalCorrida()
    {
        return $this->tempoTotalCorrida;
    }

    /**
     * Obtém o melhor tempo de volta do piloto.
     *
     * @return float Melhor tempo de volta.
     */
    public function getMelhorVolta()
    {
        return $this->melhorVolta;
    }

    /**
     * Obtém o número da melhor volta do piloto.
     *
     * @return int Número da melhor volta.
     */
    public function getNumeroMelhorVolta()
    {
        return $this->numeroMelhorVolta;
    }

    /**
     * Obtém a velocidade média do piloto.
     *
     * @return float Velocidade média do piloto.
     */
    public function getVelocidadeMedia()
    {
        return $this->velocidadeMedia;
    }

    /**
     * Calcula a velocidade média do piloto.
     */
    public function calcularVelocidadeMedia()
    {
        if ($this->numeroVoltasCompletadas > 0) {
            $this->velocidadeMedia /= $this->numeroVoltasCompletadas;
        }
    }

    /**
     * Atualiza a velocidade média do piloto.
     *
     * @param float $velocidadeMedia Nova velocidade média.
     */
    public function atualizarVelocidadeMedia($velocidadeMedia)
    {
        $this->velocidadeMedia = $velocidadeMedia;
    }

    /**
     * Atualiza os dados do piloto com informações de uma nova volta.
     *
     * @param int   $numeroVolta           Número da volta.
     * @param float $tempoVoltaNumerico    Tempo da volta em formato numérico.
     * @param float $velocidadeMediaPorVolta Velocidade média por volta.
     */
    public function atualizarDados($numeroVolta, $tempoVoltaNumerico, $velocidadeMediaPorVolta)
    {
        $this->numeroVoltasCompletadas++;
        $this->tempoTotalCorrida += $tempoVoltaNumerico;

        if ($tempoVoltaNumerico < $this->melhorVolta) {
            $this->melhorVolta = $tempoVoltaNumerico;
            $this->numeroMelhorVolta = $numeroVolta;
        }

        if ($tempoVoltaNumerico > 0) {
            $this->velocidadeMedia += $velocidadeMediaPorVolta;
        }
    }
}

/**
 * Inicializa os dados de um piloto.
 *
 * @param string $codigoPiloto Código do piloto.
 * @param string $nomePiloto   Nome do piloto.
 *
 * @return Piloto Objeto Piloto inicializado.
 *
 * @throws Exception Se houver um erro ao iniciar os dados do piloto.
 */
function inicializarDadosPiloto($codigoPiloto, $nomePiloto)
{
    try {
        return new Piloto($codigoPiloto, $nomePiloto);
    } catch (Exception $e) {
        throw new Exception("Erro ao iniciar os dados do piloto: " . $e->getMessage());
    }
}
