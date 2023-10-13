<?php 
namespace App\Repositories;

use Google_Client;
use Google_Service_Drive;
use thiagoalessio\TesseractOCR\TesseractOCR;

class DriveRepository implements DriveInterface
{
    private $client;

    public function __construct()
    {
        // initializationa  and getting valus from env 
        $this->client = new Google_Client();
        $this->client->setClientId(config('app.google.client_id'));
        $this->client->setClientSecret(config('app.google.client_secret'));
        $this->client->setRedirectUri(route('fetch-image-from-drive'));
        $this->client->addScope(Google_Service_Drive::DRIVE_READONLY);
    }

    // authenticating and redirecting 
    public function authenticate($code)
    {
        $this->client->authenticate($code);
        return $this->client->getAccessToken();
    }

    // getting access token 
    public function getAccessToken()
    {
        return session('access_token');
    }
    
    // this fiunction will select all images, my drive had near 2gb iamges so i just selcted all images from a fodler called data
    public function findDataFolder()
    {
        $driveService = new Google_Service_Drive($this->client);
        $folderQuery = "mimeType='application/vnd.google-apps.folder' and name='data'";
        $folders = $driveService->files->listFiles(['q' => $folderQuery, 'fields' => 'files(id, name)']);
        
        // Check if the folder 'data' exists
        if (count($folders->getFiles()) > 0) {
            $folder = $folders->getFiles()[0]; 
            // List all image files inside the 'data' folder
            $imageFilesInFolder = $driveService->files->listFiles([
                'q' => "'" . $folder->getId() . "' in parents and mimeType='image/jpeg' or mimeType='image/png'",
                'fields' => 'files(id, name, webContentLink)'
            ]);
        
            $imageFiles = $imageFilesInFolder->getFiles(); 
            return $imageFiles;
        } else {
            return "Folder 'data' not found.";
        }
    }
    // find iamges and list images which wwill be sent to front end 
    public function findImageFile($folderId)
    {
        $driveService = new Google_Service_Drive($this->client);

        // Search for 'image.jpg' inside the specified folder
        $fileQuery = "name='image.jpg' and '{$folderId}' in parents";
        $files = $driveService->files->listFiles([
            'q' => $fileQuery,
            'spaces' => 'drive',
            'fields' => 'files(id, name, mimeType, webContentLink)',
        ]);

        $file = collect($files->getFiles())->first();

        return $file;
    }

    // get iages content 
    public function getImageContent($imageLink)
    {
        $imageContent = file_get_contents($imageLink);
        $tempImagePath = tempnam(sys_get_temp_dir(), 'ocr') . '.jpg';
        file_put_contents($tempImagePath, $imageContent);
        return $tempImagePath;
    }

    // TesseractOCR lib to extract text 
    public function convertImageToText($tempImagePath)
    {
        $text = (new TesseractOCR($tempImagePath))->run();
        return $text;
    }
}
