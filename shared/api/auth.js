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

  loginWithGoogle: async ({ credential, access_token }) => {
    const response = await apiClient.post('/auth/google/token', {
      credential,
      access_token,
    });
    return response.data;
  },

  getGoogleRedirectUrl: async () => {
    const response = await apiClient.get('/auth/google/redirect');
    return response.data.url;
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
    const isFormData = data instanceof FormData;
    const response = await apiClient.post('/auth/profile', data, {
      headers: isFormData ? { 'Content-Type': 'multipart/form-data' } : undefined,
    });
    return response.data;
  },

  updateAvatar: async (fileOrUrl) => {
    if (fileOrUrl instanceof File) {
      const formData = new FormData();
      formData.append('avatar_file', fileOrUrl);
      const response = await apiClient.post('/auth/profile', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      });
      return response.data;
    } else {
      const response = await apiClient.post('/auth/profile', { avatar: fileOrUrl });
      return response.data;
    }
  },

  updatePassword: async (data) => {
    const response = await apiClient.put('/auth/password', data);
    return response.data;
  },
};

export default authApi;

