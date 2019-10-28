<?php

namespace Tests;

use Illuminate\Support\Collection;
use Laracasts\Flash\Message;

trait TestFlashMessages
{
    protected function flashMessages(): Collection
    {
        return $this->app['session']->get('flash_notification');
    }

    protected function flashMessagesForLevel(string $level)
    {
        return $this->flashMessages()->filter(function (Message $message) use ($level) {
            return $message->level === $level;
        });
    }

    protected function flashMessagesForMessage(string $string)
    {
        return $this->flashMessages()->filter(function (Message $message) use ($string) {
            return $message->message === $string;
        });
    }
}