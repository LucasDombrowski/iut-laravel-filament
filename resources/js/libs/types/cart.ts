import { ProductVariant } from "./product";

export interface CartItem {
    variant: ProductVariant
    quantity: number;
}
  
  export interface PromoCode {
    code: string;
    discount: number; // percentage discount
    isValid: boolean;
  }