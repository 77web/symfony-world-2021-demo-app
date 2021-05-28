<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\MagazineReader;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mailer\DataCollector\MessageDataCollector;

class MagazineReaderControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected function setUp(): void
    {
        $this->loadFixtureFiles([
            __DIR__.'/../Fixtures/register.yaml',
        ]);
        static::ensureKernelShutdown();
    }

    public function test()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $input = 'hello@localhost.localdomain';

        $form = $crawler->selectButton('Subscribe')->form();
        $form['reader_registration[email]'] = $input;

        $client->enableProfiler();
        $client->submit($form);
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect('/'), (string) $response->getStatusCode());

        /** @var MessageDataCollector $mailProfile */
        $mailProfile = $client->getProfile()->getCollector('mailer');
        $this->assertCount(1, $mailProfile->getEvents()->getMessages());

        $crawler2 = $client->followRedirect();
        $this->assertEquals('Successfully registered.', $crawler2->filter('p.flash')->text());

        $em = $client->getContainer()->get('doctrine')->getManager();
        $this->assertNotNull($em->getRepository(MagazineReader::class)->findOneBy(['email' => $input]));
    }
}
