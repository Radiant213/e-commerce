import apiClient from './client';

export const wishlistApi = {
  getWishlist: async (page = 1) => {
    const response = await apiClient.get('/wishlist', { params: { page } });
    return response.data;
  },

  toggleWishlist: async (productId) => {
    const response = await apiClient.post('/wishlist/toggle', {
      product_id: productId,
    });
    return response.data;
  },

  removeFromWishlist: async (productId) => {
    const response = await apiClient.delete(`/wishlist/${productId}`);
    return response.data;
  },
};

export default wishlistApi;
