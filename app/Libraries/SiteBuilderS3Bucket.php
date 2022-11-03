<?php

namespace App\Libraries;

use Aws\S3\S3Client;
use Aws\S3\ObjectUploader;
use Aws\S3;
use Aws\Exception\MultipartUploadException;
use Log;

class SiteBuilderS3Bucket {

    protected $key = NULL;
    protected $secret = NULL;
    private $s3client = null;

    function __construct($region = 'ap-south-1',$key=null,$secret=null) {
        if($key==null)
        {
            $this->key = env('S3KEY');
            $this->secret = env('S3SECRET');
        }else
        {
            $this->key = $key;
            $this->secret = $secret;
        }
        
        $this->s3client = new S3Client([
            'region' => $region,
            'version' => 'latest',
            'credentials' => [
                'key' => $this->key,
                'secret' => $this->secret,
            ]
        ]);
    }

    function createBucket($bucket) {
        try {

            $this->s3client->createBucket([
                'Bucket' => $bucket,
            ]);
            $this->s3client->putBucketPolicy(array(
                'Bucket' => $bucket,
                'Policy' => '{
                        "Version" : "2012-10-17",
                        "Statement" : [{
                                "Sid" : "AddPerm",
                                "Effect" : "Allow",
                                "Principal" : "*",
                                "Action" : "s3:GetObject",
                                "Resource" : "arn:aws:s3:::' . $bucket . '/*"
                        }]}'
            ));
            $this->s3client->waitUntil('BucketExists', array('Bucket' => $bucket));
            $this->s3client->putBucketCors(array(
                'Bucket' => $bucket,
                'CORSConfiguration' => [
                    'CORSRules' => array(
                        array(
                            'AllowedHeaders' => array('Authorization'),
                            'AllowedMethods' => array('GET'),
                            'AllowedOrigins' => array('*'),
                            'MaxAgeSeconds' => 3000,
                        ),
                    )]
            ));

            $result = $this->s3client->putBucketWebsite([
                'Bucket' => $bucket,
                'WebsiteConfiguration' => [
                    'ErrorDocument' => [
                        'Key' => 'error.html',
                    ],
                    'IndexDocument' => [
                        'Suffix' => 'index.html',
                    ],
                ],
            ]);


            return $result;
        } catch (Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . 'Error ' . $e->getMessage());
        }
    }

    function putFile($bucket, $keyname, $filepath) {
        try {

            // Using stream instead of file path
            $source = fopen($filepath, 'rb');
            $uploader = new ObjectUploader(
                    $this->s3client, $bucket, $keyname, $source
            );
            try {
                $result = $uploader->upload();
                if ($result["@metadata"]["statusCode"] == '200') {
                    return $result["ObjectURL"];
                }
            } catch (MultipartUploadException $e) {
                Log::error(__CLASS__ . '/' . __FUNCTION__ . 'Error ' . $e->getMessage());
            }
        } catch (Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . 'Error ' . $e->getMessage());
        }
    }

    function putBucket($bucket, $keyname, $filepath) {
        try {
            $result = $this->s3client->putObject(array(
                'Bucket' => $bucket,
                'Key' => $keyname,
                'Body' => $filepath,
                'ContentType' => 'text/html',
                'ACL' => 'public-read',
                'Metadata' => array(
                    'param1' => 'value 1',
                    'param2' => 'value 2'
                )
            ));

            return $result['ObjectURL'];
        } catch (Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . 'Error ' . $e->getMessage());
        }
    }

    function deleteBucketfile($bucket, $key) {
        try {
            $this->s3client->deleteMatchingObjects($bucket, $key);
        } catch (Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . 'Error ' . $e->getMessage());
        }
        //return $result['ObjectURL'];
    }

    function copyBucketfile($target_bucket, $target_path, $source_path) {
        try {
            $this->s3client->copyObject(array(
                'Bucket' => $target_bucket,
                'Key' => $target_path,
                'CopySource' => $source_path,
            ));
        } catch (Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . 'Error ' . $e->getMessage());
        }
    }
    function getFile($bucket, $source_path) {
        try {

           // dd($source_path);
            $result = $this->s3client->getObject(array(
                'Bucket' => $bucket,
                'Key'    => '2019-10-15.txt',
                'SaveAs' => 'data.txt'
            ));
            header("Content-Type: {$result['ContentType']}");
            dd($result['Body']);

            

        } catch (Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . 'Error ' . $e->getMessage());
        }
    }

    function getBucketinfo($bucket) {
        try {
            $result = $this->s3client->headBucket(array('Bucket' => $bucket));
            return $result;
        } catch (Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . 'Error ' . $e->getMessage());
        }
    }

    function getBucketdetails($bucket) {
        try {
            $result = $this->s3client->getObject(array('Bucket' => $bucket, 'Key' => ''));
            return $result;
        } catch (Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . 'Error ' . $e->getMessage());
        }
    }

    function isValidBucketName($bucket) {
        return true;
    }

    function uploadZiptoS3($zippath_, $zipname_, $folder = NULL) {
        try {
            $s3Obj = new S3($this->key, $this->secret);
            $file_name = $zipname_ . '_' . time();
            if ($s3Obj->putObject(S3::inputFile($zippath_, true), 'swipez.form-builder', $folder . '/' . $file_name)) {
                
            } else {
                return '';
            }
        } catch (Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . 'Error ' . $e->getMessage());
        }
    }

}
