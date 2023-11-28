<?php

namespace App\Http\Controllers;

use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\MoviData;
use App\Enums\Actions;

class UploadController extends Controller
{
    /**
     * Handles the file upload
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws UploadMissingFileException
     * @throws UploadFailedException
     */
    public function store(Request $request)
    {
        // criando o receptor do arquivo
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

        // verifique se o upload foi bem-sucedido, lance uma exceção ou retorne a resposta necessária
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        // receber o arquivo
        $save = $receiver->receive();

        // verifique se o upload foi concluído
        if ($save->isFinished()) {

            $user = auth()->user();

            LogController::addsLog(null, $user->name, Actions::UPLOAD_IS_FINISHED);

            $filePath = $save->getFile();
            $parts = explode('/', $filePath); // Divide a string pela barra
            $fileName = end($parts);  // pega a ultima parte 

            $this->extractData($fileName, $user->name);

            return response()->json([
                'fileName' => $fileName
            ]);
        }

        $handler = $save->handler();

        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    public function extractData(string $fileName, string $userName)
    {
        LogController::addsLog(null, $userName, Actions::START_DATA_PROCESSING);

        $file = Storage::disk('local')->readStream('chunks/' . $fileName);

        $startTime = microtime(true); // Marcação de tempo inicial
        $previousLine1 = '';
        $previousLine2 = '';
        $date = '';
        $dataToInsert = [];
        $lineCount = 0;
        $tamanhoDaLinha = '';

        set_time_limit(1800);
        ini_set('memory_limit', '2048M');
        $insertBatchSize = 5000;
        $currentBatchCount = 0;
        if ($file !== false) {

            while (!feof($file)) {

                $line = fgets($file);
                $lineCount++;
                $substring205to225 = substr($line, 140, 50);

                $line = mb_convert_encoding($line, 'ISO-8859-1', 'UTF-8');

                if (preg_match('/^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}$/', trim($line))) {
                    $data_hora = trim($line);
                    $data = '';
                    $hora = '';
                    list($data, $hora) = explode(' ', $data_hora);

                    $origem = trim(substr($previousLine1, 0, 8));

                    if ($origem) {
                        $coop = '';
                        $agencia = '';

                        if (strpos($origem, '/') !== false) {
                            // Se contém o formato 0000/00
                            list($coop, $agencia) = explode('/', $origem);
                        } else {
                            // Se é no formato 00
                            $coop = '0101';
                            $agencia = $origem;
                        }
                    }

                    if ($previousLine1 && $previousLine2) {
                        $tamanhoDaLinha = strlen($previousLine2);
                        $debito = '';
                        if ($tamanhoDaLinha > 150) {
                            $debito = (float)str_replace(',', '.', str_replace('.', '', trim(substr($previousLine1, 193, 106))));
                        } else {
                            $debito = (float)str_replace(',', '.', str_replace('.', '', trim(substr($previousLine1, 98, 12))));
                        }
                        $dataToInsert[] = [
                            'coop' => $coop,
                            'agencia' => $agencia,
                            'conta' => $this->getConta($previousLine1),
                            'nome_correntista' =>
                            mb_convert_encoding(
                                trim(substr($previousLine1, 16, 32)),
                                'ISO-8859-1',
                                'UTF-8'
                            ),
                            'cod' => trim(substr($previousLine1, 58, 4)),
                            'descricao' => trim(substr($previousLine1, 62, 26)),
                            'debito' => $debito,
                            'credito' => (float)str_replace(',', '.', str_replace('.', '', trim(substr($previousLine1, 116, 14)))),
                            'data' => $data,
                            'hora' => $hora,
                        ];
                    }

                    $previousLine1 = '';
                    $previousLine2 = '';
                } else {
                    if ($this->getConta($line)) {
                        $previousLine1 = $line;
                    }
                    if ($previousLine1) {
                        $previousLine2 = $line;
                    }
                }
                $currentBatchCount++;

                if ($currentBatchCount >= $insertBatchSize) {
                    // Inserir em lote
                    MoviData::insert($dataToInsert);

                    // Limpar o array para o próximo lote
                    $dataToInsert = [];
                    $currentBatchCount = 0;
                }
            }
            fclose($file);

            if (!empty($dataToInsert)) {
                MoviData::insert($dataToInsert);
            }

            $endTime = microtime(true); // Marcação de tempo final
            $executionTime = ($endTime - $startTime); // Tempo de execução em segundos

            LogController::addsLog(null, $userName, Actions::END_DATA_PROCESSING, $processing = ['tempo de processamento' => $executionTime]);

            info($executionTime);
            info($lineCount);
            return response()->json('deu certo');
        }
        return response()->json('deu certo1');
    }

    public static function getOrigem($line)
    {
        $data = trim(substr($line, 0, 8));

        if ($data) {
            $origem = [
                'coop' => '',
                'agencia' => '',

            ];
            if (strpos($data, '/') !== false) {
                // Se contém o formato 0000/00
                list($origem['coop'], $origem['agencia']) = explode('/', $data);
            } else {
                // Se é no formato 00
                $origem['coop'] = '0101';
                $origem['agencia'] = $data;
            }
            return $origem;
        }
        return $data;
    }
    public static function getConta($line)
    {
        $conta = '';
        if (preg_match('/^\d{5}-\d$/', trim(substr($line, 8, 8)))) {
            $conta = trim(substr($line, 8, 8));
        }
        return $conta;
    }
    public static function getDataHora($line)
    {
        $data_hora = '';
        if (preg_match('/^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}$/', trim(substr($line, 116, 16)))) $data_hora = trim(substr($line, 116, 16));
        return $data_hora;
    }
}
