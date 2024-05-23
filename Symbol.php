<?php
class Symbol
{
    private string $visual;
    private int $multiplier;
    private int $chanceToOccur = 0;
    public function __construct(string $visual, int $multiplier, int $chanceToOccur)
    {
        $this->visual = $visual;
        $this->multiplier = $multiplier;
        $this->chanceToOccur = $chanceToOccur;
    }
    public function getVisual(): string
    {
        return $this->visual;
    }
    public function getMultiplier(): int
    {
        return $this->multiplier;
    }
    public function getChanceToOccur(): int
    {
        return $this->chanceToOccur;
    }

    public function setChanceToOccur(int $chanceToOccur)
    {
        $this->chanceToOccur = $chanceToOccur;
    }
}