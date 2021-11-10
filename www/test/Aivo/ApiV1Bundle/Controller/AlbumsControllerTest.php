<?php 

namespace Test\Aivo\ApiV1Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;

class AlbumsControllerTest extends WebTestCase
{
    const BASE_PATH='/api/v1/albums';
    const HEADER=array('CONTENT_TYPE' => 'application/json');
    private $client,$em;

    protected function setUp(): void
    {
        static::bootKernel();
        $this->client = static::createClient();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @test
     */
    public function ifExistEndPoint()
    {
        $this->client->request('GET', self::BASE_PATH."?q=u2");
        $this->assertJsonResponse($this->client->getResponse());
    }
    
    /**
     * @test
     */
    public function failureQueryEmpty()
    {
        $this->client->request('GET', self::BASE_PATH);
        $this->assertJsonResponse($this->client->getResponse(),400);
    }
    
    protected function assertJsonResponse($response, $statusCode = 200){
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }
    
   
}