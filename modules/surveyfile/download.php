<?php

/** @var eZModule $Module */
$Module = $Params['Module'];
$EncodedFilePath =  $Params['EncodedFilePath'];
$SurveyID = $Params['SurveyID'];

$filePath = str_replace(':', '/', $EncodedFilePath);

$file = eZClusterFileHandler::instance($filePath);
if (!$file->exists()){
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel');
}else{
    //handle download
    $file->fetch();
    eZFile::download( $filePath, true, basename( $filePath ) );
}

eZDisplayDebug();
eZExecution::cleanExit();
