<?php

$Module = array('name' => 'surveyfile');

$ViewList = array();
$ViewList['download'] = array(
    'script' => 'download.php',
    'params' => array('SurveyID', 'EncodedFilePath')
);
