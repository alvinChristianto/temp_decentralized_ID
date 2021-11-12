<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Web3\Web3;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Utils;
use Web3p\EthereumTx\Transaction;     //digunakan untuk self sig transaction -> konek ke testnet/mainet

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\FORCE_IP_RESOLVE;
use GuzzleHttp\DECODE_CONTENT;
use GuzzleHttp\CONNECT_TIMEOUT;
use GuzzleHttp\READ_TIMEOUT;
use GuzzleHttp\TIMEOUT;

class EthController extends Controller{

  public function showLogsByOwner(){
    $contractABI = file_get_contents(storage_path()."/app/abi_file.json");

    //$contract = new Contract('http://127.0.0.1:7545/', $contractABI);
    //$contract = new Contract(new HttpProvider(new HttpRequestManager($_ENV['LOCAL_URL'], $_ENV['INFURA_TIMEOUT'])) , $contractABI);
    $contract = new Contract(new HttpProvider(new HttpRequestManager($_ENV['INFURA_URL'], $_ENV['INFURA_TIMEOUT'])) , $contractABI);

    $contractAddress = $_ENV['CONTRACT_ADDRESS'];
    $firstAccount = $_ENV['1_ACCOUNT'];

    $contract->at($contractAddress)->call("showLogsByOwner", $_ENV['1_ACCOUNT'], function($err,$data) use($firstAccount)  {
      if ($err !== null) {
        echo 'Error: ' . $err->getMessage();
        return;
      }
      echo '  <h2>List data log dari address ' . $firstAccount . ' </h2>'. PHP_EOL;
      foreach ($data as $dt) {
          foreach($dt as $t) {
            echo '<a href="/cek-log/'.$t.'">'.$t.'</a> <br>';
          }
      }
      echo '<br><br><a class="mb-1" href="/">back home</a> ';
    });
//      return view('post.postPreview', ['listpost' => $listpost]);
  }


  public function tokenURI($logToCheck){
    $contractABI = file_get_contents(storage_path()."/app/abi_file.json");
    //$contract = new Contract('http://127.0.0.1:7545/', $contractABI);
    //$contract = new Contract(new HttpProvider(new HttpRequestManager($_ENV['LOCAL_URL'], $_ENV['INFURA_TIMEOUT'])) , $contractABI);
    $contract = new Contract(new HttpProvider(new HttpRequestManager($_ENV['INFURA_URL'], $_ENV['INFURA_TIMEOUT'])) , $contractABI);

    $contractAddress = $_ENV['CONTRACT_ADDRESS'];
    $firstAccount = $_ENV['1_ACCOUNT'];

    $contract->at($contractAddress)->call('tokenURI', $logToCheck, function ($err, $results) use($logToCheck){
        if ($err !== null) {
            echo $err->getMessage() . PHP_EOL;
        }
        if ($results) {
          echo '<h2>isi dari log id = ' . $logToCheck . ' </h2>'. PHP_EOL;
          foreach ($results as $dt) {
            echo $dt . "<br>";
          }
          echo '<br><br><a class="mb-1" href="/">back home</a> ';
        }
      });
  }

