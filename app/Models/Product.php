<?php

namespace App\Models;

final class Product
{
    /**
     * @param string $name
     * @param string $description
     * @param float $price
     * @param ProductType $type
     * @param Supplier[] $suppliers
     * @param int $stock
     * @param DiscountType|null $discount
     */
    public function __construct(
        private string        $name,
        private string        $description,
        private float         $price,
        private ProductType   $type,
        private array         $suppliers,
        private int           $stock = 0,
        private ?DiscountType $discount = null,
    )
    {
    }

    public function getSuppliers(): array
    {
        return $this->suppliers;
    }

    public function setSuppliers(array $suppliers): void
    {
        $this->suppliers = $suppliers;
    }

    public function getDiscount(): DiscountType
    {
        return $this->discount;
    }

    public function setDiscount(DiscountType $discount): void
    {
        $this->discount = $discount;
    }

    public function getType(): ProductType
    {
        return $this->type;
    }

    public function setType(ProductType $type): void
    {
        $this->type = $type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function addStock(int $stock): void
    {
        $this->stock += $stock;
    }

    public function removeStock(int $stock): void
    {
        $this->stock -= $stock;
    }
}