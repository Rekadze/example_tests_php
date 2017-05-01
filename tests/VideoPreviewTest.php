<?php
// selenium-tests/tests/VideoPreviewTest.php

namespace My; // Note the "My" namespace maps to the "tests" folder, as defined in the autoload part of `composer.json`.

use Lmc\Steward\Test\AbstractTestCase;

class VideoPreviewTest extends AbstractTestCase
{
    public function testCheckVideoPreview()
    {
        // Load the URL (will wait until page is loaded)
        $this->wd->get('http://yandex.ru/video');

        // Check page title
        $this->assertContains('Смотрите видео онлайн', $this->wd->getTitle());

        // Set search field
        $input = $this->findByName('text');
        $input->sendKeys('Ураган');

        // Search by filled value
        $input->submit();

        // Wait for spinner disappearing
        $this->assertFalse($this->waitForCss('.presearch-spinner')->isDisplayed());

        // Select first video from the left side and Hover on it.
        // Doesn't work in selenium 3
        $first_video = $this->findByCss('.page-layout__content-wrapper .thumb-image__image');
        $this->wd->getMouse()->mouseMove($first_video->getCoordinates());

        //Check preview images source
        $this->assertNotNull(
            $this->waitForXpath(
                '//*[contains(@class, "page-layout__content-wrapper")]//img[contains(@class,"thumb-image__image") and contains(@srcset,"avatars")]'
            ),
            'No preview for first video'
        );
    }
}