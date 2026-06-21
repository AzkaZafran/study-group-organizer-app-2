<?php

namespace App\Exceptions;

use Exception;

class ParticipantsNotFoundException extends Exception {
    private array $missingIds;

    public function __construct(array $missingIds, string $message = "SOME_PARTICIPANTS_NOT_FOUND", int $code = 0, null $previous = null) {
        parent::__construct($message, $code, $previous);

        $this->missingIds = $missingIds;
    }

    public function getMissingIds(): array {
        return $this->missingIds;
    }
}