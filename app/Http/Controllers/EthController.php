<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Web3\Web3;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

//$contract = new Contract('https://ropsten.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161/', $contractABI); //default metamask
//$contract = new Contract('https://ropsten.infura.io/v3/a3491ed6ac7a4c3a87a914bbe5a1bbdd/', $contractABI); //default infura

$contractABI = '[
  {
    "inputs": [],
    "stateMutability": "nonpayable",
    "type": "constructor"
  },
  {
    "inputs": [],
    "name": "DigitalIDCounter",
    "outputs": [
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "view",
    "type": "function",
    "constant": true
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "name": "dictDigitalIDData",
    "outputs": [
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "view",
    "type": "function",
    "constant": true
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "name": "dictLogData",
    "outputs": [
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "view",
    "type": "function",
    "constant": true
  },
  {
    "inputs": [],
    "name": "logIDCounter",
    "outputs": [
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "view",
    "type": "function",
    "constant": true
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "_address_sender",
        "type": "address"
      },
      {
        "internalType": "string",
        "name": "_tokenURI",
        "type": "string"
      }
    ],
    "name": "saveLogs",
    "outputs": [
      {
        "internalType": "address",
        "name": "",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "_address_sender",
        "type": "address"
      },
      {
        "internalType": "string",
        "name": "_tokenURI",
        "type": "string"
      }
    ],
    "name": "saveDigitalIDs",
    "outputs": [
      {
        "internalType": "address",
        "name": "",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "_address",
        "type": "address"
      }
    ],
    "name": "showLogsByOwner",
    "outputs": [
      {
        "internalType": "uint256[]",
        "name": "",
        "type": "uint256[]"
      }
    ],
    "stateMutability": "view",
    "type": "function",
    "constant": true
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "_address",
        "type": "address"
      }
    ],
    "name": "showDigitalIDByOwner",
    "outputs": [
      {
        "internalType": "uint256[]",
        "name": "",
        "type": "uint256[]"
      }
    ],
    "stateMutability": "view",
    "type": "function",
    "constant": true
  },
  {
    "inputs": [
      {
        "internalType": "uint256",
        "name": "_index",
        "type": "uint256"
      }
    ],
    "name": "showIPFSLogs",
    "outputs": [
      {
        "internalType": "string",
        "name": "",
        "type": "string"
      }
    ],
    "stateMutability": "view",
    "type": "function",
    "constant": true
  },
  {
    "inputs": [
      {
        "internalType": "uint256",
        "name": "_index",
        "type": "uint256"
      }
    ],
    "name": "showIPFSDID",
    "outputs": [
      {
        "internalType": "string",
        "name": "",
        "type": "string"
      }
    ],
    "stateMutability": "view",
    "type": "function",
    "constant": true
  },
  {
    "inputs": [
      {
        "internalType": "uint256",
        "name": "_tokenId",
        "type": "uint256"
      }
    ],
    "name": "ownerOfLog",
    "outputs": [
      {
        "internalType": "address",
        "name": "",
        "type": "address"
      }
    ],
    "stateMutability": "view",
    "type": "function",
    "constant": true
  },
  {
    "inputs": [
      {
        "internalType": "uint256",
        "name": "_tokenId",
        "type": "uint256"
      }
    ],
    "name": "ownerOfDigitalID",
    "outputs": [
      {
        "internalType": "address",
        "name": "",
        "type": "address"
      }
    ],
    "stateMutability": "view",
    "type": "function",
    "constant": true
  }
//]';

