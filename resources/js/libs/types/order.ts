import { ProductVariant } from "./product";

export interface Order{
    id: number;
    user_id: number;
    discount_id?: number;
    status: string;
    address_number: string;
    address_street: string;
    address_city: string;
    address_complement?: string;
    address_zip_code: string;
    address_country_code: string;
    created_at: string;
    updated_at: string;
    variants: OrderItem[];
    discount?: Discount;
}

export interface OrderItem{
    variant: ProductVariant;
    quantity; number;
    unit_price: number;
}

export interface Discount{
    code: string;
    start_at: string;
    end_at: string;
    min_price: number;
    type: "percentage" | "fixed";
    value: number;
}