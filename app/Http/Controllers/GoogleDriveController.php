<?php
namespace App\Http\Controllers;

use App\Repositories\DriveInterface;
use App\Repositories\DriveRepository;
use Google_Client;
use Google_Service_Drive;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;

class GoogleDriveController extends Controller
{
    private $driveRepository;

    public function __construct(DriveInterface $driveRepository)
    {
        // initiliazing google client
        $this->driveRepository = $driveRepository;
        $this->client = new Google_Client();
        $this->client->setClientId(config('app.google.client_id'));
        $this->client->setClientSecret(config('app.google.client_secret'));
        $this->client->setRedirectUri(route('fetch-image-from-drive'));
        $this->client->addScope(Google_Service_Drive::DRIVE_READONLY);
    }
    // index page 
    public function index()
    {
        return view('welcome');
    }

    // integrate drive and url redirect mangemnt 
    public function integrate()
    {
        $authUrl = $this->client->createAuthUrl();
        return redirect($authUrl);
    }

    // redirected url fucniotn which gets images frm drive 
    public function fetchImage()
    {
        if (request()->has('code')) {
            $accessToken = $this->driveRepository->authenticate(request()->get('code'));
            session(['access_token' => $accessToken]);
        }

        if ($this->driveRepository->getAccessToken()) {
            $folder = $this->driveRepository->findDataFolder();
            if (!$folder) {
                return 'Data folder not found';
            }
            return view('welcome', ['imageFiles' => $folder]); 
        } else {
            return 'Authentication failed';
        }
    }

    // convert image =to text and write in word file. 
    public function imageConverter(Request $request)
    {
        // Get the selected image URLs from the request
        $imageLinks = $request->input('selectedImages');
    
        // Initialize PHPWord
        $phpWord = new PhpWord(); 
        $section = $phpWord->addSection(); 
        $table = $section->addTable();
    
        // Add header row
        $table->addRow();
        $table->addCell(2000)->addText('Text');  
        $table->addCell(2000)->addText('Image'); 
    
        // Loop through each selected image URL
        foreach ($imageLinks as $imageLink) {
            // Get the image content and convert it to text
            $tempImagePath = $this->driveRepository->getImageContent($imageLink);
            $text = $this->driveRepository->convertImageToText($tempImagePath);
    
            // Add data row for each image
            $table->addRow();
            $table->addCell(5000)->addText($text); 
    
            // Data for the image column
            $imageCell = $table->addCell(2000);
            $imageCell->addImage($tempImagePath); 
        }
    
        // Save the document
        $filePath = storage_path('app/public/myDocument.docx');
        $phpWord->save($filePath, 'Word2007');
    
        // Download the document as a response
        return response()->download($filePath);
    }
    

}
