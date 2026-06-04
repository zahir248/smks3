<?php

function uploadImage($file, $path){

    if(!$file || !isset($file['tmp_name'])){
        return false;
    }

    if(!is_dir($path)){
        mkdir($path, 0755, true);
    }

    if($file['error'] != 0){
        return false;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp'];

    if(!in_array($ext,$allowed)){
        return false;
    }

    // MIME check
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowedMime = ['image/jpeg','image/png','image/webp'];

    if(!in_array($mime, $allowedMime)){
        return false;
    }

    $path = rtrim($path, '/') . '/';

    $newName = time().'_'.rand(1000,9999).'.'.$ext;

    if(move_uploaded_file($file['tmp_name'], $path.$newName)){
        return $newName;
    }

    return false;
}