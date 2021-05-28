<?php
declare(strict_types=1);

namespace App\Domain\Magazine;

class SubscribeUseCase
{
    /**
     * @var RegisterReaderInterface
     */
    private $registerReader;

    /**
     * @var SendThankYouMailInterface
     */
    private $sendThankYouMail;

    /**
     * SubscribeUseCase constructor.
     * @param RegisterReaderInterface $registerReader
     * @param SendThankYouMailInterface $sendThankYouMail
     */
    public function __construct(RegisterReaderInterface $registerReader, SendThankYouMailInterface $sendThankYouMail)
    {
        $this->registerReader = $registerReader;
        $this->sendThankYouMail = $sendThankYouMail;
    }

    /**
     * @param MagazineReaderInterface $readerObj
     * @throws SubscribeToMagazineFailedException
     */
    public function subscribeToMagazine(MagazineReaderInterface $readerObj): void
    {
        $this->registerReader->registerReader($readerObj);

        try {
            $this->sendThankYouMail->sendThankYouMail($readerObj);
        } catch (SendThankYouMailFailedException $e) {
            throw new SubscribeToMagazineFailedException();
        }
    }
}
