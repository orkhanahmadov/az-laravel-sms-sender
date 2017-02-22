<?php

namespace Orkhanahmadov\LaravelAzSmsSender;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Orkhanahmadov\LaravelAzSmsSender\Model\SentSms;
use Orkhanahmadov\LaravelAzSmsSender\Model\SentSmsNumber;
use SimpleXMLElement;

/**
 * Class Sender
 * @package Orkhanahmadov\LaravelAzSmsSender
 */
class Sender
{
    /**
     * @var SimpleXMLElement $xml
     */
    private $xml;


    /**
     * Only public function receives request
     *
     * @param array|string $arrayOrNumber
     * @param bool|string $text
     * @return mixed
     */
    public function send($arrayOrNumber, $text = false)
    {
        if (config('az-sms-sender-main.provider') == "msm")
        {
            return $this->msmProvider($arrayOrNumber, $text);
        }

        elseif (config('az-sms-sender-main.provider') == "mobis")
        {
            return $this->mobisProvider($arrayOrNumber, $text);
        }

        return false;
    }


    /**
     * MSM provider API function
     *
     * @param array|string $arrayOrNumber
     * @param bool|string $text
     * @return mixed
     */
    private function msmProvider($arrayOrNumber, $text = false)
    {
        $this->xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" ?><SMS-InsRequest></SMS-InsRequest>");


        $client = $this->xml->addChild("CLIENT");
        $client->addAttribute("user", config('az-sms-sender-main.user'));
        $client->addAttribute("pwd",  config('az-sms-sender-main.pwd'));
        $client->addAttribute("from", config('az-sms-sender-main.from'));


        $recipients = [];

        // single sms
        if (!is_array($arrayOrNumber) && $text)
        {
            $insert = $this->xml->addChild("INSERT");
            $insert->addAttribute("to", $arrayOrNumber);
            $insert->addAttribute("text", $text);

            array_push($recipients, [
                "number" => $arrayOrNumber,
                "message" => $text
            ]);
        }

        // bulk sms with same message
        elseif (!$this->isAssociativeArray($arrayOrNumber) && $text)
        {
            $insertMsg = $this->xml->addChild("INSERTMSG");
            $insertMsg->addAttribute("text", $text);

            foreach ($arrayOrNumber as $number) {
                $insertMsg->addChild("TO", $number);

                array_push($recipients, [
                    "number" => $number,
                    "message" => $text
                ]);
            }
        }

        // multiple individual
        elseif ($this->isAssociativeArray($arrayOrNumber))
        {
            foreach ($arrayOrNumber as $number => $text) {
                $insert = $this->xml->addChild("INSERT");
                $insert->addAttribute("to", $number);
                $insert->addAttribute("text", $text);

                array_push($recipients, [
                    "number" => $number,
                    "message" => $text
                ]);
            }
        }



        $client = new Client();

        $request = new Request(
            'POST',
            config('az-sms-sender-providers.msm')['url'],
            ['Content-Type' => 'text/xml; charset=UTF8'],
            $this->xml->asXML()
        );

        $response = $client->send($request);

        return $this->handleResult($recipients, $response->getBody()->getContents());
    }


    /**
     * Mobis provider API function
     *
     * @param $arrayOrNumber
     * @param bool $text
     * @return array
     */
    private function mobisProvider($arrayOrNumber, $text = false)
    {
        $this->xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" ?><request></request>");

        $head = $this->xml->addChild('head');
        $head->addChild('operation', 'submit');
        $head->addChild('login', config('az-sms-sender-main.user'));
        $head->addChild('password', config('az-sms-sender-main.pwd'));
        $head->addChild('title', config('az-sms-sender-main.from'));
        $head->addChild('scheduled', date('Y-m-d H:i:s')); // TODO: scheduled sms
        $head->addChild('controlid', microtime());


        $recipients = [];

        // single sms
        if (!is_array($arrayOrNumber) && $text)
        {

            $head->addChild('isbulk', 'false');
            $body = $this->xml->addChild('body');
            $body->addChild('msisdn', $arrayOrNumber);
            $body->addChild('message', $text);

            array_push($recipients, [
                "number" => $arrayOrNumber,
                "message" => $text
            ]);
        }

        // bulk sms with same message
        elseif (!$this->isAssociativeArray($arrayOrNumber) && $text)
        {
            $head->addChild('isbulk', 'true');
            $head->addChild('bulkmessage', $text);

            foreach ($arrayOrNumber as $number) {
                $body = $this->xml->addChild('body');
                $body->addChild('msisdn', preg_replace('/\D/', "", $number));

                array_push($recipients, [
                    "number" => $number,
                    "message" => $text
                ]);
            }
        }

        // multiple individual
        elseif ($this->isAssociativeArray($arrayOrNumber))
        {
            $head->addChild('isbulk', 'false');

            foreach ($arrayOrNumber as $number => $text) {
                $body = $this->xml->addChild('body');
                $body->addChild('msisdn', $number);
                $body->addChild('message', $text);

                array_push($recipients, [
                    "number" => $number,
                    "message" => $text
                ]);
            }
        }


        $client = new Client();

        $request = new Request(
            'POST',
            config('az-sms-sender-providers.mobis')['url'],
            ['Content-Type' => 'text/xml; charset=UTF8'],
            $this->xml->asXML()
        );

        $response = $client->send($request);

        return $this->handleResult($recipients, $response->getBody()->getContents());
    }

    /**
     * Function handles response data sent from API
     *
     * @param array $recipients
     * @param string $result
     * @return array
     */
    private function handleResult($recipients, $result)
    {
        $resultArray = simplexml_load_string($result) or die();

        $collectResult = [
            "provider" => config("az-sms-sender-main.provider"),
            "task_id" => $resultArray->STATUS["id"] ?: $resultArray->body->taskid,
            "response_code" => $resultArray->STATUS["res"] ?: $resultArray->head->responsecode,
            "recipients" => $recipients
        ];


        if (config("az-sms-sender-main.use_db")) {
            $sentSms = SentSms::create($collectResult);

            $sentSmsNumber = [];

            foreach ($recipients as $recipient) {
                $recipient["sent_sms_id"] = $sentSms->id;
                array_push($sentSmsNumber, $recipient);
            }

            $sentSms->numbers = $sentSmsNumber;

            SentSmsNumber::insert($sentSmsNumber);
        }

        return $collectResult;

    }



    /**
     * Function checks if given array is associative or not
     *
     * @param array $array
     * @return bool
     */
    private function isAssociativeArray($array)
    {
        if (array() === $array) return false;
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
