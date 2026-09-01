import apiClient from './client';

export const productsApi = {
  getProducts: async (params = {}) => {
    const response = await apiClient.get('/products', { params });
    return response.data;
  },

  getProductBySlug: async (slug) => {
    const response = await apiClient.get(`/products/${slug}`);
    return response.data;
  },

  getFeaturedProducts: async () => {
    const response = await apiClient.get('/products/featured');
    return response.data.data;
  },

  getBestSellers: async () => {
    const response = await apiClient.get('/products/best-sellers');
    return response.data.data;
  },

  getNewArrivals: async () => {
    const response = await apiClient.get('/products/new-arrivals');
    return response.data.data;
  },
};

export default productsApi;