class EthController extends Controller{
  public function DecetralizeID(){

    $contractABI = '[
      {
        "inputs": [],
        "stateMutability": "nonpayable",
        "type": "constructor"
      },
      {
        "inputs": [],
        "name": "DigitalIDCounter",
        "outputs": [
          {
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      },
      {
        "inputs": [
          {
            "internalType": "address",
            "name": "",
            "type": "address"
          },
          {
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
          }
        ],
        "name": "dictDigitalIDData",
        "outputs": [
          {
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      },
      {
        "inputs": [
          {
            "internalType": "address",
            "name": "",
            "type": "address"
          },
          {
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
          }
        ],
        "name": "dictLogData",
        "outputs": [
          {
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      },
      {
        "inputs": [],
        "name": "logIDCounter",
        "outputs": [
          {
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      },
      {
        "inputs": [
          {
            "internalType": "address",
            "name": "_address_sender",
            "type": "address"
          },
          {
            "internalType": "string",
            "name": "_tokenURI",
            "type": "string"
          }
        ],
        "name": "saveLogs",
        "outputs": [
          {
            "internalType": "address",
            "name": "",
            "type": "address"
          },
          {
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
          }
        ],
        "stateMutability": "nonpayable",
        "type": "function"
      },
      {
        "inputs": [
          {
            "internalType": "address",
            "name": "_address_sender",
            "type": "address"
          },
          {
            "internalType": "string",
            "name": "_tokenURI",
            "type": "string"
          }
        ],
        "name": "saveDigitalIDs",
        "outputs": [
          {
            "internalType": "address",
            "name": "",
            "type": "address"
          },
          {
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
          }
        ],
        "stateMutability": "nonpayable",
        "type": "function"
      },
      {
        "inputs": [
          {
            "internalType": "address",
            "name": "_address",
            "type": "address"
          }
        ],
        "name": "showLogsByOwner",
        "outputs": [
          {
            "internalType": "uint256[]",
            "name": "",
            "type": "uint256[]"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      },
      {
        "inputs": [
          {
            "internalType": "address",
            "name": "_address",
            "type": "address"
          }
        ],
        "name": "showDigitalIDByOwner",
        "outputs": [
          {
            "internalType": "uint256[]",
            "name": "",
            "type": "uint256[]"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      },
      {
        "inputs": [
          {
            "internalType": "uint256",
            "name": "_index",
            "type": "uint256"
          }
        ],
        "name": "showIPFSLogs",
        "outputs": [
          {
            "internalType": "string",
            "name": "",
            "type": "string"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      },
      {
        "inputs": [
          {
            "internalType": "uint256",
            "name": "_index",
            "type": "uint256"
          }
        ],
        "name": "showIPFSDID",
        "outputs": [
          {
            "internalType": "string",
            "name": "",
            "type": "string"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      },
      {
        "inputs": [
          {
            "internalType": "uint256",
            "name": "_tokenId",
            "type": "uint256"
          }
        ],
        "name": "ownerOfLog",
        "outputs": [
          {
            "internalType": "address",
            "name": "",
            "type": "address"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      },
      {
        "inputs": [
          {
            "internalType": "uint256",
            "name": "_tokenId",
            "type": "uint256"
          }
        ],
        "name": "ownerOfDigitalID",
        "outputs": [
          {
            "internalType": "address",
            "name": "",
            "type": "address"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      }
    ]';

    //$contract = new Contract('http://127.0.0.1:7545/', $contractABI);
    $contract = new Contract('https://ropsten.infura.io/v3/a3491ed6ac7a4c3a87a914bbe5a1bbdd', $contractABI); //default metamask

    $contractAddress = "0x22835508BadAA6D26A11327bFcB528d791eAEC5b";
    $fromAccount = "0x9cdc1E3F896dD416660b7359A0bC81EAE5e1b93a"; //accounts[0] -

    //SHOWLOGSBYOWNER
    $contract->at($contractAddress)->call("showLogsByOwner", $fromAccount, function($err,$data) {
      if ($err !== null) {
        echo 'Error: ' . $err->getMessage();
        return;
      }
      echo 'showLogsByOwner : menampilkan id logs berdasar address user :<br>';
      foreach ($data as $dt) {
          foreach($dt as $t) {
            echo $t;
            echo "<br>";
          }
      }

    });

    //SAVELOGS
    $contract->at($contractAddress)->send("saveLogs", $fromAccount, "string_ipfs_url", [
      'from' => $fromAccount,
      'gas' => '0x200b20' //
    ], function($err,$result){
      if ($err !== null) {
        throw $err;
      }

      if ($result) {
        echo "Do saveLogs for user : " . $result . "\n<br>";
      }

    });


    //SHOWIPFSLOGS
    $contract->at($contractAddress)->call("showIPFSLogs", 101, function($err,$data) {
      if ($err !== null) {
        echo 'Error: ' . $err->getMessage();
        return;
      }
      echo 'showIPFSLogs : menampilkan string ipgs berdasar id : <br>';
      echo $data[0];
      echo "<br>";

    });

    //OWNEROFLOG
    $contract->at($contractAddress)->call("ownerOfLog", 101, function($err,$data) {
      if ($err !== null) {
        echo 'Error: ' . $err->getMessage();
        return;
      }
      echo 'ownerOfLog : menampilkan address user yang mempunyai log id tertentu : <br>';
      echo $data[0];
      echo "<br>";

    });


  }

  public function StackEx(){ //EXAMPLE CALL
    //https://ethereum.stackexchange.com/questions/62279/how-to-pass-value-to-contract-via-php-no-java-script

  $contractABI = '[
    {
      "inputs": [],
      "stateMutability": "nonpayable",
      "type": "constructor"
    },
    {
      "inputs": [
        {
          "internalType": "address",
          "name": "",
          "type": "address"
        },
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "name": "dictLogData",
      "outputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [
        {
          "internalType": "address",
          "name": "_address_sender",
          "type": "address"
        }
      ],
      "name": "getNik",
      "outputs": [
        {
          "internalType": "uint256[]",
          "name": "",
          "type": "uint256[]"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [
        {
          "internalType": "address",
          "name": "_address_sender",
          "type": "address"
        },
        {
          "internalType": "uint256",
          "name": "_ids",
          "type": "uint256"
        }
      ],
      "name": "setNik",
      "outputs": [],
      "stateMutability": "nonpayable",
      "type": "function"
    },
    {
      "inputs": [],
      "name": "getNamaLengkap",
      "outputs": [
        {
          "internalType": "string",
          "name": "",
          "type": "string"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [],
      "name": "getStatus",
      "outputs": [
        {
          "internalType": "string",
          "name": "",
          "type": "string"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [
        {
          "internalType": "string",
          "name": "_status",
          "type": "string"
        }
      ],
      "name": "setStatus",
      "outputs": [],
      "stateMutability": "nonpayable",
      "type": "function"
    }
      ]';

    // call contract function

    $contract = new Contract('http://127.0.0.1:7545/', $contractABI);
    $contractAddress = "0x5eCcF4BB1c026b80E2dd4fcFEf00924938a20BF4";

    $contract->at($contractAddress)->call("getNik", "0xC1CC2d14179A1e10934044d452B32b89032B17a5", function($err,$data) {
        if ($err !== null) {
          echo 'Error: ' . $err->getMessage();
          return;
        }

        foreach ($data as $dt) {
            foreach($dt as $t) {
              echo $t;
              echo "<br>";
            }
        }
    });

  }


  public function echoLog() {
    $web3 = new Web3('http://127.0.0.1:7545/');
    $eth = $web3->eth;
    $personal = $web3->personal;

    $eth->accounts(function ($err, $accounts) use ($eth) {
      echo $accounts[0] . ' ';
      $eth->getBalance($accounts[0], function ($err, $balance) {
        echo $balance;
      });
    });
  }
  public function frontpage() {
      $web3 = new Web3('http://127.0.0.1:7545/');
      $eth = $web3->eth;

      //$arrayOfAccount = array();

      $eth->accounts(function ($err, $accounts) use ($eth) {
        if ($err !== null) {
            echo 'Error: ' . $err->getMessage();
            return;
        }
        foreach ($accounts as $account) {
            echo 'Account: ' . $account . PHP_EOL;
            //array_push($arrayOfAccount, $account);

            //$eth->getBalance($account, function ($err, $balance) {
            //    if ($err !== null) {
            //        echo 'Error: ' . $err->getMessage();
            //        return;
            //    }
            //    echo 'Balance: ' . $balance . PHP_EOL;
            //});
        }

        }

      );

      //foreach ($arrayOfAccount as $acc){
      //   echo 'accountqq  '.$acc;
      //}

      //return view('newpage',['all_account' => $array_account] );

      return view('newpage' );
  }

  public function testAbiFunction(){
      $web3 = new Web3('http://127.0.0.1:7545/');

      $testAbi = '[
    {
      "inputs": [],
      "stateMutability": "nonpayable",
      "type": "constructor"
    },
    {
      "inputs": [],
      "name": "getNik",
      "outputs": [
        {
          "internalType": "int256",
          "name": "",
          "type": "int256"
        }
      ],
      "stateMutability": "view",
      "type": "function"
    },
    {
      "inputs": [],
      "name": "getNamaLengkap",
      "outputs": [
        {
          "internalType": "string",
          "name": "",
          "type": "string"
        }
      ],
      "stateMutability": "view",
      "type": "function"
    },
    {
      "inputs": [],
      "name": "getStatus",
      "outputs": [
        {
          "internalType": "string",
          "name": "",
          "type": "string"
        }
      ],
      "stateMutability": "view",
      "type": "function"
    },
    {
      "inputs": [
        {
          "internalType": "string",
          "name": "_status",
          "type": "string"
        }
      ],
      "name": "setStatus",
      "outputs": [],
      "stateMutability": "nonpayable",
      "type": "function"
    }
  ]';
      $testAbibk = '[
        {
          "constant": true,
          "inputs": [],
          "name": "name",
          "outputs": [
            {
              "name": "",
              "type": "string"
            }
          ],
          "payable": false,
          "stateMutability": "view",
          "type": "function"
        },
        {
          "constant": false,
          "inputs": [
            {
              "name": "_spender",
              "type": "address"
            },
            {
              "name": "_value",
              "type": "uint256"
            }
          ],
          "name": "approve",
          "outputs": [
            {
              "name": "success",
              "type": "bool"
            }
          ],
          "payable": false,
          "stateMutability": "nonpayable",
          "type": "function"
        },
        {
          "constant": true,
          "inputs": [],
          "name": "totalSupply",
          "outputs": [
            {
              "name": "",
              "type": "uint256"
            }
          ],
          "payable": false,
          "stateMutability": "view",
          "type": "function"
        },
        {
          "constant": false,
          "inputs": [
            {
              "name": "_from",
              "type": "address"
            },
            {
              "name": "_to",
              "type": "address"
            },
            {
              "name": "_value",
              "type": "uint256"
            }
          ],
          "name": "transferFrom",
          "outputs": [
            {
              "name": "success",
              "type": "bool"
            }
          ],
          "payable": false,
          "stateMutability": "nonpayable",
          "type": "function"
        },
        {
          "constant": true,
          "inputs": [],
          "name": "decimals",
          "outputs": [
            {
              "name": "",
              "type": "uint8"
            }
          ],
          "payable": false,
          "stateMutability": "view",
          "type": "function"
        },
        {
          "constant": true,
          "inputs": [],
          "name": "standard",
          "outputs": [
            {
              "name": "",
              "type": "string"
            }
          ],
          "payable": false,
          "stateMutability": "view",
          "type": "function"
        },
        {
          "constant": true,
          "inputs": [
            {
              "name": "",
              "type": "address"
            }
          ],
          "name": "balanceOf",
          "outputs": [
            {
              "name": "",
              "type": "uint256"
            }
          ],
          "payable": false,
          "stateMutability": "view",
          "type": "function"
        },
        {
          "constant": true,
          "inputs": [],
          "name": "symbol",
          "outputs": [
            {
              "name": "",
              "type": "string"
            }
          ],
          "payable": false,
          "stateMutability": "view",
          "type": "function"
        },
        {
          "constant": false,
          "inputs": [
            {
              "name": "_to",
              "type": "address"
            },
            {
              "name": "_value",
              "type": "uint256"
            }
          ],
          "name": "transfer",
          "outputs": [],
          "payable": false,
          "stateMutability": "nonpayable",
          "type": "function"
        },
        {
          "constant": true,
          "inputs": [
            {
              "name": "",
              "type": "address"
            },
            {
              "name": "",
              "type": "address"
            }
          ],
          "name": "allowance",
          "outputs": [
            {
              "name": "",
              "type": "uint256"
            }
          ],
          "payable": false,
          "stateMutability": "view",
          "type": "function"
        },
        {
          "inputs": [
            {
              "name": "initialSupply",
              "type": "uint256"
            },
            {
              "name": "tokenName",
              "type": "string"
            },
            {
              "name": "decimalUnits",
              "type": "uint8"
            },
            {
              "name": "tokenSymbol",
              "type": "string"
            }
          ],
          "payable": false,
          "stateMutability": "nonpayable",
          "type": "constructor"
        },
        {
          "anonymous": false,
          "inputs": [
            {
              "indexed": true,
              "name": "from",
              "type": "address"
            },
            {
              "indexed": true,
              "name": "to",
              "type": "address"
            },
            {
              "indexed": false,
              "name": "value",
              "type": "uint256"
            }
          ],
          "name": "Transfer",
          "type": "event"
        },
        {
          "anonymous": false,
          "inputs": [
            {
              "indexed": true,
              "name": "_owner",
              "type": "address"
            },
            {
              "indexed": true,
              "name": "_spender",
              "type": "address"
            },
            {
              "indexed": false,
              "name": "_value",
              "type": "uint256"
            }
          ],
          "name": "Approval",
          "type": "event"
        }
    ]';

    /**
     * testBytecode
     * GameToken abi from https://github.com/sc0Vu/GameToken
     *
     * @var string
     */
    $testBytecode = '0x60606040526040805190810160405280600581526020017f45524332300000000000000000000000000000000000000000000000000000008152506000908051906020019061004f92919061012f565b50341561005b57600080fd5b604051610ec5380380610ec58339810160405280805190602001909190805182019190602001805190602001909190805182019190505083600560003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff168152602001908152602001600020819055508360048190555082600190805190602001906100f392919061012f565b50806002908051906020019061010a92919061012f565b5081600360006101000a81548160ff021916908360ff160217905550505050506101d4565b828054600181600116156101000203166002900490600052602060002090601f016020900481019282601f1061017057805160ff191683800117855561019e565b8280016001018555821561019e579182015b8281111561019d578251825591602001919060010190610182565b5b5090506101ab91906101af565b5090565b6101d191905b808211156101cd5760008160009055506001016101b5565b5090565b90565b610ce2806101e36000396000f3006060604052600436106100a4576000357c0100000000000000000000000000000000000000000000000000000000900463ffffffff16806306fdde03146100a9578063095ea7b31461013757806318160ddd1461019157806323b872dd146101ba578063313ce567146102335780635a3b7e421461026257806370a08231146102f057806395d89b411461033d578063a9059cbb146103cb578063dd62ed3e1461040d575b600080fd5b34156100b457600080fd5b6100bc610479565b6040518080602001828103825283818151815260200191508051906020019080838360005b838110156100fc5780820151818401526020810190506100e1565b50505050905090810190601f1680156101295780820380516001836020036101000a031916815260200191505b509250505060405180910390f35b341561014257600080fd5b610177600480803573ffffffffffffffffffffffffffffffffffffffff16906020019091908035906020019091905050610517565b604051808215151515815260200191505060405180910390f35b341561019c57600080fd5b6101a4610609565b6040518082815260200191505060405180910390f35b34156101c557600080fd5b610219600480803573ffffffffffffffffffffffffffffffffffffffff1690602001909190803573ffffffffffffffffffffffffffffffffffffffff1690602001909190803590602001909190505061060f565b604051808215151515815260200191505060405180910390f35b341561023e57600080fd5b61024661092a565b604051808260ff1660ff16815260200191505060405180910390f35b341561026d57600080fd5b61027561093d565b6040518080602001828103825283818151815260200191508051906020019080838360005b838110156102b557808201518184015260208101905061029a565b50505050905090810190601f1680156102e25780820380516001836020036101000a031916815260200191505b509250505060405180910390f35b34156102fb57600080fd5b610327600480803573ffffffffffffffffffffffffffffffffffffffff169060200190919050506109db565b6040518082815260200191505060405180910390f35b341561034857600080fd5b6103506109f3565b6040518080602001828103825283818151815260200191508051906020019080838360005b83811015610390578082015181840152602081019050610375565b50505050905090810190601f1680156103bd5780820380516001836020036101000a031916815260200191505b509250505060405180910390f35b34156103d657600080fd5b61040b600480803573ffffffffffffffffffffffffffffffffffffffff16906020019091908035906020019091905050610a91565b005b341561041857600080fd5b610463600480803573ffffffffffffffffffffffffffffffffffffffff1690602001909190803573ffffffffffffffffffffffffffffffffffffffff16906020019091905050610c91565b6040518082815260200191505060405180910390f35b60018054600181600116156101000203166002900480601f01602080910402602001604051908101604052809291908181526020018280546001816001161561010002031660029004801561050f5780601f106104e45761010080835404028352916020019161050f565b820191906000526020600020905b8154815290600101906020018083116104f257829003601f168201915b505050505081565b600081600660003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200190815260200160002060008573ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff168152602001908152602001600020819055508273ffffffffffffffffffffffffffffffffffffffff163373ffffffffffffffffffffffffffffffffffffffff167f8c5be1e5ebec7d5bd14f71427d1e84f3dd0314c0f7b2291e5b200ac8c7c3b925846040518082815260200191505060405180910390a36001905092915050565b60045481565b6000808373ffffffffffffffffffffffffffffffffffffffff16141561063457600080fd5b81600560008673ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200190815260200160002054101561068057600080fd5b600560008473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020016000205482600560008673ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020016000205401101561070d57600080fd5b600660008573ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200190815260200160002060003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020016000205482111561079657600080fd5b81600560008673ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020016000206000828254039250508190555081600560008573ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020016000206000828254019250508190555081600660008673ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200190815260200160002060003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff168152602001908152602001600020600082825403925050819055508273ffffffffffffffffffffffffffffffffffffffff168473ffffffffffffffffffffffffffffffffffffffff167fddf252ad1be2c89b69c2b068fc378daa952ba7f163c4a11628f55a4df523b3ef846040518082815260200191505060405180910390a3600190509392505050565b600360009054906101000a900460ff1681565b60008054600181600116156101000203166002900480601f0160208091040260200160405190810160405280929190818152602001828054600181600116156101000203166002900480156109d35780601f106109a8576101008083540402835291602001916109d3565b820191906000526020600020905b8154815290600101906020018083116109b657829003601f168201915b505050505081565b60056020528060005260406000206000915090505481565b60028054600181600116156101000203166002900480601f016020809104026020016040519081016040528092919081815260200182805460018160011615610100020316600290048015610a895780601f10610a5e57610100808354040283529160200191610a89565b820191906000526020600020905b815481529060010190602001808311610a6c57829003601f168201915b505050505081565b60008273ffffffffffffffffffffffffffffffffffffffff161415610ab557600080fd5b80600560003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff168152602001908152602001600020541015610b0157600080fd5b600560008373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020016000205481600560008573ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200190815260200160002054011015610b8e57600080fd5b80600560003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020016000206000828254039250508190555080600560008473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff168152602001908152602001600020600082825401925050819055508173ffffffffffffffffffffffffffffffffffffffff163373ffffffffffffffffffffffffffffffffffffffff167fddf252ad1be2c89b69c2b068fc378daa952ba7f163c4a11628f55a4df523b3ef836040518082815260200191505060405180910390a35050565b60066020528160005260406000206020528060005260406000206000915091505054815600a165627a7a723058203eb700b31f6d7723be3f4a0dd07fc4ba166a17279e26a437227679b92bacb5a20029';

      $abiArray = [];
       if (is_string($testAbi)) {
           $abiArray = json_decode($testAbi, true);

           if (JSON_ERROR_NONE !== json_last_error()) {
               throw new InvalidArgumentException('abi decode error: ' . json_last_error_msg());
           }
       } else {
           $abiArray = Utils::jsonToArray($testAbi);
       }

       foreach ($abiArray as $item) {
          if (isset($item['name'])) {
            //echo 'this is '.$item['name'] ;
          }

      }


      $contract = new Contract($web3->provider, $testAbi);

      print_r($contract->getFunctions(),false);
      //print_r($contract->getToAddress(),false);
      //print_r($contract->getEvents(),false);
      //print_r($contract->getConstructor(),false);
      //print_r($contract->getEth(),false);



      $web3->eth->accounts(function ($err, $accounts) use ($contract, $testBytecode) {
          if ($err === null) {
              if (isset($accounts)) {
                  $accounts = $accounts;
              } else {
                  throw new RuntimeException('Please ensure you have access to web3 json rpc provider.');
              }
              $fromAccount = $accounts[0];
              $toAccount = $accounts[1];

              //echo ' '.$fromAccount . ' to ' .$toAccount;
              //print_r($contract->getProperties(), false);
          }
      });


  }

}