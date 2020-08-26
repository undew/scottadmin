<?php

class Emp
{

    private $id;
    private $emNo;
    private $emName;
    private $emJob;
    private $emMgr;
    private $emHiredate;
    private $emSal;
    private $dpId;

    //以下アクセサメソッド。

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getEmNo(): ?int
    {
        return $this->emNo;
    }
    public function setEmNo(int $emNo): void
    {
        $this->emNo = $emNo;
    }
    public function getEmName(): ?string
    {
        return $this->emName;
    }
    public function setEmName(string $emName): void
    {
        $this->emName = $emName;
    }
    public function getEmJob(): ?string
    {
        return $this->emJob;
    }
    public function setEmJob(string $emJob): void
    {
        $this->emJob = $emJob;
    }
    public function getEmMgr(): ?int
    {
        return $this->emMgr;
    }
    public function setEmMgr(int $emMgr): void
    {
        $this->emMgr = $emMgr;
    }
    public function getEmHiredate(): ?string
    {
        return $this->emHiredate;
    }
    public function setEmHiredate(string $emHiredate): void
    {
        $this->emHiredate = $emHiredate;
    }
    public function getEmSal(): ?int
    {
        return $this->emSal;
    }
    public function setEmSal(int $emSal): void
    {
        $this->emSal = $emSal;
    }
    public function getDpId(): ?int
    {
        return $this->dpId;
    }
    public function setDpId(int $dpId): void
    {
        $this->dpId = $dpId;
    }
}
