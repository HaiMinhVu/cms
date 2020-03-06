<?php 

namespace App\Services;

use Aws\S3\S3Client;
use Aws\S3\ObjectUploader;

class AWS {

	private $S3Client;
	private $bucket;
	protected static $instance = null;

	public function __construct()
	{
		$this->S3Client = new S3Client([
			// 'profile' => 'default',
            'region' => getenv('AWS_REGION'),
            'version' => 'latest'
		]);
		$this->bucket = getenv('AWS_BUCKET');
	}

    public static function uploadToS3($key, $source)
    {
    	$instance = self::getInstance();

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

}
