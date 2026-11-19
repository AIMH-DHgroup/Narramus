<?php

require('api_helpers.php');
ensure_session_started();
require('PgConn.php');

$context = resolve_narrative_context_from_request(array(
	'method' => 'POST',
	'id_keys' => array('idNarr', 'idNarra', 'id'),
	'subject_keys' => array('subject', 'subjectId'),
));
$slides = post_param('slides', '');
$title = post_param('narrationTitle', '');
$user = $context['storageUser'];
$idNarration = $context['idNarration'];
$baseUrl = post_param('baseURL', '');

if (!is_string($slides) || $slides === '' || strlen($slides) > 50 * 1024 * 1024) {
	json_error_response('Storymap data is missing or exceeds the 50 MB limit.');
}

$storymapData = json_decode($slides);
if (json_last_error() !== JSON_ERROR_NONE
	|| !is_object($storymapData)
	|| !isset($storymapData->slides) || !is_array($storymapData->slides)
	|| !isset($storymapData->items) || !is_object($storymapData->items)
	|| !isset($storymapData->events) || !is_object($storymapData->events)
	|| !isset($storymapData->A1) || !is_object($storymapData->A1)) {
	json_error_response('Storymap data has an invalid format.');
}

if (!is_string($title)) {
	json_error_response('Narrative title is invalid.');
}

if (!preg_match('/^[A-Za-z0-9._-]+$/D', $user) || $user === '.' || $user === '..'
	|| !ctype_digit((string) $idNarration) || (int) $idNarration < 1) {
	json_error_response('Narrative storage path is invalid.', 500);
}

$numericNarrationId = (string) (int) $idNarration;
$tableName = $context['tableName'];
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$triplifyRoot = realpath(__DIR__ . '/../Triplify');
$triplifyJar = $triplifyRoot !== false ? $triplifyRoot . DIRECTORY_SEPARATOR . 'Triplify.jar' : false;

$events = fetch_narrative_events_map($tableName);
$entities = fetch_narrative_entities_map($tableName);
$info = fetch_narrative_info_object($tableName);

if (isset($_SESSION['Demon_on'])) {
	$datasetFs = 'https://tool.dlnarratives.eu/fuseki/prova2';
	$datasetName = 'prova2';
	$storageSubdir = 'prova2';
} elseif (isset($info->CSVnumber)) {
	$datasetFs = 'https://tool.dlnarratives.eu/fuseki/moving';
	$datasetName = 'moving';
	$storageSubdir = 'moving';
} else {
	$datasetFs = 'http://127.0.0.1:3030/narratives';
	$datasetName = 'narratives';
	$storageSubdir = 'narratives';
}

$triplifyPayload = array(
	'entities' => $entities,
	'narra' => $info,
	'events' => $events,
);

$jsonDir = json_storage_dir($storageSubdir);
$owlDir = owl_storage_dir($storageSubdir);

foreach (array(
	'JSON' => $jsonDir,
	'OWL' => $owlDir,
) as $outputType => $outputDir) {
	if (!is_dir($outputDir) || !is_writable($outputDir)) {
		json_error_response(
			'The ' . $outputType . ' output directory is not writable by the web server.',
			500,
			array('path' => $outputDir)
		);
	}
}

$jsonPath = $jsonDir . DIRECTORY_SEPARATOR . $user . '_N' . $idNarration . '.json';
$owlPath = $owlDir . DIRECTORY_SEPARATOR . $user . '_N' . $idNarration . '.owl';

$jsonContent = json_encode($triplifyPayload);
if ($jsonContent === false) {
	json_error_response('Unable to encode narrative data for graph generation: ' . json_last_error_msg(), 500);
}

$jsonBytesWritten = file_put_contents($jsonPath, $jsonContent);
if ($jsonBytesWritten === false || $jsonBytesWritten !== strlen($jsonContent) || !is_readable($jsonPath)) {
	json_error_response('Unable to write the triplification JSON input file.', 500, array('path' => $jsonPath));
}
chmod($jsonPath, 0777);

$output = array();
$retval = null;
if ($triplifyRoot !== false && $triplifyJar !== false && is_file($triplifyJar) && is_readable($triplifyJar)) {
    $command = 'cd ' . escapeshellarg($triplifyRoot) . ' && ' .
            'java -Xmx512m -cp ' .
            escapeshellarg($triplifyRoot . PATH_SEPARATOR . $triplifyJar) .
            ' narra.triplifier.Triplify ' .
            escapeshellarg($jsonPath) . ' ' .
            escapeshellarg($owlPath) . ' ' .
            escapeshellarg($datasetFs) . ' 2>&1';

    exec($command, $output, $retval);
    if (file_exists($owlPath)) {
        chmod($owlPath, 0777);
    } elseif ($retval === 0) {
        $output[] = 'Triplifier completed without creating the OWL output file';
        $retval = 1;
    }
} else {
    $output[] = 'Triplify JAR not found or not readable: ' . ($triplifyJar === false ? __DIR__ . '/../Triplify/Triplify.jar' : $triplifyJar);
    $retval = 1;
}

