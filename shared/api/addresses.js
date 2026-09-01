import client from './client';

export const addressesApi = {
  getAddresses: async () => {
    const response = await client.get('/addresses');
    return response.data;
  },

  createAddress: async (addressData) => {
    const response = await client.post('/addresses', addressData);
    return response.data;
  },

  updateAddress: async (id, addressData) => {
    const response = await client.put(`/addresses/${id}`, addressData);
    return response.data;
  },

  deleteAddress: async (id) => {
    const response = await client.delete(`/addresses/${id}`);
    return response.data;
  },

  setPrimaryAddress: async (id) => {
    const response = await client.post(`/addresses/${id}/primary`);
    return response.data;
  },
};

export default addressesApi;
