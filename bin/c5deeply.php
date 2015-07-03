<?php

try {
    if (!class_exists('\C5Deeply\Connector', true)) {
        if (is_file(dirname(__DIR__).'/vendor/autoload.php')) {
            require_once dirname(__DIR__).'/vendor/autoload.php';
        } else {
            throw new Exception('Please run composer.');
        }
    }
    $webRoot = '';
    $optionsDef = array(
        'w:' => 'webroot:',
    );
    foreach (getopt(implode('', array_keys($optionsDef)), array_values($optionsDef)) as $optionKey => $optionValue) {
        switch ($optionKey) {
            case 'w':
            case 'webroot':
                $webRoot = $optionValue;
                break;
        }
    }
    C5Deeply\Connector::startCore($webRoot);
    C5DeeplyCLIMenu();
    die(0);
} catch (Exception $x) {
    echo $x->getMessage();
    die(1);
}

function C5DeeplyCLIMenu()
{
    for (;;) {
        try {
            echo "\n";
            echo "/------------------------\\\n";
            echo "| 1. Search by block ID  |\n";
            echo "|                        |\n";
            echo "| x. Exit                |\n";
            echo "\\------------------------/\n";
            for ($loop = true; $loop;) {
                echo "\n";
                $mainOption = strtolower(C5DeeplyAskLine('Your choice'));
                $loop = false;
                switch ($mainOption) {
                    case '1':
                        $bID = null;
                        for (;;) {
                            $s = strtolower(C5DeeplyAskLine('Block ID'));
                            if ($s === '') {
                                break;
                            }
                            if (!preg_match('/^[1-9][0-9]*$/', $s)) {
                                echo "Invalid block identifier!\n";
                            } else {
                                $bID = (int) $s;
                                break;
                            }
                        }
                        if (isset($bID)) {
                            $analyzer = new C5Deeply\Analyzer();
                            $analyzer->inspectBlock($bID);
                        }
                        break;
                    case 'x':
                        return;
                    default:
                        $loop = true;
                        echo 'Unknown comamnd.';
                }
            }
        } catch (Exception $x) {
            echo "\nERROR!\n", $x->getMessage(), "\n\n";
        }
    }
}

function C5DeeplyAskLine($text = '', $trim = true)
{
    if (is_string($text) && $text !== '') {
        echo $text, ': ';
    }
    $result = @fread(STDIN, 1024);
    @fflush(STDIN);
    if (is_string($result)) {
        if ($trim) {
            $result = trim($result);
        }
    } else {
        $result = '';
    }

    return $result;
}
