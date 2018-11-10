<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BankTransactionRepository")
 */
class BankTransaction implements \JsonSerializable
{
    const mandatoryFields = ["amount", "booking_date", "parts"];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $uuid;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $booking_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BankTransactionPart", mappedBy="BankTransaction", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $bankTransactionParts;

    public function __construct()
    {
        $this->bankTransactionParts = new ArrayCollection();
    }

    /**
     * @throws \Exception
     * @param \stdClass $data
     * @return BankTransaction
     */
    public static function fromJsonObject(\stdClass $data)
    {
        self::ensureMandatoryFieldsAreGiven($data);

        $transaction = new self();
        $transaction->setAmount($data->amount);
        $transaction->setUuid(Uuid::uuid4());
        $transaction->setBookingDate(\DateTime::createFromFormat("Y-m-d H:i:s", $data->booking_date));

        foreach ($data->parts as $part) {
            $transactionPart = BankTransactionPart::fromJsonObject($part);
            $transactionPart->setBankTransaction($transaction);

            $transaction->addBankTransactionPart($transactionPart);
        }

        return $transaction;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

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

    public function getBookingDate(): ?\DateTimeInterface
    {
        return $this->booking_date;
    }

    public function setBookingDate(\DateTimeInterface $booking_date): self
    {
        $this->booking_date = $booking_date;

        return $this;
    }

    /**
     * @return Collection|BankTransactionPart[]
     */
    public function getBankTransactionParts(): Collection
    {
        return $this->bankTransactionParts;
    }

    public function addBankTransactionPart(BankTransactionPart $bankTransactionPart): self
    {
        if (!$this->bankTransactionParts->contains($bankTransactionPart)) {
            $this->bankTransactionParts[] = $bankTransactionPart;
            $bankTransactionPart->setBankTransaction($this);
        }

        return $this;
    }

    public function removeBankTransactionPart(BankTransactionPart $bankTransactionPart): self
    {
        if ($this->bankTransactionParts->contains($bankTransactionPart)) {
            $this->bankTransactionParts->removeElement($bankTransactionPart);
            // set the owning side to null (unless already changed)
            if ($bankTransactionPart->getBankTransaction() === $this) {
                $bankTransactionPart->setBankTransaction(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "amount" => $this->getAmount(),
            "booking_date" => $this->getBookingDate()->format("Y-m-d H:i:s"),
            "parts" => $this->getBankTransactionParts()->toArray(),
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
