import axios from 'axios';

interface AddressAPIResponse {
  features: {
    properties: {
      label: string;
      housenumber: string;
      street: string;
      postcode: string;
      city: string;
      context: string;
    };
  }[];
}

export const searchAddress = async (query: string): Promise<any[]> => {
  try {
    const response = await axios.get<AddressAPIResponse>(
      `https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(query)}&limit=5`
    );
    
    return response.data.features.map(feature => ({
      label: feature.properties.label,
      number: feature.properties.housenumber || '',
      street: feature.properties.street || '',
      zipCode: feature.properties.postcode,
      city: feature.properties.city,
    }));
  } catch (error) {
    console.error('Error searching address:', error);
    return [];
  }
};