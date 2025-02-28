export interface ProductVariant {
    id: number;
    color_id: number;
    size_id: number;
    product_id: number;
    thumbnail: string;
    color: ProductColor;
    size: ProductSize;
    product: Product;
    stock: number;
    price: number;
    images: ProductImage[];
}

export interface ProductColor {
    id: number;
    label: string;
    hex: string;
}

export interface ProductSize {
    id: number;
    label: string;
}

export interface ProductCategory {
    id: number;
    name: string;
    slug: string;
}

export interface Product {
    id: number;
    name: string;
    description: string;
    categories: ProductCategory[];
    variants: ProductVariant[];
}

export interface ProductImage {
    id: number;
    url: string;
    alt: string;
}