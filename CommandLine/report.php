<?php
require_once __DIR__ . '/../vendor/autoload.php';

$xml = 'doc/structure.xml';

// ./vendor/bin/phpdoc --quiet -d /tmp/typo3-extension-sync --target doc --force --no-ansi --template xml

if (is_readable($xml)) {
    // Generate document body
    $body = new \SchamsNet\DocErrorReport\View\Render();
    $body->setTemplatePathAndFilename(__DIR__ . '/../Resources/Private/Templates/Web/Report.html');

    $data = [];
    try {
        $totalErrorCount = 0;
        $project = new \SimpleXMLElement(file_get_contents($xml));
        foreach ($project->file as $file) {
            $errorCount = 0;
            $filename = $file['path']->__toString();
            $data[md5($filename)] = [
                'id' => md5($filename),
                'filename' => $filename,
                'classes' => []
            ];
            if (isset($file->class)) {
                foreach ($file->class as $class) {
                    $data[md5($filename)]['classes'][] = [
                        'namespace' => $class['namespace']->__toString(),
                        'fullname' => $class->full_name->__toString(),
                    ];
                }
            }
            if (isset($file->parse_markers)) {
                $parse_markers = $file->parse_markers;
                if (isset($parse_markers->error)) {
                    foreach ($parse_markers->error as $error) {
                        if ($error['code']->__toString() != 'PPC:ERR-50000') {
                            $data[md5($filename)]['errors'][] = [
                                'lineNumber' => $error['line']->__toString(),
                                'description' => $error->__toString(),
                                'code' => $error['code']->__toString(),
                            ];
                            $errorCount++;
                        }
                    }
                }
            }
            $data[md5($filename)]['error_count'] = $errorCount;
            $totalErrorCount = $totalErrorCount + $errorCount;
            if ($errorCount == 0) {
                unset($data[md5($filename)]);
            }
        }
        if (count($data) > 0) {
            $body->assign('totalErrorCount', $totalErrorCount);
            $body->assign('data', $data);
        }

        // ...
        \SchamsNet\DocErrorReport\Utility\FileSystemUtility::copyDirectoryRecursively(
            __DIR__ . '/../html/assets',
            '/tmp/assets/'
        );
        $content = $body->render();
        file_put_contents('/tmp/content.html', $content);
    } catch (Exception $e) {
        echo "Error: bad XML data.";
    }
}
