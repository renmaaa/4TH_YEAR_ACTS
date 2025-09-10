<?php

class QuadraticEquation
{
    private float $a;
    private float $b;
    private float $c;

    public function __construct(float $a, float $b, float $c)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }

    public function getA(): float
    {
        return $this->a;
    }

    public function getB(): float
    {
        return $this->b;
    }

    public function getC(): float
    {
        return $this->c;
    }

    public function getDiscriminant(): float
    {
        return ($this->b ** 2) - (4 * $this->a * $this->c);
    }

    public function getRoot1(): ?float
    {
        $disc = $this->getDiscriminant();
        if ($disc < 0 || $this->a == 0) return null;
        return (-$this->b + sqrt($disc)) / (2 * $this->a);
    }

    public function getRoot2(): ?float
    {
        $disc = $this->getDiscriminant();
        if ($disc < 0 || $this->a == 0) return null;
        return (-$this->b - sqrt($disc)) / (2 * $this->a);
    }
}
?>
