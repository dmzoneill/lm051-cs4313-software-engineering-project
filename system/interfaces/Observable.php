<?php
// phpcs:ignoreFile

interface Observable
{
    /* @phpstan-ignore-line */ /** @psalm-suppress MissingReturnType, UndefinedClass */ /** @psalm-suppress MissingReturnType, UndefinedClass */ public function attach( Observer $observer );
    /* @phpstan-ignore-line */ /** @psalm-suppress MissingReturnType, UndefinedClass */ /** @psalm-suppress MissingReturnType, UndefinedClass */ public function detatch( Observer $observer ); // codespell:ignore detatch
    /** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ public function notify();
    /** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ public function status();
}
