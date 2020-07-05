<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace MainframeTest\Utils\Helper;

use Mainframe\Utils\Helper\Data;
use Mainframe\Utils\Helper\Str;
use MainframeTest\Utils\MainframeTestCase;

class StrTest extends MainframeTestCase
{
    public function testSimilarityProvidesAPercentage()
    {
        $word = $this->faker->word;
        $this->assertEquals(Str::similarity());
    }

    public function testSluggifyProperlyConvertsTextToSlug()
    {
        $this->assertEquals(
            'images-users-contacts-79514-1-gif',
            Str::sluggify(Data::get($this->samples, 'users.0DD464H-1.contacts.79514-1.profile-image'))
        );
        $this->assertEquals(
            'walter-hassie-pagac-com',
            Str::sluggify(Data::get($this->samples, 'users.1TK139A-6.contacts.24088-3.email'))
        );
        $this->assertEquals(
            'consequatur-quis-alias-in-aspernatur-cum-quia-distinctio-voluptatum-expedita-ab-voluptas-fugit-archi',
            Str::sluggify(Data::get($this->samples, 'users.1TV730Y-8.contacts.67638-4.notes'), 100)
        );
        $this->assertEquals(
            'prof-shayne-o-kon',
            Str::sluggify(Data::get($this->samples, 'users.1TV730Y-8.contacts.80088-5.name'))
        );
        $this->assertEquals(
            '1-496-418-6733',
            Str::sluggify(Data::get($this->samples, 'users.1TV730Y-8.contacts.80088-5.phone'))
        );
    }
}

