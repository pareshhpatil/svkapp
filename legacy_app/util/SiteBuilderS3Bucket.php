<?php

use Aws\S3\S3Client;
use Aws\S3\ObjectUploader;
use Aws\S3;
use Aws\Exception\MultipartUploadException;

class SiteBuilderS3Bucket
{

    protected $key = NULL;
    protected $secret = NULL;
    private $s3client = null;

    function __construct($region = 'us-east-1')
    {
        $this->key = env('S3KEY');
        $this->secret = env('S3SECRET');
        $this->s3client = new S3Client([
            'region' => $region,
            'version' => 'latest',
            'credentials' => [
                'key' => $this->key,
                'secret' => $this->secret,
            ]
        ]);
    }

    function createBucket($bucket)
    {
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
                    )
                ]
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
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__ . '/' . __FUNCTION__, 'Error ' . $e->getMessage());
        }
    }

    function putFile($bucket, $keyname, $filepath)
    {
        try {

            // Using stream instead of file path
            $source = fopen($filepath, 'rb');
            $params = [
                'ContentType' => 'application/pdf'
            ];
            $uploader = new ObjectUploader(
                $this->s3client,
                $bucket,
                $keyname,
                $source,
                $params
            );
            try {
                $result = $uploader->upload();
                if ($result["@metadata"]["statusCode"] == '200') {
                    return $result["ObjectURL"];
                }
            } catch (MultipartUploadException $e) {

                SwipezLogger::error(__CLASS__ . '/' . __FUNCTION__, 'Error ' . $e->getMessage());
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__ . '/' . __FUNCTION__, 'Error ' . $e->getMessage());
        }
    }

    function putBucketFile($bucket, $keyname, $text, $ext = '')
    {
        try {
            if (strtolower($ext) == 'pdf') {
                $ContentType = 'application/pdf';
            } else  if (strtolower($ext) == 'html') {
                $ContentType = 'text/html';
            } else {
                $ContentType = 'image/jpeg';
            }
            $result = $this->s3client->putObject(array(
                'Bucket' => $bucket,
                'Key' => $keyname,
                'Body' => $text,
                'ContentType' => $ContentType,
                'ACL' => 'public-read',
                'Metadata' => array(
                    'param1' => 'value 1',
                    'param2' => 'value 2'
                )
            ));
            return $result['ObjectURL'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__ . '/' . __FUNCTION__, 'Error ' . $e->getMessage());
        }
    }

    function putBucket($bucket, $keyname, $filepath, $ext = '')
    {
        try {
            if (strtolower($ext) == 'pdf') {
                $ContentType = 'application/pdf';
            } else  if (strtolower($ext) == 'html') {
                $ContentType = 'text/html';
            } else {
                $ContentType = 'image/jpeg';
            }
            $result = $this->s3client->putObject(array(
                'Bucket' => $bucket,
                'Key' => $keyname,
                'SourceFile' => $filepath,
                'ContentType' => $ContentType,
                'ACL' => 'public-read',
                'Metadata' => array(
                    'param1' => 'value 1',
                    'param2' => 'value 2'
                )
            ));
            return $result['ObjectURL'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__ . '/' . __FUNCTION__, 'Error ' . $e->getMessage());
        }
    }

    function deleteBucketfile($bucket, $key)
    {
        try {
            $this->s3client->deleteMatchingObjects($bucket, $key);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__ . '/' . __FUNCTION__, 'Error ' . $e->getMessage());
        }
        //return $result['ObjectURL'];
    }

    function copyBucketfile($target_bucket, $target_path, $source_path)
    {
        try {
            $this->s3client->copyObject(array(
                'Bucket' => $target_bucket,
                'Key' => $target_path,
                'CopySource' => $source_path,
            ));
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__ . '/' . __FUNCTION__, 'Error ' . $e->getMessage());
        }
    }

    function getBucketinfo($bucket)
    {
        try {
            $result = $this->s3client->headBucket(array('Bucket' => $bucket));
            return $result;
        } catch (Exception $e) {
        }
    }

    function getBucketdetails($bucket)
    {
        try {
            $result = $this->s3client->getObject(array('Bucket' => $bucket, 'Key' => ''));
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__ . '/' . __FUNCTION__, 'Error ' . $e->getMessage());
        }
    }

    function isValidBucketName($bucket)
    {
        return true;
    }

    function uploadZiptoS3($zippath_, $zipname_, $folder = NULL)
    {
        try {
            $s3Obj = new S3($this->key, $this->secret);
            $file_name = $zipname_ . '_' . time();
            if ($s3Obj->putObject(S3::inputFile($zippath_, true), 'swipez.form-builder', $folder . '/' . $file_name)) {
            } else {
                return '';
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__ . '/' . __FUNCTION__, 'Error ' . $e->getMessage());
        }
    }
}
