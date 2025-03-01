import { Discount } from '../types/order';
import { isAfter, isBefore, parseISO } from 'date-fns';

export function isDiscountValid(discount: Discount, cartTotal: number): boolean {
  const now = new Date();
  const startDate = parseISO(discount.start_at);
  const endDate = parseISO(discount.end_at);
  
  // Check if the current date is within the discount period
  const isWithinDateRange = isAfter(now, startDate) && isBefore(now, endDate);
  
  // Check if the cart total meets the minimum price requirement
  const meetsMinPrice = cartTotal >= discount.min_price;
  
  return isWithinDateRange && meetsMinPrice;
}

export function calculateDiscountAmount(discount: Discount, cartTotal: number): number {
  if (!isDiscountValid(discount, cartTotal)) {
    return 0;
  }
  
  if (discount.type === 'fixed') {
    return discount.value;
  } else {
    // For percentage discount
    return (cartTotal * discount.value) / 100;
  }
}