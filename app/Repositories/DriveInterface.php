<?php
namespace App\Repositories;

interface DriveInterface
{
    public function authenticate($code);

    public function getAccessToken();

    public function findDataFolder();

    public function findImageFile($folderId);

    public function getImageContent($imageLink);

    public function convertImageToText($imageLink);
}
