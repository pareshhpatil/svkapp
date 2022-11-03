<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Firebase\JWT\JWT;
use SimpleJWT;

class JWTValidation extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twt:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'JWT token validation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        try {
            
            $token = 'eyJlbmMiOiJBMTI4R0NNIiwicDJzIjoiNzFyMEFaUUYyV1VrLXFzYmJOOG1hdyIsImFsZyI6IlBCRVMyLUhTMjU2K0ExMjhLVyIsInAyYyI6NTAwMH0.nUptbac5YLUCxudvmpnnpICIoIa2_Jsl.jfP_KislSABQvMb9YilQNw.WM-TSaZuwnPv5nWQTxhBN8NycYlxH4m8my0q6CNsjZTdSaPrch01nI4b-Z6M_xj-dMFSG8x5-aj3mHY.7K67-FpaZjF_JQMfnaBaQw';
            $key = env('JWT_SECRET_KEY');

            $set = SimpleJWT\Keys\KeySet::createFromSecret($key);

            try {
                $jwt = SimpleJWT\JWE::decrypt($token, $set, 'PBES2-HS256+A128KW');
            } catch (SimpleJWT\InvalidTokenException $e) {
                echo $e->getMessage();
            }

            print $jwt->getHeader('alg');
            print $jwt->getPlaintext();

            die();
            $decoded = JWT::decode($token, $key, array('PBES2-HS256+A128KW'));
            $array = json_decode(json_encode($decoded), 1);
            echo 'JWT verified successfully. ';
            print_r($array);
            die();
        } catch (Exception $e) {
            echo 'JWT verification faled.<br> Invalid Token' . $e->getMessage();
            die();
        }
    }

}
