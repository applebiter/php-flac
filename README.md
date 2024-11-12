# php-flac

Flac is a wrapper around the flac binary program
  
This class is a wrapper for the flac binary program from Xiph.org It exposes 
all of the program functionality through class methods.  

API:

    (array) analyze(array $general_options=[], array $analysis_options=[])
        -- takes optional array of general options, and an optional array of 
            analysis options 
            
    (array) batchAnalyze($files, array $general_options=[], array $analysis_options=[]) 
        -- takes an argument in $files that can be either a string, 
            representing the path to a directory containing flac files, or an 
            array of complete filename paths to flac files for batch analysis 
        -- takes optional array of general options, and an optional array of 
            analysis options 
            
    (array) batchDecode($files, array $general_options=[], array $format_options=[], array $decoding_options=[]) 
        -- takes an argument in $files that can be either a string, 
            representing the path to a directory containing flac files, or an 
            array of complete filename paths to flac files for batch decoding 
        -- takes optional array of general options, an optional array of 
            formatting options, and an optional array of decoding options 
            
    (array) batchEncode($files, array $general_options=[], array $format_options=[], array $encoding_options=[]) 
        -- takes an argument in $files that can be either a string, 
            representing the path to a directory containing audio files, or an 
            array of complete filename paths to audio files for batch encoding 
        -- takes optional array of general options, an optional array of 
            formatting options, and an optional array of encoding options 
            
    (array) batchTest($files, array $general_options=[]) 
        -- takes an argument in $files that can be either a string, 
            representing the path to a directory containing flac files, or an 
            array of complete filename paths to flac files for batch testing 
        -- takes optional array of general options  
            
    (array) decode(array $general_options=[], array $format_options=[], array $decoding_options=[]) 
        -- takes optional array of general options, an optional array of 
            formatting options, and an optional array of decoding options 
            
    (array) encode(array $general_options=[], array $format_options=[], array $encoding_options=[]) 
        -- takes optional array of general options, an optional array of 
            formatting options, and an optional array of encoding options 
            
    (array) test(array $general_options=[]) 
        -- takes optional array of general options
    
Examples: 

    Initialize the wrapper by passing it the absolute path to an audio file 
    or a dash (-) indicating the input should come from stdin
    
        $flac = new Flac('-'); 
        
    The following examples follow the example usage of the flac binary 
    program found here: https://xiph.org/flac/documentation_tools_flac.html 
    
    Some common encoding tasks. PLEASE NOTE that the $output that is returned 
    by the following methods does not necessarily contain the modified, 
    encoded, or decoded file, but rather the response text from the flac 
    binary program, itself. That being said, certain parameters, if passed to 
    the flac program, will cause the program to return the output file, or 
    analysis file, through stdout, which would also mean it would be 
    collected in the $output array, along with messages from flac: 
    
    
        Encode abc.wav to abc.flac using the default compression setting. 
        abc.wav is not deleted. 
    
            $flac = new Flac('/path/to/abc.wav'); 
            $output = $flac->encode(); 
        
        
        Like above, except abc.wav is deleted if there were no errors. 
    
            $flac = new Flac('/path/to/abc.wav'); 
            $output = $flac->encode(['--delete-input-file']); 
        
    
        Like above, except abc.wav is deleted if there were no errors or 
        warnings. 
    
            $flac = new Flac('/path/to/abc.wav'); 
            $output = $flac->encode([
                '--delete-input-file', 
                '-w'
            ]); 
            
        
        Encode abc.wav to abc.flac using the highest compression setting. 
    
            $flac = new Flac('/path/to/abc.wav'); 
            $output = $flac->encode(['--best']); 
            
        
        Encode abc.wav to abc.flac and internally decode abc.flac to make 
        sure it matches abc.wav. 
    
            $flac = new Flac('/path/to/abc.wav'); 
            $output = $flac->encode(['--verify']); 
            
        
        Encode abc.wav to my.flac. 
    
            $flac = new Flac('/path/to/abc.wav'); 
            $output = $flac->encode(['-o "/path/to/my.flac"']); 
            
        
        Encode abc.wav and add some tags at the same time to abc.flac.
    
            $flac = new Flac('/path/to/abc.wav'); 
            $output = $flac->encode(['-T "TITLE=Bohemian Rhapsody" -T "ARTIST=Queen"']);
            
        
        Encode all .wav files in the [same] directory. There are two ways to 
        get files into the batch processors. Either pass a string which is a 
        path to a directory with files to process, or pass an array whose 
        elements are paths to individual files to process, whereever they may 
        be in the filesystem. 
        
            First method, using a path to a directory. PLEASE NOTE that this 
            example deviates from the flac documentation in that the file 
            type extension is not exclusively named. This means that any 
            files in the given directory that are among the allowed types 
            defined in this class will be processed by the batch processors:
    
                $flac = new Flac(); 
                $output = $flac->batchEncode('/path/to/directory/of/files'); 
            
            Second method, using an array of filename paths: 
    
                $flac = new Flac(); 
                $output = $flac->batchEncode([
                    '/path/to/file1.wav', 
                    '/path/to/file2.wav', 
                    '/different/path/to/file.aiff'
                ]);
            
        
        Decode abc.flac to abc.wav. abc.flac is not deleted.
    
            $flac = new Flac('/path/to/abc.flac'); 
            $output = $flac->decode(); 
            
        
        Two different ways of decoding abc.flac to abc.aiff (AIFF format). 
        abc.flac is not deleted. 
        
            First method:
    
                $flac = new Flac('/path/to/abc.flac'); 
                $output = $flac->decode(['--force-aiff-format']); 
            
            Second method, using an array of filename paths: 
    
                $flac = new Flac('/path/to/abc.flac'); 
                $output = $flac->decode(['-o "/path/to/abc.aiff"']); 
            
        
        Two different ways of decoding abc.flac to abc.rf64 (RF64 format). 
        abc.flac is not deleted. 
        
            First method:
    
                $flac = new Flac('/path/to/abc.flac'); 
                $output = $flac->decode(['--force-rf64-format']); 
            
            Second method, using an array of filename paths: 
    
                $flac = new Flac('/path/to/abc.flac'); 
                $output = $flac->decode(['-o "/path/to/abc.rf64"']); 
            
        
        Two different ways of decoding abc.flac to abc.w64 (Wave64 format). 
        abc.flac is not deleted. 
        
            First method:
    
                $flac = new Flac('/path/to/abc.flac'); 
                $output = $flac->decode(['--force-wave64-format']); 
            
            Second method, using an array of filename paths: 
    
                $flac = new Flac('/path/to/abc.flac'); 
                $output = $flac->decode(['-o "/path/to/abc.w64"']);
            
        
        Decode abc.flac to abc.wav and don't abort if errors are found 
        (useful for recovering as much as possible from corrupted files).
    
            $flac = new Flac('/path/to/abc.flac'); 
            $output = $flac->decode(['-F']); 
