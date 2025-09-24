<?php

namespace App\Enums;

enum OrderStatus: int
{
    case SHIPPING = 1;
    case RESERVED_READY_TO_SHIP = 5;
    case SHIPPING_COST_UNCLEAR = 20;
    case PAUSED = 30;
    case PAUSED_PARTIAL_DELIVERY = 35;
    case ADDRESS_ERROR = 50;
    case US24_REFUND = 100;
    case US24_CHARGEBACK = 110;
    case INFO_NEW_PRODUCT_VARIANT = 140;
    case RETURN = 150;
    case GOODS_RECEIPT = 200;
    case WAREHOUSE_PICKUP = 210;
    case INVENTORY_CHANGE_INTERNAL = 300;
    case DELIVERY_DELETED = 333;
    case LSE_RESERVED = 500;
    case LSE_SHIPPING_COST_UNCLEAR = 2000;
    case LSE_PAUSED = 3000;
    case LSE_GOODS_RECEIPT = 20000;
    case LSE_STOCK_ADJUSTMENT_PLUS = 30000;
    case LSE_STOCK_ADJUSTMENT_MINUS = 31000;
    case LSE_PRODUCTION_ORDER = 32000;

    public function label(): string
    {
        return match ($this) {
            self::SHIPPING => 'Shipping',
            self::RESERVED_READY_TO_SHIP => 'Reserved, ready for shipment',
            self::SHIPPING_COST_UNCLEAR => 'Shipping cost unclear',
            self::PAUSED => 'Paused',
            self::PAUSED_PARTIAL_DELIVERY => 'Paused, partial delivery',
            self::ADDRESS_ERROR => 'Address error',
            self::US24_REFUND => 'US24 Refund',
            self::US24_CHARGEBACK => 'US24 Chargeback',
            self::INFO_NEW_PRODUCT_VARIANT => 'Info: new product variant',
            self::RETURN => 'Return',
            self::GOODS_RECEIPT => 'Goods receipt',
            self::WAREHOUSE_PICKUP => 'Warehouse pickup',
            self::INVENTORY_CHANGE_INTERNAL => 'Inventory change, internal adjustment',
            self::DELIVERY_DELETED => 'Delivery deleted',
            self::LSE_RESERVED => 'LSE Reserved',
            self::LSE_SHIPPING_COST_UNCLEAR => 'LSE Shipping cost unclear',
            self::LSE_PAUSED => 'LSE Paused',
            self::LSE_GOODS_RECEIPT => 'LSE Goods receipt',
            self::LSE_STOCK_ADJUSTMENT_PLUS => 'LSE Inventory adjustment +',
            self::LSE_STOCK_ADJUSTMENT_MINUS => 'LSE Inventory adjustment –',
            self::LSE_PRODUCTION_ORDER => 'LSE Production order',
        };
    }




    public function bootstrapClass(): string
    {
        return match ($this) {
            self::SHIPPING,
            self::RESERVED_READY_TO_SHIP,
            self::GOODS_RECEIPT,
            self::WAREHOUSE_PICKUP,
            self::LSE_GOODS_RECEIPT => 'bg-success',

            self::RETURN,
            self::US24_REFUND,
            self::US24_CHARGEBACK => 'bg-danger',

            self::SHIPPING_COST_UNCLEAR,
            self::LSE_SHIPPING_COST_UNCLEAR,
            self::ADDRESS_ERROR => 'bg-warning',

            self::PAUSED,
            self::PAUSED_PARTIAL_DELIVERY,
            self::LSE_PAUSED => 'bg-secondary',

            self::INVENTORY_CHANGE_INTERNAL,
            self::LSE_STOCK_ADJUSTMENT_PLUS,
            self::LSE_STOCK_ADJUSTMENT_MINUS,
            self::LSE_PRODUCTION_ORDER => 'bg-info',

            self::INFO_NEW_PRODUCT_VARIANT => 'bg-primary',

            self::DELIVERY_DELETED => 'bg-dark text-white',

            self::LSE_RESERVED => 'bg-light text-dark border border-dark',

            default => 'bg-light',
        };
    }


    public function icon(): string
    {
        return match ($this) {
            // Shipping-related
            self::SHIPPING,
            self::WAREHOUSE_PICKUP,
            self::GOODS_RECEIPT,
            self::LSE_GOODS_RECEIPT => 'bi-truck',

            // Reserved
            self::RESERVED_READY_TO_SHIP,
            self::LSE_RESERVED => 'bi-clock',

            // Shipping cost unclear
            self::SHIPPING_COST_UNCLEAR,
            self::LSE_SHIPPING_COST_UNCLEAR => 'bi-question-circle',

            // Paused
            self::PAUSED,
            self::PAUSED_PARTIAL_DELIVERY,
            self::LSE_PAUSED => 'bi-pause-circle',

            // Address error
            self::ADDRESS_ERROR => 'bi-geo-alt-exclamation',

            // Refund / Chargeback
            self::US24_REFUND => 'bi-arrow-counterclockwise',
            self::US24_CHARGEBACK => 'bi-arrow-repeat',

            // New product variant info
            self::INFO_NEW_PRODUCT_VARIANT => 'bi-info-circle',

            // Returns
            self::RETURN => 'bi-arrow-return-left',

            // Inventory adjustments
            self::INVENTORY_CHANGE_INTERNAL => 'bi-box-seam',
            self::LSE_STOCK_ADJUSTMENT_PLUS => 'bi-plus-circle',
            self::LSE_STOCK_ADJUSTMENT_MINUS => 'bi-dash-circle',

            // Production order
            self::LSE_PRODUCTION_ORDER => 'bi-gear',

            // Delivery deleted
            self::DELIVERY_DELETED => 'bi-trash',

            // Fallback
            default => 'bi-info-circle',
        };
    }





    public static function forCategory(string $category): array
    {
        return match ($category) {
            // “Active Orders” – anything that’s been placed but not yet completed
            'active' => [
                self::SHIPPING->value,                   // 1
                self::RESERVED_READY_TO_SHIP->value,     // 5
                self::SHIPPING_COST_UNCLEAR->value,      // 20
                self::PAUSED->value,                     // 30
                self::PAUSED_PARTIAL_DELIVERY->value,    // 35
                self::ADDRESS_ERROR->value,              // 50
                self::LSE_RESERVED->value,               // 500
                self::LSE_SHIPPING_COST_UNCLEAR->value,  // 2000
                self::LSE_PAUSED->value,                 // 3000
            ],

            // “Completed Orders” – successfully received in warehouse
            'completed' => [
                self::GOODS_RECEIPT->value,      // 200
                self::WAREHOUSE_PICKUP->value,   // 210
                self::LSE_GOODS_RECEIPT->value,  // 20000
            ],

            // “Pending Deliveries” – about to ship or waiting on shipping details
            'pending_delivery' => [
                self::RESERVED_READY_TO_SHIP->value,  // 5
                self::SHIPPING->value,                // 1
                self::SHIPPING_COST_UNCLEAR->value,   // 20
            ],

            default => [],
        };
    }
}
