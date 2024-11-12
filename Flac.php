<?php 
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/** 
 * Flac is a wrapper around the flac binary program
 * 
 * This class is a wrapper for the flac binary program from Xiph.org It exposes 
 * all of the program functionality through class methods.  
 * 
 * API:
 * 
 *     (array) analyze(array $general_options=[], array $analysis_options=[])
 *         -- takes optional array of general options, and an optional array of 
 *            analysis options 
 *            
 *     (array) batchAnalyze($files, array $general_options=[], array $analysis_options=[]) 
 *         -- takes an argument in $files that can be either a string, 
 *            representing the path to a directory containing flac files, or an 
 *            array of complete filename paths to flac files for batch analysis 
 *         -- takes optional array of general options, and an optional array of 
 *            analysis options 
 *            
 *     (array) batchDecode($files, array $general_options=[], array $format_options=[], array $decoding_options=[]) 
 *         -- takes an argument in $files that can be either a string, 
 *            representing the path to a directory containing flac files, or an 
 *            array of complete filename paths to flac files for batch decoding 
 *         -- takes optional array of general options, an optional array of 
 *            formatting options, and an optional array of decoding options 
 *            
 *     (array) batchEncode($files, array $general_options=[], array $format_options=[], array $encoding_options=[]) 
 *         -- takes an argument in $files that can be either a string, 
 *            representing the path to a directory containing audio files, or an 
 *            array of complete filename paths to audio files for batch encoding 
 *         -- takes optional array of general options, an optional array of 
 *            formatting options, and an optional array of encoding options 
 *            
 *     (array) batchTest($files, array $general_options=[]) 
 *         -- takes an argument in $files that can be either a string, 
 *            representing the path to a directory containing flac files, or an 
 *            array of complete filename paths to flac files for batch testing 
 *         -- takes optional array of general options  
 *            
 *     (array) decode(array $general_options=[], array $format_options=[], array $decoding_options=[]) 
 *         -- takes optional array of general options, an optional array of 
 *            formatting options, and an optional array of decoding options 
 *            
 *     (array) encode(array $general_options=[], array $format_options=[], array $encoding_options=[]) 
 *         -- takes optional array of general options, an optional array of 
 *            formatting options, and an optional array of encoding options 
 *            
 *     (array) test(array $general_options=[]) 
 *         -- takes optional array of general options
 *     
 * Examples: 
 * 
 *     Initialize the wrapper by passing it the absolute path to an audio file 
 *     or a dash (-) indicating the input should come from stdin
 *     
 *         $flac = new Flac('-'); 
 *         
 *     The following examples follow the example usage of the flac binary 
 *     program found here: https://xiph.org/flac/documentation_tools_flac.html 
 *     
 *     Some common encoding tasks. PLEASE NOTE that the $output that is returned 
 *     by the following methods does not necessarily contain the modified, 
 *     encoded, or decoded file, but rather the response text from the flac 
 *     binary program, itself. That being said, certain parameters, if passed to 
 *     the flac program, will cause the program to return the output file, or 
 *     analysis file, through stdout, which would also mean it would be 
 *     collected in the $output array, along with messages from flac: 
 *     
 *     
 *         Encode abc.wav to abc.flac using the default compression setting. 
 *         abc.wav is not deleted. 
 *      
 *             $flac = new Flac('/path/to/abc.wav'); 
 *             $output = $flac->encode(); 
 *         
 *          
 *         Like above, except abc.wav is deleted if there were no errors. 
 *      
 *             $flac = new Flac('/path/to/abc.wav'); 
 *             $output = $flac->encode(['--delete-input-file']); 
 *          
 *     
 *         Like above, except abc.wav is deleted if there were no errors or 
 *         warnings. 
 *      
 *             $flac = new Flac('/path/to/abc.wav'); 
 *             $output = $flac->encode([
 *                 '--delete-input-file', 
 *                 '-w'
 *             ]); 
 *             
 *          
 *         Encode abc.wav to abc.flac using the highest compression setting. 
 *     
 *             $flac = new Flac('/path/to/abc.wav'); 
 *             $output = $flac->encode(['--best']); 
 *             
 *         
 *         Encode abc.wav to abc.flac and internally decode abc.flac to make 
 *         sure it matches abc.wav. 
 *     
 *             $flac = new Flac('/path/to/abc.wav'); 
 *             $output = $flac->encode(['--verify']); 
 *             
 *         
 *         Encode abc.wav to my.flac. 
 *     
 *             $flac = new Flac('/path/to/abc.wav'); 
 *             $output = $flac->encode(['-o "/path/to/my.flac"']); 
 *             
 *         
 *         Encode abc.wav and add some tags at the same time to abc.flac.
 *     
 *             $flac = new Flac('/path/to/abc.wav'); 
 *             $output = $flac->encode(['-T "TITLE=Bohemian Rhapsody" -T "ARTIST=Queen"']);
 *             
 *         
 *         Encode all .wav files in the [same] directory. There are two ways to 
 *         get files into the batch processors. Either pass a string which is a 
 *         path to a directory with files to process, or pass an array whose 
 *         elements are paths to individual files to process, whereever they may 
 *         be in the filesystem. 
 *         
 *             First method, using a path to a directory. PLEASE NOTE that this 
 *             example deviates from the flac documentation in that the file 
 *             type extension is not exclusively named. This means that any 
 *             files in the given directory that are among the allowed types 
 *             defined in this class will be processed by the batch processors:
 *     
 *                 $flac = new Flac(); 
 *                 $output = $flac->batchEncode('/path/to/directory/of/files'); 
 *             
 *             Second method, using an array of filename paths: 
 *     
 *                 $flac = new Flac(); 
 *                 $output = $flac->batchEncode([
 *                     '/path/to/file1.wav', 
 *                     '/path/to/file2.wav', 
 *                     '/different/path/to/file.aiff'
 *                 ]);
 *             
 *         
 *         Decode abc.flac to abc.wav. abc.flac is not deleted.
 *     
 *             $flac = new Flac('/path/to/abc.flac'); 
 *             $output = $flac->decode(); 
 *             
 *         
 *         Two different ways of decoding abc.flac to abc.aiff (AIFF format). 
 *         abc.flac is not deleted. 
 *         
 *             First method:
 *     
 *                 $flac = new Flac('/path/to/abc.flac'); 
 *                 $output = $flac->decode(['--force-aiff-format']); 
 *             
 *             Second method, using an array of filename paths: 
 *     
 *                 $flac = new Flac('/path/to/abc.flac'); 
 *                 $output = $flac->decode(['-o "/path/to/abc.aiff"']); 
 *             
 *         
 *         Two different ways of decoding abc.flac to abc.rf64 (RF64 format). 
 *         abc.flac is not deleted. 
 *         
 *             First method:
 *     
 *                 $flac = new Flac('/path/to/abc.flac'); 
 *                 $output = $flac->decode(['--force-rf64-format']); 
 *             
 *             Second method, using an array of filename paths: 
 *     
 *                 $flac = new Flac('/path/to/abc.flac'); 
 *                 $output = $flac->decode(['-o "/path/to/abc.rf64"']); 
 *             
 *         
 *         Two different ways of decoding abc.flac to abc.w64 (Wave64 format). 
 *         abc.flac is not deleted. 
 *         
 *             First method:
 *     
 *                 $flac = new Flac('/path/to/abc.flac'); 
 *                 $output = $flac->decode(['--force-wave64-format']); 
 *             
 *             Second method, using an array of filename paths: 
 *     
 *                 $flac = new Flac('/path/to/abc.flac'); 
 *                 $output = $flac->decode(['-o "/path/to/abc.w64"']);
 *             
 *         
 *         Decode abc.flac to abc.wav and don't abort if errors are found 
 *         (useful for recovering as much as possible from corrupted files).
 *     
 *             $flac = new Flac('/path/to/abc.flac'); 
 *             $output = $flac->decode(['-F']); 
 *         
 *            
 * PHP version 7 
 * 
 * @category Command-line, FLAC 
 * @package applebiter/flac 
 * @author Richard Lucas <webmaster@applebiter.com> 
 * @link https://bitbucket.org/applebiter/flac
 * @license MIT License 
 * 
 * The MIT License (MIT)
 *
 * Copyright (c) 2018
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class Flac 
{ 
    protected $_allowed_types = [
        'audio/aiff', 'audio/flac', 'audio/ogg', 'audio/ogg;codec=flac', 
        'audio/wav', 'audio/x-aiff', 'audio/x-flac', 'audio/x-wav'
    ];
    
   /**
     * _audio_file
     *
     * The absolute path to the file to be inspected/manipulated
     *
     * @var string
     */
    protected $_audio_file;
    
    /**
     * _command 
     * 
     * The full text of the command that was most recently constructed
     * 
     * @var string
     */
    protected $_command;
    
    /**
    * _errors
    *
    * Holds error messages if any are generated
    *
    * @var array
    */
    protected $_errors = [];
    
    /**
     * _flac
     *
     * The path to the binary program flac
     *
     * @var string
     */
    protected $_flac; 
    
    /**
     * _has_errors
     *
     * Indicates whether an error has been generated
     *
     * @var boolean
     */
    protected $_has_errors = false; 
    
    /**
     * _output 
     * 
     * Holds the output of a call to exec()
     * 
     * @var array
     */
    protected $_output = [];
    
    /**
     * __construct()
     *
     * Takes the absolute path to the input file to be inspected/manipulated, or 
     * else a single dash (-) to indicate the input should come from stdin
     *
     * @param string $filenamepath
     */
    public function __construct($filenamepath = null, $flacpath = null)
    {
        $filenamepath = $filenamepath ? $filenamepath : '-';
        $flacpath = $flacpath ? $flacpath : '/usr/bin/flac';
        
        $this->_initialize($filenamepath, $flacpath);
    } 
    
    /**
     * _inititalize() 
     * 
     * Initialize object values
     * 
     * @param string $filenamepath // path to audio file
     * @param string $flacpath     // path to flac binary program
     */
    protected function _initialize($filenamepath, $flacpath) 
    {
        if ('-' == $filenamepath) {
            
            $this->_audio_file = '-';
        }
        else if (is_file($filenamepath)) {
            
            if (is_readable($filenamepath)) {
                
                $this->_audio_file = $filenamepath;
            }
            else {
                
                $this->_errors[] = 'The file is not readable.';
                $this->_has_errors = true;
            }
        }
        else {
            
            $this->_errors[] = 'The supplied filename is not a file.';
            $this->_has_errors = true;
        }
        
        $this->_flac = $flacpath;        
    } 
    
    /**
     * _batch() 
     * 
     * Run the flac binary to operate on a set of audio files
     * 
     * @param string $type
     * @param string|array $files 
     * @param array $general_options optional
     * @param array $format_options optional
     * @param array $function_options optional
     * @return array
     */
    protected function _batch(
              $type,
              $files,
        array $general_options = [],
        array $format_options = [],
        array $function_options = [])
    {
        switch ($type) {
            
            case 'analyze':
                $function = '-a ';
                break;
                
            case 'decode':
                $function = '-d ';
                break;
                
            case 'test':
                $function = '-t ';
                break;
                
            case 'encode':
            default:
                $function = '';
                break;
        }
        
        $general_options = !empty($general_options) ? implode(' ', $general_options) . ' ' : '';
        $format_options = !empty($format_options) ? implode(' ', $format_options) . ' ' : '';
        $function_options = !empty($function_options) ? implode(' ', $function_options) . ' ' : '';
        
        $this->_command = "{$this->_flac} {$function}{$general_options}{$format_options}{$function_options}-- ";
        
        if (is_string($files)) {
            
            if (is_dir($files)) {
                
                if (is_readable($files)) {
                    
                    // the given string is a directory path which can be read...
                    $objects = scandir($files);
                    
                    // ...so for each file in the directory...
                    foreach ($objects as $object) {
                        
                        if ('.' != $object && '..' != $object) {
                            
                            $command = 'file -b --mime-type -m /usr/share/misc/magic "' . $files . '/' . $object . '"';
                            $mimetype = shell_exec($command);
                            
                            // ...if the OS thinks it is an allowed type...
                            if (in_array(trim($mimetype), $this->_allowed_types)) {
                                
                                // ...add the file to the list
                                $this->_command .= ' "' . $files . '/' . $object . '"';
                            }
                        }
                    }
                }
                else {
                    
                    $this->_errors[] = 'The directory is not readable.';
                    $this->_has_errors = true;
                }
            }
            else {
                
                $this->_errors[] = 'The given path was not a valid directory.';
                $this->_has_errors = true;
            }
        }
        else if (is_array($files)) {
            
            // an array of filenames was given, so for each one...
            foreach ($files as $file) {
                
                // ...if the file exists...
                if (is_file($file)) {
                    
                    $command = 'file -b --mime-type -m /usr/share/misc/magic "' . $file . '"';
                    $mimetype = shell_exec($command);
                    
                    // ...if the OS thinks it is a flac file...
                    if (in_array(trim($mimetype), $this->_allowed_types)) {
                        
                        // ...add the file to the list
                        $this->_command .= ' "' . $file . '"';
                    }
                }
                else {
                    
                    $this->_errors[] = 'One or more of the given file paths were not valid filenames.';
                    $this->_has_errors = true;
                }
            }
        }
        
        $this->_command .= " 2>&1";
        
        $errno = 0;
        
        exec($this->_command, $this->_output, $errno);
        
        if (0 !== $errno) {
            
            $this->_errors[] = 'An unknown error occurred.';
            $this->_has_errors = true;
        }
        
        return $this->_output;
    }
    
    /**
     * _single()
     *
     * Run the flac binary to operate on a single audio file
     *
     * @param string $type
     * @param array $general_options optional
     * @param array $format_options optional
     * @param array $function_options optional
     * @return array
     */
    protected function _single(
        $type,
        array $general_options = [],
        array $format_options = [],
        array $function_options = [])
    {
        switch ($type) {
            
            case 'analyze':
                $function = '-a ';
                break;
                
            case 'decode':
                $function = '-d ';
                break;
                
            case 'test':
                $function = '-t ';
                break;
                
            case 'encode':
            default:
                $function = '';
                break;
        }
        
        $general_options = !empty($general_options) ? implode(' ', $general_options) . ' ' : '';
        $format_options = !empty($format_options) ? implode(' ', $format_options) . ' ' : '';
        $function_options = !empty($function_options) ? implode(' ', $function_options) . ' ' : '';
        
        $this->_command = "{$this->_flac} {$function}{$general_options}{$format_options}{$function_options}-- '{$this->_audio_file}' 2>&1";
        
        $errno = 0;
        
        exec($this->_command, $this->_output, $errno);
        
        if (0 !== $errno) {
            
            $this->_errors[] = 'An unknown error occurred.';
            $this->_has_errors = true;
        }
        
        return $this->_output;
    } 
    
    /**
     * analyze() 
     * 
     * Analyze a single flac audio file
     * 
     * @param array $general_options optional
     * @param array $analysis_options optional
     * @return array
     */
    public function analyze(
        array $general_options = [], 
        array $analysis_options = []) 
    {
        return $this->_single('analyze', $general_options, [], $analysis_options);
    } 
    
    /**
     * batchAnalyze() 
     * 
     * Analyze a set of flac audio files
     * 
     * @param string|array $files
     * @param array $general_options optional
     * @param array $analysis_options optional
     * @return array
     */
    public function batchAnalyze(
              $files, 
        array $general_options = [], 
        array $analysis_options = [])
    {
        return $this->_batch('analyze', $files, $general_options, [], $analysis_options);
    } 
    
    /**
     * batchDecode() 
     * 
     * Decode a set of flac audio files
     * 
     * @param string|array $files
     * @param array $general_options optional
     * @param array $format_options optional
     * @param array $decoding_options optional
     * @return array
     */
    public function batchDecode(
              $files, 
        array $general_options = [], 
        array $format_options = [], 
        array $decoding_options = []) 
    {
        return $this->_batch('decode', $files, $general_options, $format_options, $decoding_options);
    } 
    
    /**
     * batchEncode() 
     * 
     * Encode a set of audio files to flac
     * 
     * @param string|array $files
     * @param array $general_options optional
     * @param array $format_options optional
     * @param array $encoding_options optional
     * @return array
     */
    public function batchEncode(
              $files,
        array $general_options = [],
        array $format_options = [],
        array $encoding_options = []) 
    {
        return $this->_batch('encode', $files, $general_options, $format_options, $encoding_options);
    }
    
    /**
     * batchTest()
     *
     * Test a set of flac audio files
     *
     * @param string|array $files
     * @param array $general_options optional
     * @return array
     */
    public function batchTest($files, array $general_options = [])
    {
        return $this->_batch('test', $files, $general_options, [], []);
    }
    
    /**
     * decode()
     *
     * Decode a single audio file from flac format
     *
     * @param array $general_options optional
     * @param array $format_options optional
     * @param array $decoding_options optional
     * @return array
     */
    public function decode(
        array $general_options = [],
        array $format_options = [], 
        array $decoding_options = [])
    {
        return $this->_single('decode', $general_options, $format_options, $decoding_options);
    }
    
    /**
     * encode()
     *
     * Encode a single audio file to flac format
     *
     * @param array $general_options optional
     * @param array $format_options optional
     * @param array $encoding_options optional
     * @return array
     */
    public function encode(
        array $general_options = [],
        array $format_options = [],
        array $encoding_options = [])
    {
        return $this->_single('encode', $general_options, $format_options, $encoding_options);
    }
    
    /**
     * test()
     *
     * Test a single flac audio file
     *
     * @param array $general_options optional
     * @param array $format_options optional
     * @param array $encoding_options optional
     * @return array
     */
    public function test(array $general_options = [])
    {
        return $this->_single('test', $general_options, [], []);
    } 
    
    /**
     * getErrors()
     *
     * Return the error, if any exists, and boolean false otherwise
     *
     * @return array|boolean
     */
    public function getErrors()
    {
        return $this->_has_errors ? $this->_errors : false;
    }
    
    /**
     * hasErrors()
     *
     * Indicates whether an error has occurred
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return $this->_has_errors;
    }

    /**
     * version()
     *
     * Returns the version information from the flac binary program
     *
     * @return string
     */
    public function version()
    {
        $command = "{$this->_flac} --version 2>&1";
        $result = shell_exec($command);
        
        return $result;
    }
}