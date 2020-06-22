<?php

namespace App\Services;

use Aws\S3\S3Client;
use Aws\S3\ObjectUploader;

class AWS {

	const IMAGE_CDN = 'https://d4ursusm8s4tk.cloudfront.net';

	private $S3Client;
	private $bucket;
	protected static $instance = null;

	public function __construct()
	{
		$this->S3Client = new S3Client([
            'region' => getenv('AWS_REGION'),
            'version' => 'latest'
		]);
		$this->bucket = getenv('AWS_BUCKET');
	}

    public static function uploadToS3($key, $source)
    {
    	$instance = self::getInstance();

    	if($instance->doesFileExistInS3($key)) {
    		throw new \Exception("File exists in S3", 1);
    	}

        $uploader = new ObjectUploader(
            $instance->S3Client,
            $instance->bucket,
            $key,
            $source
        );
        return $uploader->upload();
    }

    public function doesFileExistInS3($key) {
        return $this->S3Client->doesObjectExist($this->bucket, $key);
    }

    public static function getInstance()
    {
        if(self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

	// Temporarily hard coding config values since this is a temporary class
	public function imageLink($fileKey, $imageWidth = null, $additionalOptions = [])
	{
	   $options = [
		   "bucket" => "sellmark-media-bucket",
		   "key" => $fileKey,
		   'edits' => [
			   'resize' => []
		   ]
	   ];
	   if($imageWidth) {
		   $options['edits']['resize'] = [
				   'width' => $imageWidth,
				   'fit' => 'contain'

		   ];
	   }
	   $options = base64_encode(json_encode($options));
	   $imageCDN = self::IMAGE_CDN;
	   return "{$imageCDN}/{$options}";
   }

}
