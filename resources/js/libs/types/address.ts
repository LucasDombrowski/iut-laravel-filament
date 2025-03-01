export interface Address {
    id: number;
    user_id: number;
    number: string;
    street: string;
    city: string;
    complement?: string;
    zip_code: string;
    country_code: string;
}