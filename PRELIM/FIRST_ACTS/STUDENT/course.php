<?php
class Course {
    private string $name;
    private const COST = 1450;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCost(): int {
        return self::COST;
    }
}
