import apiClient from './client';

export const ordersApi = {
  getOrders: async (page = 1) => {
    const response = await apiClient.get('/orders', { params: { page } });
    return response.data;
  },

  getOrderById: async (id) => {
    const response = await apiClient.get(`/orders/${id}`);
    return response.data.order;
  },

  createOrder: async (checkoutData) => {
    const response = await apiClient.post('/orders', checkoutData);
    return response.data;
  },

  cancelOrder: async (id) => {
    const response = await apiClient.put(`/orders/${id}/cancel`);
    return response.data;
  },
};

export default ordersApi;
