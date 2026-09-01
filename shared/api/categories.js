import apiClient from './client';

export const categoriesApi = {
  getCategories: async () => {
    const response = await apiClient.get('/categories');
    return response.data.data;
  },

  getCategoryBySlug: async (slug) => {
    const response = await apiClient.get(`/categories/${slug}`);
    return response.data.data;
  },
};

export default categoriesApi;
