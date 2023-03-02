<?php

namespace App\Test\Controller;

use App\Entity\Matches;
use App\Repository\MatchesRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MatchesControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private MatchesRepository $repository;
    private string $path = '/matches/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Matches::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Match index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'match[tourActuel]' => 'Testing',
            'match[scoreEquipe1]' => 'Testing',
            'match[scoreEquipe2]' => 'Testing',
            'match[idTournoi]' => 'Testing',
        ]);

        self::assertResponseRedirects('/matches/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Matches();
        $fixture->setTourActuel('My Title');
        $fixture->setScoreEquipe1('My Title');
        $fixture->setScoreEquipe2('My Title');
        $fixture->setIdTournoi('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Match');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Matches();
        $fixture->setTourActuel('My Title');
        $fixture->setScoreEquipe1('My Title');
        $fixture->setScoreEquipe2('My Title');
        $fixture->setIdTournoi('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'match[tourActuel]' => 'Something New',
            'match[scoreEquipe1]' => 'Something New',
            'match[scoreEquipe2]' => 'Something New',
            'match[idTournoi]' => 'Something New',
        ]);

        self::assertResponseRedirects('/matches/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTourActuel());
        self::assertSame('Something New', $fixture[0]->getScoreEquipe1());
        self::assertSame('Something New', $fixture[0]->getScoreEquipe2());
        self::assertSame('Something New', $fixture[0]->getIdTournoi());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Matches();
        $fixture->setTourActuel('My Title');
        $fixture->setScoreEquipe1('My Title');
        $fixture->setScoreEquipe2('My Title');
        $fixture->setIdTournoi('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/matches/');
    }
}
