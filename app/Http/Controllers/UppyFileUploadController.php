<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UppyFileUploadController extends Controller
{
    public function uploadImage(Request $request, $type = null, $subfolder = null)
    {
        $response['errors'] = '';
        $response['status'] = 300;
        $response['filename'] = '';
        $folder = 'products';
        $fileExtensionsAllowed = ['jpeg', 'png', 'jpg', 'gif'];
        if ($type == 'invoice') {
            $fileExtensionsAllowed = ['jpeg', 'png', 'jpg', 'gif', 'pdf'];
            $folder = 'invoices';
        }

        $product_base_url = 'https://s3.' . env('S3REGION') . '.amazonaws.com/' . env('S3BUCKET_EXPENSE') . '/' . $folder . '/';

        $dt = time();
        $randNo = rand(1111, 9999);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = $file->getClientOriginalName();
            $name = substr($name, 0, strrpos($name, '.'));
            if($subfolder=='billcode')
            {
                $data=explode("_", $name);
               $folder=$folder.'/'.$data[0];
               $name=str_replace($data[0].'_',"",$name);
               $product_base_url = 'https://s3.' . env('S3REGION') . '.amazonaws.com/' . env('S3BUCKET_EXPENSE') . '/' . $folder . '/';
            }
            //$encryptedFileName = Encrypt::encode($name);
            $filenameExt =str_replace('.','',$name).$randNo;//$dt . $randNo;
            $filenameExt =str_replace('(','',$filenameExt);
            $filenameExt =str_replace(')','',$filenameExt);
            $encryptedFileName =$filenameExt; //Encrypt::encode($filenameExt);
            //get file extension
            $fileExtension = $file->getClientOriginalExtension();
            //get file size
            $fileSize = $file->getSize();
            $encryptedFileNameExt = $encryptedFileName . '.' . $fileExtension;

            $filePath = $folder . '/' . $encryptedFileNameExt;

            if (!in_array($fileExtension, $fileExtensionsAllowed)) {
                $response['errors'] = $fileExtension . " file extension is not allowed. Please upload a JPEG or PNG file";
            }
            if($subfolder!='billcode'){
            if ($fileSize > 1000000) {
                $response['errors'] = "File exceeds maximum size (1MB)";
            }
        }

            if (empty($response['errors'])) {
                $uploadImg = Storage::disk('s3_expense')->put($filePath, file_get_contents($file));

                if ($uploadImg) {
                    $response['status'] = 200;
                    $response['filename'] = $encryptedFileNameExt;
                    $response['fileUploadPath'] = $product_base_url . $encryptedFileNameExt;
                } else {
                    $response['status'] = 300;
                    $response['errors'] = "Failed to upload file on s3 bucket";
                }
            }
        } else {
            $response['errors'] = "An error occurred. Please contact the administrator.";
        }
        echo json_encode($response);
    }

    public function uploadWCProductImage($filePath = null, $wc_post_id = null)
    {
        $response['errors'] = '';
        $response['status'] = 300;
        $response['filename'] = '';
        $fileExtensionsAllowed = ['jpeg', 'png', 'jpg', 'gif'];
        $product_base_url = 'https://s3.' . env('S3REGION') . '.amazonaws.com/' . env('S3BUCKET_EXPENSE') . '/products/';

        if ($filePath != null) {
            $path_parts = pathinfo($filePath);
            $filenameExt = $wc_post_id . '-' . $path_parts['filename'];
            $encryptedFileName = Encrypt::encode($filenameExt);

            $encryptedFileNameExt = $encryptedFileName . '.' . $path_parts['extension'];

            $fileLocation = 'products/' . $encryptedFileNameExt;
            //get file size
            $uploadImg = Storage::disk('s3_expense')->put($fileLocation, file_get_contents($filePath));
            if ($uploadImg) {
                $response['status'] = 200;
                $response['filename'] = $encryptedFileNameExt;
                $response['fileUploadPath'] = $product_base_url . $encryptedFileNameExt;
            } else {
                $response['status'] = 300;
                $response['errors'] = "Failed to upload file on s3 bucket";
            }
        }
        return $response;
    }
}
