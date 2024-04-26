<?php
namespace App\Models;
use App\Core\Database;

class LogAppel extends Database {
    private int $id;
    private \DateTime|string $callDate;
    private ?string $src;
    private ?string $dst;
    private int $duration;
    private string $disposition;
    private string $dstChannel;

    public function __construct() {
        parent::__construct();
    }
    public function getId(): int {
        return $this->id;
    }

    public function setCallDate(\DateTime|string $callDate): void
    {
        $this->callDate = $callDate;
    }

    public function getCallDate(): \DateTime | string {
        return $this->callDate;
    }

    public function setSrc(?string $src): void
    {
        $this->src = $src;
    }

    public function getSrc(): ?string {
        return $this->src;
    }

    public function setDst(?string $dst): void
    {
        $this->dst = $dst;
    }

    public function getDst(): ?string {
        return $this->dst;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function getDuration(): int {
        return $this->duration;
    }

    public function setDisposition(string $disposition): void
    {
        $this->disposition = $disposition;
    }

    public function getDisposition(): string {
        return $this->disposition;
    }

    public function setDstChannel(string $dstChannel): void
    {
        $this->dstChannel = $dstChannel;
    }

    public function getDstChannel(): string {
        return $this->dstChannel;
    }
}