  public function saveLogsToRopsten(){
    $infuraProjectId = $_ENV['INFURA_PROJECT_ID'];
    $infuraProjectSecret = $_ENV['INFURA_PROJECT_SECRET'];
    $contractAddress = $_ENV['TOKEN_CONTRACT_ADDRESS'];
    $fromAccount = $_ENV['SOURCE_ACCOUNT_ADDRESS'];
    $fromAccountPrivateKey = $_ENV['SOURCE_ACCOUNT_PRIVATE_KEY'];
    $secondsToWaitForReceiptString = $_ENV['SECONDS_TO_WAIT_FOR_RECEIPT'];
    $secondsToWaitForReceipt = intval($secondsToWaitForReceiptString);
    $factorToMultiplyGasEstimateString = $_ENV['FACTOR_TO_MULTIPLY_GAS_ESTIMATE'];
    $factorToMultiplyGasEstimate = intval($factorToMultiplyGasEstimateString);
    $toAccount = '0xeBe9b00A81916f0ADE7a8f485Ff22af33AcEBaf0';
    $tokenIDtoSend = 100;

    $chainIds = [
        'Mainnet' => 1,
        'Ropsten' => 3
    ];

    $infuraHosts = [
        'Mainnet' => 'mainnet.infura.io',
        'Ropsten' => 'ropsten.infura.io'
    ];

    $chainId = $chainIds[$_ENV['CHAIN_NAME']];
    $infuraHost = $infuraHosts[$_ENV['CHAIN_NAME']];

    $timeout = 10;
    //$abi = file_get_contents(__DIR__ . '/../resources/Erc777TokenAbiArray.json');
    $abi = file_get_contents(storage_path()."/app/abi_file.json");

    //$contract = new Contract(new HttpProvider(new HttpRequestManager("https://:$infuraProjectSecret@$infuraHost/v3/$infuraProjectId", $timeout)) , $abi);
    $contract = new Contract(new HttpProvider(new HttpRequestManager("https://ropsten.infura.io/v3/790551957e7d483a932d0b1a3f6e7eaa", $timeout)) , $abi);
    $eth = $contract->eth;

    //1. GET TRANSACTION DATA DARI FUNGSI saveLogs(address user, string url ipfs)
    $rawTransactionData = '0x' . $contract->at($contractAddress)->getData('saveLogs', $fromAccount,'ipfsss.txt');
    echo "raw = $rawTransactionData" . PHP_EOL;
    $tCount = $this->transactionCount($eth, $fromAccount, 'latest');
    echo "transactionCount = " . $tCount . PHP_EOL;

    //2. SET TRANSACTION PARAMS
    $txParams = [
        'from' => $fromAccount,
        'to' => $contractAddress,
        'value' => '0x0',
        'nonce' => "0x" . dechex($tCount->toString()),// dec_to_hex($from_addr_nonce->toString()),
        'gas' => '0x' . dechex(8000000), //'0x76c0', //GAS LIMIT ??
        'gasPrice' =>  "0x" . dechex($tCount->toString()) . "000000000",  //format in wei
        'chainId' => "0x" .$chainId,
        'data' => $rawTransactionData,
    ];

    $EstGas = $this->estimateTheGas($eth, $txParams);
    echo "estimatedGas = " . $EstGas . PHP_EOL;

    $gasPriceMultiplied = hexdec(dechex($EstGas->toString())) * $factorToMultiplyGasEstimate;
    echo "\$gasPriceMultiplied=$gasPriceMultiplied" . PHP_EOL;

    $txParams['gasPrice'] = '0x' . dechex($gasPriceMultiplied);
    $txParams['chainId'] = $chainId;

    echo '<br><br><br><br> remove die() untuk simpan log ke ethereum';
    die();   //remove to send to send to blockchain

    // 3. SELF SIGN TRANSACTION WITH EthereumTx
    $transaction = new Transaction($txParams);
    $signedTransaction = $transaction->sign($fromAccountPrivateKey);
    echo '$signedTransaction : '. $signedTransaction . PHP_EOL;

    // 4. SEND TO ROPSTEN/MAINNET -> call sendRawTransaction with self signed transact
    $txHash = null;
    $eth->sendRawTransaction('0x'. $signedTransaction, function ($err, $txResult) use (&$txHash) {
      if($err) {
        echo ' error: ' . $err->getMessage() . PHP_EOL;
      }
      $txHash = $txResult;
    });

    echo "\$txHash=$txHash" . PHP_EOL;

    //5. WAITING FOR RECEIPTS
    $txReceipt = null;
    echo "Waiting for transaction receipt";
    for ($i=0; $i <= $secondsToWaitForReceipt; $i++) {
        echo '.';
        $eth->getTransactionReceipt($txHash, function ($err, $txReceiptResult) use(&$txReceipt) {
            if($err) {
                echo 'getTransactionReceipt error: ' . $err->getMessage() . PHP_EOL;
            } else {
                $txReceipt = $txReceiptResult;
            }
        });

        if ($txReceipt) {
            echo PHP_EOL;
            break;
        }

        sleep(1);
    }
    $txStatus = $txReceipt->status;
    echo "\$txStatus=$txStatus" . PHP_EOL;
    echo '<br><br><a class="mb-1" href="/">back home</a> ';
  }

  //
  private function estimateTheGas($eth, $params){
    $estimatedGas = null;
    $eth->estimateGas($params, function ($err, $gas) use (&$estimatedGas) {
      if ($err) {
          echo 'estimateGas error: ' . $err->getMessage() . PHP_EOL;
      } else {
          $estimatedGas = $gas;
      }
    });
    return $estimatedGas;
  }

  //
  private function transactionCount($eth, $user_account, $status = 'latest') {
    $transactionCount = null;
    $eth->getTransactionCount($user_account, $status, function ($err, $transactionCountResult) use(&$transactionCount) {
      if($err) {
          echo 'getTransactionCount error: ' . $err->getMessage() . PHP_EOL;
      } else {
          $transactionCount = $transactionCountResult;
      }
    });
    return $transactionCount;
  }

}
