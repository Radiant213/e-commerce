import apiClient from './client';

export const authApi = {
  login: async (credentials) => {
    const response = await apiClient.post('/auth/login', credentials);
    return response.data;
  },

  register: async (data) => {
    const response = await apiClient.post('/auth/register', data);
    return response.data;
  },

  logout: async () => {
    const response = await apiClient.post('/auth/logout');
    return response.data;
  },

  getUser: async () => {
    const response = await apiClient.get('/auth/user');
    return response.data.user;
  },

  updateProfile: async (data) => {
    const response = await apiClient.put('/auth/profile', data);
    return response.data;
  },

  updatePassword: async (data) => {
    const response = await apiClient.put('/auth/password', data);
    return response.data;
  },
};

export default authApi;
