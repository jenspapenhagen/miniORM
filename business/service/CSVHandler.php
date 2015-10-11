<?php
//form https://github.com/jwage/easy-csv

class CSVHandler {
    protected $handle;
    protected $delimiter = ',';
    protected $enclosure = '"';

    private $headersInFirstRow = true;
    private $headers = false;
    private $init;
    private $headerLine = false;
    private $lastLine = false;
    
    public function __construct($path, $mode = 'r+') {
        if (! file_exists($path)) {
            touch($path);
        }
        $this->handle = new SplFileObject($path, $mode);
        $this->handle->setFlags(SplFileObject::DROP_NEW_LINE);
    }
    
    public function __destruct()  {
        $this->handle = null;
    }
    
    public function setDelimiter($delimiter) {
        $this->delimiter = $delimiter;
    }
    
    public function setEnclosure($enclosure)  {
        $this->enclosure = $enclosure;
    }
    

    public function getHeaders()    {
        $this->init();
        return $this->headers;
    }
    
    public function getRow()    {
        $this->init();
        if ($this->isEof()) {
            return false;
        }
        $row = $this->getCurrentRow();
        $isEmpty = $this->rowIsEmpty($row);
        if ($this->isEof() === false) {
            $this->handle->next();
        }
        if ($isEmpty === false) {
            if($this->headers && is_array($this->headers)){
                $output = array_combine($this->headers, $row);
            }else{
                $output = $row;
            }
            return $output; 
        } elseif ($isEmpty) {
            // empty row, transparently try the next row
            return $this->getRow();
        } else {
            return false;
        }
    }
    
    public function isEof()    {
        return $this->handle->eof();
    }
    
    public function getAll()    {
        $data = array();
        while ($row = $this->getRow()) {
            $data[] = $row;
        }
        return $data;
    }
    
    public function getLineNumber()    {
        return $this->handle->key();
    }
    
    public function getLastLineNumber()    {
        if ($this->lastLine !== false) {
            return $this->lastLine;
        }
        $this->handle->seek($this->handle->getSize());
        $lastLine = $this->handle->key();
        $this->handle->rewind();
        return $this->lastLine = $lastLine;
    }
    
    public function countColumns(){
        $output = count($this->getCurrentRow());
        
        return $output;
    }
    
    public function getCurrentRow()    {
        return str_getcsv($this->handle->current(), $this->delimiter, $this->enclosure);
    }
    
    public function advanceTo($lineNumber)    {
        if ($this->headerLine > $lineNumber) {
            echo "Line Number $lineNumber is before the header line that was set";
            die();
        } elseif ($this->headerLine === $lineNumber) {
            echo "Line Number $lineNumber is equal to the header line that was set";
            die();
        }
        if ($lineNumber > 0) {
            $this->handle->seek($lineNumber - 1);
        } // check the line before
        if ($this->isEof()) {
            echo "Line Number $lineNumber is past the end of the file";
            die();
        }
        $this->handle->seek($lineNumber);
    }
    
    public function setHeaderLine($lineNumber)    {
        if ($lineNumber !== 0) {
            $this->headersInFirstRow = false;
        } else {
            return false;
        }
        $this->headerLine = $lineNumber;
        $this->handle->seek($lineNumber);
        // get headers
        $this->headers = $this->getRow();
    }
    
    protected function init()
    {
        if (true === $this->init) {
            return;
        }
        $this->init = true;
        if ($this->headersInFirstRow === true) {
            $this->handle->rewind();
            $this->headerLine = 0;
            $this->headers = $this->getRow();
        }
    }
    
    protected function rowIsEmpty($row)    {
        $emptyRow = ($row === array(null));
        $emptyRowWithDelimiters = (array_filter($row) === array());
        $isEmpty = false;
        if ($emptyRow) {
            $isEmpty = true;
            return $isEmpty;
        } elseif ($emptyRowWithDelimiters) {
            $isEmpty = true;
            return $isEmpty;
        }
        return $isEmpty;
    }
    

    public function writeRow($row)  {
        if (is_string($row)) {
            $row = explode(',', $row);
            $row = array_map('trim', $row);
        }
        return $this->handle->fputcsv($row, $this->delimiter, $this->enclosure);
    }
    
    public function writeFromArray(array $array)  {
        foreach ($array as $key => $value) {
            $this->writeRow($value);
        }
    }
    
}   
    

 