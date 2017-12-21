<?php
require 'autoload.php';

$script = eZScript::instance( array( 'description' => ( 'Clusterize survey file' ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions();
$script->initialize();
$script->setUseDebugAccumulators( true );

$cli = eZCLI::instance();

try
{
    $fileHandler = eZClusterFileHandler::instance();
    if ( !is_object( $fileHandler ) )
    {
        throw new Exception( "Clustering settings specified incorrectly or the chosen file handler is ezfs." );
    }
    // the script will only run if clusterizing is supported by the currently
    // configured handler
    elseif ( !$fileHandler->requiresClusterizing() )
    {
        $message = "The current cluster handler (" . get_class( $fileHandler ) . ") " .
                   "doesn't require/support running this script";
        throw new Exception( $message );
    }

    $db = eZDB::instance();
    $rows = $db->arrayQuery("SELECT text FROM ezsurveyquestionresult WHERE question_id IN (SELECT id FROM ezsurveyquestion WHERE type = 'File')");
    foreach($rows as $row){
        $filePath = $row['text'];
        if (file_exists($filePath)){
            $mimeData = eZMimeType::findByFileContents( $filePath );
            $fileHandler->fileStore( $filePath, 'surveyfile', false, $mimeData['name'] );
        }
    }
    $script->shutdown();
}
catch( Exception $e )
{
    $errCode = $e->getCode();
    $errCode = $errCode != 0 ? $errCode : 1; // If an error has occured, script must terminate with a status other than 0
    $script->shutdown( $errCode, $e->getMessage() );
}
