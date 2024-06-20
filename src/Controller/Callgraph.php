<?php
/**
 * @file Calgraph utils.
 */

namespace Xhprof\Controller;

/**
 * @class Callgraph.
 */
class Callgraph
{

    // Supported ouput format
    private $xhprof_legal_image_types = array(
        "jpg" => 1,
        "gif" => 1,
        "png" => 1,
        "ps" => 1,
        "svg" => 1,
    );

    public function getLegalImageTypes()
    {
        return $this->xhprof_legal_image_types;
    }


    /**
     * Send an HTTP header with the response. You MUST use this function instead
     * of header() so that we can debug header issues because they're virtually
     * impossible to debug otherwise. If you try to commit header(), SVN will
     * reject your commit.
     *
     * @param string  HTTP header name, like 'Location'
     * @param string  HTTP header value, like 'http://www.example.com/'
     *
     */
    public function setHttpHeader($name, $value) {

        if (!$name) {
            xhprof_error('http_header usage');
            return null;
        }

        if (!is_string($value)) {
            xhprof_error('http_header value not a string');
        }

        header($name.': '.$value, true);
    }

    /**
     * Genearte and send MIME header for the output image to client browser.
     *
     * @author cjiang
     */
    public function GenerateMimeHeader($type, $length) {
        switch ($type) {
            case 'jpg':
                $mime = 'image/jpeg';
                break;
            case 'gif':
                $mime = 'image/gif';
                break;
            case 'png':
                $mime = 'image/png';
                break;
            case 'ps':
                $mime = 'application/postscript';
            default:
                $mime = false;
        }

        if ($mime) {
            xhprof_http_header('Content-type', $mime);
            xhprof_http_header('Content-length', (string)$length);
        }
    }


    /**
     * Generate image according to DOT script. This function will spawn a process
     * with "dot" command and pipe the "dot_script" to it and pipe out the
     * generated image content.
     *
     * @param dot_script, string, the script for DOT to generate the image.
     * @param type, one of the supported image types, see
     * $xhprof_legal_image_types.
     * @returns, binary content of the generated image on success. empty string on
     *           failure.
     *
     * @author cjiang
     */
    public function generateDotImage($dot_script, $type) {
        // get config => yep really dirty - but unobstrusive
        global $_xhprof;

        $errorFile    = $_xhprof['dot_errfile'];
        $tmpDirectory = $_xhprof['dot_tempdir'];
        $dotBinary    = $_xhprof['dot_binary'];

        // detect windows
        if (stristr(PHP_OS, 'WIN') && !stristr(PHP_OS, 'Darwin')) {
            return xhprof_generate_image_by_dot_on_win($dot_script,
                $type,
                $errorFile,
                $tmpDirectory,
                $dotBinary);
        }

        // parts of the original source
        $descriptorspec = array(
            // stdin is a pipe that the child will read from
            0 => array("pipe", "r"),
            // stdout is a pipe that the child will write to
            1 => array("pipe", "w"),
            // stderr is a file to write to
            2 => array("file", $errorFile, "a")
        );

        $cmd = ' "'.$dotBinary.'" -T'.$type;

        $process = proc_open($cmd, $descriptorspec, $pipes, $tmpDirectory, array());

        if (is_resource($process)) {
            fwrite($pipes[0], $dot_script);
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            proc_close($process);
            if ($output == "" && filesize($errorFile) > 0)
            {
                die("Error producing callgraph, check $errorFile");
            }
            return $output;
        }
        print "failed to shell execute cmd=\"$cmd\"\n";

        $error = error_get_last();
        if (isset($error['message'])) {
            print($error['message'] . "\n");
        }

        exit();
    }

}
