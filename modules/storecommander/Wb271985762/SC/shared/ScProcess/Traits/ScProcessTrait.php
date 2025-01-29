<?php

namespace Sc\ScProcess\Traits;

use DateTime;
use Exception;

trait ScProcessTrait
{
    /**
     * @var array|false|int|string
     */
    private $data;

    private $lastRunAt;

    /**
     * @var mixed
     */
    private $processParams;

    /**
     * @param $channel
     * @param $message
     *
     * @return void
     */

    /**
     * @param $channel
     * @param array|Exception $data
     *
     * @return void
     */
    public function sendResponse($channel, $data = [])
    {

        if($data instanceof Exception or $data instanceof \Throwable){
            $message = $data->getMessage();
            if(SC_BETA){
                $message.=' '.$data->getTraceAsString();
            }
            $data = stripslashes($message);
        }
        $output = 'event: '.$channel."\r\n";
        $output .= 'data: '.json_encode($data)." \r\n";
        $output .= "\r\n";

        echo $output;
    }

    /**
     * @param DateTime|false $lastRunAt
     *
     * @return $this
     */
    public function setLastRunAt($lastRunAt)
    {
        $this->lastRunAt = $lastRunAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastRunAt()
    {
        return $this->lastRunAt;
    }
}
