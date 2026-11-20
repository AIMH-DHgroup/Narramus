<?php

require('api_helpers.php');
ensure_session_started();
require('PgConn.php');
require_login_user();

$slides = post_param('slides', '');
$context = resolve_narrative_context_from_request(array(
	'method' => 'POST',
	'id_keys' => array('idNarr', 'idNarra', 'id'),
	'subject_keys' => array('subject', 'subjectId'),
));
$user = $context['storageUser'];
$idNarration = $context['idNarration'];

$tempDir = app_root_path() . DIRECTORY_SEPARATOR . 'TempJson';
if (!is_dir($tempDir) && !@mkdir($tempDir, 0775, true) && !is_dir($tempDir)) {
	json_error_response('Unable to create the temporary preview directory. Check its parent directory permissions.', 500, array(
		'path' => $tempDir,
	));
}
if (!is_writable($tempDir)) {
	json_error_response('The temporary preview directory is not writable by the web server.', 500, array(
		'path' => $tempDir,
	));
}

if (!is_string($slides) || trim($slides) === '') {
	json_error_response('The preview data is missing or is not valid JSON.', 400);
}
json_decode($slides);
if (json_last_error() !== JSON_ERROR_NONE) {
	json_error_response('The preview data is missing or is not valid JSON.', 400);
}

$fileName = $user . '-N' . $idNarration . '-slide.json';
$filePath = $tempDir . DIRECTORY_SEPARATOR . $fileName;

$bytesWritten = file_put_contents($filePath, $slides, LOCK_EX);
if ($bytesWritten === false || $bytesWritten !== strlen($slides) || !is_readable($filePath)) {
	json_error_response('Unable to write the temporary preview file. Check the directory and file permissions.', 500, array(
		'path' => $filePath,
	));
}

json_response(array('url' => 'TempJson/' . $fileName));
