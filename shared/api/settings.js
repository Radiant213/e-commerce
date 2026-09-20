import apiClient from './client';

const settingsApi = {
  getPublicSettings: async () => {
    const response = await apiClient.get('/settings/public');
    return response.data.settings;
  },
};

export default settingsApi;
