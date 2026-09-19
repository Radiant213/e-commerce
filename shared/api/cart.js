import apiClient from './client';

export const cartApi = {
  getCart: async () => {
    const response = await apiClient.get('/cart');
    return response.data;
  },

  addItem: async (productId, quantity = 1, variantId = null) => {
    const payload = {
      product_id: productId,
      quantity,
    };
    if (variantId) {
      payload.variant_id = variantId;
    }
    const response = await apiClient.post('/cart/items', payload);
    return response.data;
  },

  updateItem: async (cartItemId, quantity) => {
    const response = await apiClient.put(`/cart/items/${cartItemId}`, {
      quantity,
    });
    return response.data;
  },

  removeItem: async (cartItemId) => {
    const response = await apiClient.delete(`/cart/items/${cartItemId}`);
    return response.data;
  },

  clearCart: async () => {
    const response = await apiClient.delete('/cart/clear');
    return response.data;
  },
};

export default cartApi;
