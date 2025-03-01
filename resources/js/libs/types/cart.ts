import { ProductVariant } from "./product";

export interface CartItem {
    variant: ProductVariant
    quantity: number;
}
  