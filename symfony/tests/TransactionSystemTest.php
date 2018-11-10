<?php

namespace App\Tests;

class TransactionSystemTest extends \PHPUnit\Framework\TestCase
{
    const BASE_URL = "http://symfony.localhost:8080";

    public function testCanCreateTransaction()
    {
        $ch = $this->prepareCurlPostRequest(
            self::BASE_URL . "/transaction",
            file_get_contents(__DIR__ . "/Resources/data_example.json")
        );

        $response = curl_exec($ch);
        curl_close($ch);

        $uuid = json_decode($response)->uuid;

        $this->assertTrue($this->isValidUuid($uuid));

        return $uuid;
    }

    /**
     * @depends testCanCreateTransaction
     * @param string $uuid
     */
    public function testCanGetTransaction(string $uuid)
    {
        $ch = curl_init(self::BASE_URL . "/transaction/" . $uuid);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $testData = file_get_contents(__DIR__ . "/Resources/data_example.json");

        $this->assertTrue($response !== false);
        $this->assertSame(json_decode($response)->booking_date, json_decode($testData)->booking_date);
    }

    /**
     * @param $uuid
     * @return bool
     * @author https://gist.github.com/Joel-James/3a6201861f12a7acf4f2
     */
    private function isValidUuid($uuid)
    {

        if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
            return false;
        }
        return true;
    }

    private function prepareCurlPostRequest($url, string $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );

        return $ch;
    }
}