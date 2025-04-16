<?php
/**
* IW 2021
*
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class IWSTDIteratorValues
{
    public function __construct($start, $size, $dimension, $max_dimension)
    {
        $this->start = $start;
        $this->size = $size;
        $this->index = $start;
        $this->dimension = $dimension;
        $this->max_dimension = $max_dimension;
    }
}

class IWSTDIterator
{
    public function __construct($dimension, $size, $full=false)
    {
        $this->list = array();
        $this->dimension = $dimension;
        $this->size = $size;
        $this->full = $full;
        $this->count = 0;
    }
  
    public function isStarted()
    {
        return count($this->list) > 0;
    }

    public function addIterate($dimension, $start, $size, $max_dimension)
    {
        $iv = new IWSTDIteratorValues($start, $size, $dimension, $max_dimension);
        $this->list[] = $iv;
    }

    public function addNextIterate()
    {
        $size = count($this->list);
        $iv = $this->list[$size-1];
        if ($this->full) {
            $dim = 1;
            $incr = 0;
        } else {
            $dim = $iv->dimension+1;
            $incr = $iv->index+1;
        }
        $this->addIterate($dim, $incr, $iv->size, $iv->max_dimension);
    }

    public function next()
    {
        if (!$this->isStarted()) {
            if ($this->size == 0 || $this->size < $this->dimension) {
                return false;
            }
            $this->addIterate(1, 0, $this->size, $this->dimension);
            while (count($this->list) < $this->dimension) {
                $this->addNextIterate();
            }
            $result = $this->isStarted();
            if ($result) {
                $this->count += 1;
            }
            return $result;
        }
        $size = count($this->list);
        if ($size == 0) {
            return false;
        }
        while ($size) {
            $iv = $this->list[$size-1];
            $iv->index += 1;
            $x = $iv->index;
            if ($this->full) {
                $max = $this->size;
            } else {
                $max = $iv->size - $iv->max_dimension + $iv->dimension;
            }
            if ($x < $max) {
                $dimension = $this->dimension;
                while (count($this->list) < $dimension) {
                    $this->addNextIterate();
                }
                $this->count += 1;
                return true;
            } else {
                $size -= 1;
                if ($size == 0) {
                    return false;
                }
                while (count($this->list) > $size) {
                    array_pop($this->list);
                }
            }
        }
        return false;
    }

    public function current($list=false)
    {
        $r = [];
        foreach($this->list as $x) {
            if ($list) {
                $r[] = $list[$x->index];
            } else {
                $r[] = $x->index;
            }
        }
        return $r;
    }

    public function currentCount()
    {
        return $this->count;
    }
}
