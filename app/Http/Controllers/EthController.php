<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Web3\Web3;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\FORCE_IP_RESOLVE;
use GuzzleHttp\DECODE_CONTENT;
use GuzzleHttp\CONNECT_TIMEOUT;
use GuzzleHttp\READ_TIMEOUT;
use GuzzleHttp\TIMEOUT;

class EthController extends Controller{

  public function showLogsByOwner() {
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
      }
    );
  }
}
