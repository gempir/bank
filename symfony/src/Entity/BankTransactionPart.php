<?php

namespace App\Entity;

use App\Enumeration\ReasonEnumeration;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BankTransactionPartRepository")
 */
class BankTransactionPart implements \JsonSerializable
{
    const mandatoryFields = ["reason", "amount"];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reason;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BankTransaction", inversedBy="bankTransactionParts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $BankTransaction;

    /**
     * @throws \InvalidArgumentException
     * @param \stdClass $data
     * @return BankTransactionPart
     */
    public static function fromJsonObject(\stdClass $data)
    {
        self::ensureMandatoryFieldsAreGiven($data);

        $part = new self();
        $part->setReason($data->reason);
        $part->setAmount($data->amount);

        return $part;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        if (!ReasonEnumeration::isValidValue($reason)) {
            throw new \InvalidArgumentException("Invalid reason value: " . $reason);
        }

        $this->reason = $reason;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBankTransaction(): ?BankTransaction
    {
        return $this->BankTransaction;
    }

    public function setBankTransaction(?BankTransaction $BankTransaction): self
    {
        $this->BankTransaction = $BankTransaction;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "reason" => $this->getReason(),
            "amount" => $this->getAmount(),
        ];
    }

    /**
     * @param \stdClass $data
     * @throws \InvalidArgumentException
     */
    private static function ensureMandatoryFieldsAreGiven(\stdClass $data)
    {
        foreach (self::mandatoryFields as $mandatoryField) {
            if (!property_exists($data, $mandatoryField)) {
                throw new \InvalidArgumentException("Mandatory field missing: " . $mandatoryField);
            }
        }
    }
}
