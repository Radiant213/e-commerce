import apiClient from './client';

export const paymentsApi = {
  getSnapToken: async (orderId) => {
    const response = await apiClient.get(`/payments/${orderId}/snap-token`);
    return response.data;
  },

  checkStatus: async (orderId) => {
    const response = await apiClient.get(`/payments/${orderId}/status`);
    return response.data;
  },
};

export default paymentsApi;
