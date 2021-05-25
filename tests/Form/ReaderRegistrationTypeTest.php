<?php
declare(strict_types=1);

namespace App\Tests\Form;

use App\Entity\MagazineReader;
use App\Form\ReaderRegistrationType;
use Symfony\Component\Form\Test\TypeTestCase;

class ReaderRegistrationTypeTest extends TypeTestCase
{
    public function test(): void
    {
        $form = $this->factory->create(ReaderRegistrationType::class);
        $form->submit([
            'email' => $email = 'hello@localhost',
        ]);

        $this->assertTrue($form->isSubmitted() && $form->isValid());

        $data = $form->getData();
        $this->assertInstanceOf(MagazineReader::class, $data);
        $this->assertEquals($email, $data->getEmail());
    }
}
