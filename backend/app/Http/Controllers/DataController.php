<?php

namespace App\Http\Controllers;

use App\Enums\Actions;
use App\Models\MoviData;

class DataController extends Controller
{
    public function qtdMov()
    {
        ini_set('memory_limit', '2048M');
        $startTime = microtime(true);
        /////////////////////////////////////////////////////////////
        //   Data com maior e menor quantidade de movimentações;   //
        /////////////////////////////////////////////////////////////
        $contagemPorData = MoviData::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => '$data',                                   // Agrupa por data
                        'count' => ['$sum' => 1],                           // Conta a quantidade de ocorrências para cada data
                    ],
                ],
                [
                    '$sort' => ['count' => 1],                              // Ordena em ordem crescente
                ],
                [
                    '$group' => [
                        '_id' => null,                                      // Agrupa todos os documentos
                        'maiorQuantidade' => ['$last' => '$$ROOT'],         // Obtém o último documento (com maior quantidade) $$ROOT se refere ao documento atual
                        'menorQuantidade' => ['$first' => '$$ROOT'],        // Obtém o primeiro documento (com menor quantidade)
                    ],
                ],
                [
                    '$project' => [
                        'dataMaiorQuantidade' => '$maiorQuantidade._id',    // Extrai a data da maior quantidade
                        'maiorQuantidade' => '$maiorQuantidade.count',      // Extrai a maior quantidade
                        'dataMenorQuantidade' => '$menorQuantidade._id',    // Extrai a data da menor quantidade
                        'menorQuantidade' => '$menorQuantidade.count',      // Extrai a menor quantidade
                        '_id' => 1,
                    ],
                ],
            ]);
        });


        $endTime = microtime(true); // Marcação de tempo final
        $executionTime = ($endTime - $startTime); // Tempo de execução em segundos

        $user = auth()->user();

        LogController::addsLog(null, 'user->name', Actions::QUERY_DATA_MAIOR_E_MENOR_QUANTIDADE_MOVIMENTAÇÃO, $processing = ['tempo de processamento' => $executionTime]);
        info($executionTime);
        return response()->json([
            'dataMaiorMenorNumRegistros' => $contagemPorData
        ]);
    }

    public function vlrMov()
    {
        ini_set('memory_limit', '2048M');
        $startTime = microtime(true);

        /////////////////////////////////////////////////////////
        //    Data com maior e menor soma de movimentações;    //
        /////////////////////////////////////////////////////////
        $maiorMenorMov =
            MoviData::raw(function ($collection) {
                return $collection->aggregate([
                    [
                        '$group' => [
                            '_id' => '$data',
                            'totalMovimentado' => ['$sum' => ['$add' => ['$credito', '$debito']]],
                        ],
                    ],
                    [
                        '$sort' => ['totalMovimentado' => -1], // Ordena em ordem decrescente
                    ],
                ]);
            });

        $data['dataMenorMovimentacao'] = $maiorMenorMov->last();
        $data['dataMaiorMovimentacao'] = $maiorMenorMov->first();

        $endTime = microtime(true); // Marcação de tempo final
        $executionTime = ($endTime - $startTime); // Tempo de execução em segundos

        $user = auth()->user();

        LogController::addsLog(null, $user->name, Actions::QUERY_DATA_MAIOR_E_MENOR_SOMA_MOVIMENTAÇÃO, $processing = ['tempo de processamento' => $executionTime]);
        info($executionTime);
        return response()->json([
            'maiorMenorMov' => $data,
        ]);
    }

    public function rx1Px1()
    {
        ini_set('memory_limit', '2048M');
        $startTime = microtime(true);

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //    Dia da semana em que houveram mais movimentações dos tipos (código de movimentação) “RX1” e “PX1”;    //
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $RX1GroupedByWeekday =
            MoviData::raw(function ($collection) {
                return $collection->aggregate([
                    ['$match' => ['cod' => ['$in' => ['RX1']]]],
                    [
                        '$group' => [
                            '_id' => [
                                'diaSemana' => [
                                    '$dayOfWeek' => [
                                        '$dateFromString' => [
                                            'dateString' => '$data',
                                            'format' => '%d/%m/%Y',
                                        ],
                                    ],
                                ],
                            ],
                            'count' => ['$sum' => 1],
                            'totalCredito' => ['$sum' => '$credito'],
                            'totalDebito' => ['$sum' => '$debito'],
                        ],
                    ],
                    [
                        '$sort' => ['count' => -1],
                    ],
                    [
                        '$limit' => 1,
                    ],
                    [
                        '$project' => [
                            '_id' => 0,
                            'diaSemana' => [
                                '$switch' => [
                                    'branches' => [
                                        [
                                            'case' => ['$eq' => [2, '$_id.diaSemana']],
                                            'then' => 'Segunda-feira',
                                        ],
                                        [
                                            'case' => ['$eq' => [3, '$_id.diaSemana']],
                                            'then' => 'Terça-feira',
                                        ],
                                        [
                                            'case' => ['$eq' => [4, '$_id.diaSemana']],
                                            'then' => 'Quarta-feira',
                                        ],
                                        [
                                            'case' => ['$eq' => [5, '$_id.diaSemana']],
                                            'then' => 'Quinta-feira',
                                        ],
                                        [
                                            'case' => ['$eq' => [6, '$_id.diaSemana']],
                                            'then' => 'Sexta-feira',
                                        ],
                                        [
                                            'case' => ['$eq' => [7, '$_id.diaSemana']],
                                            'then' => 'Sábado',
                                        ],
                                        [
                                            'case' => ['$eq' => [0, '$_id.diaSemana']],
                                            'then' => 'Domingo',
                                        ],
                                    ],
                                    'default' => 'Dia Desconhecido',
                                ],
                            ],
                            'count' => 1,
                            'totalCredito' => 1,
                            'totalDebito' => 1,
                            'somaCreditoDebito' => ['$add' => ['$totalCredito', '$totalDebito']],
                        ],
                    ],
                ]);
            });

        $PX1GroupedByWeekday =
            MoviData::raw(function ($collection) {
                return $collection->aggregate([
                    ['$match' => ['cod' => ['$in' => ['PX1']]]],
                    [
                        '$group' => [
                            '_id' => [
                                'diaSemana' => [
                                    '$dayOfWeek' => [
                                        '$dateFromString' => [
                                            'dateString' => '$data',
                                            'format' => '%d/%m/%Y',
                                        ],
                                    ],
                                ],
                            ],
                            'count' => ['$sum' => 1],
                            'totalCredito' => ['$sum' => '$credito'],
                            'totalDebito' => ['$sum' => '$debito'],
                        ],
                    ],
                    [
                        '$sort' => ['count' => -1],
                    ],
                    [
                        '$limit' => 1,
                    ],
                    [
                        '$project' => [
                            '_id' => 0,
                            'diaSemana' => [
                                '$switch' => [
                                    'branches' => [
                                        [
                                            'case' => ['$eq' => [2, '$_id.diaSemana']],
                                            'then' => 'Segunda-feira',
                                        ],
                                        [
                                            'case' => ['$eq' => [3, '$_id.diaSemana']],
                                            'then' => 'Terça-feira',
                                        ],
                                        [
                                            'case' => ['$eq' => [4, '$_id.diaSemana']],
                                            'then' => 'Quarta-feira',
                                        ],
                                        [
                                            'case' => ['$eq' => [5, '$_id.diaSemana']],
                                            'then' => 'Quinta-feira',
                                        ],
                                        [
                                            'case' => ['$eq' => [6, '$_id.diaSemana']],
                                            'then' => 'Sexta-feira',
                                        ],
                                        [
                                            'case' => ['$eq' => [7, '$_id.diaSemana']],
                                            'then' => 'Sábado',
                                        ],
                                        [
                                            'case' => ['$eq' => [0, '$_id.diaSemana']],
                                            'then' => 'Domingo',
                                        ],
                                    ],
                                    'default' => 'Dia Desconhecido',
                                ],
                            ],
                            'count' => 1,
                            'totalCredito' => 1,
                            'totalDebito' => 1,
                            'somaCreditoDebito' => ['$add' => ['$totalCredito', '$totalDebito']],
                        ],
                    ],
                ]);
            });

        $endTime = microtime(true); // Marcação de tempo final
        $executionTime = ($endTime - $startTime); // Tempo de execução em segundos

        $user = auth()->user();

        LogController::addsLog(null, $user->name, Actions::QUERY_DIA_SEMANA_MAIS_MOVIMENTACAO_RX1_PX1, $processing = ['tempo de processamento' => $executionTime]);
        info($executionTime);
        return response()->json([
            'RX1GroupedByWeekday' => $RX1GroupedByWeekday,
            'PX1GroupedByWeekday' => $PX1GroupedByWeekday,
        ]);
    }

    public function coopAgencia()
    {
        ini_set('memory_limit', '2048M');
        $startTime = microtime(true);

        ////////////////////////////////////////////////////////////
        //    Quantidade e valor movimentado por coop/agência;    //
        ////////////////////////////////////////////////////////////

        $qtdVlrCoopAg = MoviData::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => [
                            'coop' => '$coop',
                            'agencia' => '$agencia',        // agrupando por coop e agencia
                        ],
                        'count' => ['$sum' => 1],
                        'total_credito' => ['$sum' => '$credito'],
                        'total_debito' => ['$sum' => '$debito'],
                    ],
                ],
                [
                    '$group' => [
                        '_id' => '$_id.coop',       //agrupando por coop
                        'agencias' => [             //criando um array
                            '$push' => [            //add dados a agencia
                                'agencia' => '$_id.agencia',
                                'count' => '$count',
                                'total_credito' => '$total_credito',
                                'total_debito' => '$total_debito',
                                'valor_movimentado' => ['$sum' => ['$total_credito', '$total_debito']],
                            ],
                        ],
                        'total_registros' => ['$sum' => '$count'],
                        'total_credito_coop' => ['$sum' => '$total_credito'],
                        'total_debito_coop' => ['$sum' => '$total_debito'],

                    ],
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'coop' => '$_id',
                        'agencias' => 1,
                        'total_registros' => 1,
                        'total_credito_coop' => 1,
                        'total_debito_coop' => 1,
                        // 'total_valor_movimentado_coop' => 1,
                    ],
                ],
                ['$sort' => ['coop' => 1]],
            ]);
        });

        $endTime = microtime(true); // Marcação de tempo final
        $executionTime = ($endTime - $startTime); // Tempo de execução em segundos

        $user = auth()->user();

        LogController::addsLog(null, $user->name, Actions::QUERY_QUANTIDADE_E_VALOR_POR_COOP_AGENCIA, $processing = ['tempo de processamento' => $executionTime]);
        info($executionTime);
        return response()->json([
            'qtdVlrCoopAg' => $qtdVlrCoopAg,
        ]);
    }

    public function relacaoCdtDbt()
    {
        ini_set('memory_limit', '2048M');
        $startTime = microtime(true);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //    Relação de créditos x débitos ao longo das horas do dia (valores fechados por hora. Ex: das 9h às 10h, das 10h às 11h, etc);    //
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        $cdtDbtHoraDia = MoviData::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => [
                            'data' => '$data',      //agrupa por data
                            'hora' => ['$substr' => ['$hora', 0, 2]], //agrupa pelos dois primeiros digitos da hora
                        ],
                        'count' => ['$sum' => 1],
                        'total_credito' => ['$sum' => '$credito'],
                        'total_debito' => ['$sum' => '$debito'],
                    ],
                ],
                ['$group' => [
                    '_id' => '$_id.data', //agrupa por data
                    'horas' => [           //cria o array
                        '$push' => [        // add dados ao array
                            'hora' => [
                                '$concat' => [  //concatena 00 e 59 a coma hora
                                    '$_id.hora',
                                    ':00 - ',
                                    '$_id.hora',
                                    ':59'
                                ],
                            ],
                            'count' => '$count',        //Contagem total para cada hora
                            'total_credito' => '$total_credito', //Soma total de créditos para cada hora
                            'total_debito' => ['$sum' => '$total_debito'],
                        ],
                    ],
                ]],
                [
                    '$project' => [
                        '_id' => 0,
                        'data' => '$_id',
                        'horas' => 1,
                        // 'horas' => [
                        //     'hora' => 1,
                        //     'count' => 1,
                        //     'total_credito' => 1,
                        //     'total_debito' => 1,
                        // ],
                    ],
                ],
                ['$sort' => ['data' => 1]], //Ordena o resultado final com base no campo 'data'
            ]);
        });

        $endTime = microtime(true); // Marcação de tempo final
        $executionTime = ($endTime - $startTime); // Tempo de execução em segundos

        $user = auth()->user();

        LogController::addsLog(null, $user->name, Actions::QUERY_RELACAO_CREDITO_DEBITO_HORAS_DIA, $processing = ['tempo de processamento' => $executionTime]);
        info($executionTime);
        return response()->json([
            'cdtDbtHoraDia' => $cdtDbtHoraDia,
        ]);
    }
}