$appRoot = app_root_path();
if ($appRoot === false) {
    json_error_response('Unable to resolve the application directory.', 500);
}

$storyRoot = ensure_directory($appRoot . DIRECTORY_SEPARATOR . 'storymaps');
$storyBaseDir = ensure_directory($storyRoot . DIRECTORY_SEPARATOR . $user);
$storyDir = ensure_directory($storyBaseDir . DIRECTORY_SEPARATOR . 'N' . $numericNarrationId);
foreach (array($storyRoot, $storyBaseDir, $storyDir) as $directory) {
    if (!is_dir($directory) || !is_writable($directory)) {
        json_error_response('The storymap output directory is not writable by the web server.', 500, array('path' => $directory));
    }
}

$slideJsonPath = $storyDir . DIRECTORY_SEPARATOR . 'slide.json';
$jsonBytesWritten = file_put_contents($slideJsonPath, $slides, LOCK_EX);
if ($jsonBytesWritten === false || $jsonBytesWritten !== strlen($slides) || !is_readable($slideJsonPath)) {
    json_error_response('Unable to write the published storymap JSON.', 500, array('path' => $slideJsonPath));
}

$subjectNarration = post_param('subject', $context['subjectId']);
if (!is_string($subjectNarration)) {
    $subjectNarration = $context['subjectId'];
}
$title = $numericNarrationId . '-' . $subjectNarration;
$titleHtml = htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

$searchLink = '../../../Search/?user=' . rawurlencode($user) . '&id=N' . rawurlencode($numericNarrationId) . '&dataset=' . rawurlencode($datasetName);
$visualizationLinks = array(
    '<a href="?visualization=map">Storymap</a>',
    '<a href="?visualization=timeline">Timeline</a>',
);
if (!isset($info->CSVnumber)) {
    $visualizationLinks[] = '<a href="../../../Entity_With_Events/?user=' . rawurlencode($user) . '&idNarra=N' . rawurlencode($numericNarrationId) . '&dataset=' . rawurlencode($datasetName) . '">Entity With Related Events</a>';
}

$storyIndexHtml = '<html>
<head>
    <meta charset="UTF-8">
    <title>Narrative - ' . $titleHtml . '</title>
    <link rel="stylesheet" type="text/css" href="../../../lib/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../../../lib/narra.css" />
    <script src="../../../lib/jquery-3.2.1.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="../../../lib/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="../../../lib/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>

    <script src="../../../lib/typeahead.bundle.min.js" type="text/javascript" charset="utf-8"></script>

    <script src="../../lib/visualization.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="https://cdn.knightlab.com/libs/storymapjs/0.8.6/css/storymap.css">
    <script type="text/javascript" src="../../../lib/storymap.js"></script>
    <link rel="stylesheet" type="text/css" href="../../../lib/timeline.css" />
    <script src="../../../lib/timeline-min.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="../../lib/narrativeVisualization.css" />
</head>
<body>
        <div id="menu">
            <div id="titleTable">
                <h1>' . $titleHtml . '</h1>
            </div>
            <div class="otherVisual">
                <button class="dropbtn">Other Visualizations</button>
                <div class="otherVisual-content">
                    ' . implode("\n\t\t\t", $visualizationLinks) . '
                </div>
            </div>
            <a href="' . htmlspecialchars($searchLink, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '">
                <div class="otherNarratives">
                    <button class="dropbtn">Search</button>
                </div>
            </a>
        </div>
        <div id="mapdiv"></div>
</body>
</html>';

$indexPath = $storyDir . DIRECTORY_SEPARATOR . 'index.html';
$htmlBytesWritten = file_put_contents($indexPath, $storyIndexHtml, LOCK_EX);
if ($htmlBytesWritten === false || $htmlBytesWritten !== strlen($storyIndexHtml) || !is_readable($indexPath)) {
    json_error_response('Unable to write the published storymap page.', 500, array('path' => $indexPath));
}

$owlLink = $retval === 0
	? $scheme . '://' . $baseUrl . '/owl/' . rawurlencode($storageSubdir) . '/' . rawurlencode($user . '_N' . $idNarration . '.owl')
	: null;
$scriptName = str_replace('\\', '/', isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/PHP/publishAndTriplifyNarration.php');
$applicationBasePath = dirname(dirname($scriptName));
if ($applicationBasePath === '.' || $applicationBasePath === DIRECTORY_SEPARATOR) {
	$applicationBasePath = '';
}
$applicationBasePath = rtrim($applicationBasePath, '/');
$verticalStorymapPath = $applicationBasePath . '/verticalstorymap3D/index.php';
$verticalStorymapLink = $scheme . '://' . $baseUrl . $verticalStorymapPath .
	'?user=' . rawurlencode($user) . '&id=' . rawurlencode('N' . $numericNarrationId);

json_response(array(
	'owlLink' => $owlLink,
	'storymapLink' => $verticalStorymapLink,
	'output' => $output,
	'exitCode' => $retval,
));
