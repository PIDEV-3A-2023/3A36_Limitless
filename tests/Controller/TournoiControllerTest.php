<?php

namespace App\Test\Controller;

use App\Entity\Tournoi;
use App\Repository\TournoiRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TournoiControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TournoiRepository $repository;
    private string $path = '/tournois/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Tournoi::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Tournoi index');

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
            'tournoi[nomTournoi]' => 'Testing',
            'tournoi[participantActuel]' => 'Testing',
            'tournoi[participantTotal]' => 'Testing',
            'tournoi[nomHote]' => 'Testing',
            'tournoi[dateDebut]' => 'Testing',
            'tournoi[prix]' => 'Testing',
            'tournoi[typeTournoi]' => 'Testing',
            'tournoi[image]' => 'Testing',
        ]);

        self::assertResponseRedirects('/tournois/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Tournoi();
        $fixture->setNomTournoi('My Title');
        $fixture->setParticipantActuel('My Title');
        $fixture->setParticipantTotal('My Title');
        $fixture->setNomHote('My Title');
        $fixture->setDateDebut('My Title');
        $fixture->setPrix('My Title');
        $fixture->setTypeTournoi('My Title');
        $fixture->setImage('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Tournoi');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Tournoi();
        $fixture->setNomTournoi('My Title');
        $fixture->setParticipantActuel('My Title');
        $fixture->setParticipantTotal('My Title');
        $fixture->setNomHote('My Title');
        $fixture->setDateDebut('My Title');
        $fixture->setPrix('My Title');
        $fixture->setTypeTournoi('My Title');
        $fixture->setImage('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'tournoi[nomTournoi]' => 'Something New',
            'tournoi[participantActuel]' => 'Something New',
            'tournoi[participantTotal]' => 'Something New',
            'tournoi[nomHote]' => 'Something New',
            'tournoi[dateDebut]' => 'Something New',
            'tournoi[prix]' => 'Something New',
            'tournoi[typeTournoi]' => 'Something New',
            'tournoi[image]' => 'Something New',
        ]);

        self::assertResponseRedirects('/tournois/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomTournoi());
        self::assertSame('Something New', $fixture[0]->getParticipantActuel());
        self::assertSame('Something New', $fixture[0]->getParticipantTotal());
        self::assertSame('Something New', $fixture[0]->getNomHote());
        self::assertSame('Something New', $fixture[0]->getDateDebut());
        self::assertSame('Something New', $fixture[0]->getPrix());
        self::assertSame('Something New', $fixture[0]->getTypeTournoi());
        self::assertSame('Something New', $fixture[0]->getImage());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Tournoi();
        $fixture->setNomTournoi('My Title');
        $fixture->setParticipantActuel('My Title');
        $fixture->setParticipantTotal('My Title');
        $fixture->setNomHote('My Title');
        $fixture->setDateDebut('My Title');
        $fixture->setPrix('My Title');
        $fixture->setTypeTournoi('My Title');
        $fixture->setImage('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/tournois/');
    }
}
