import apiClient from './client';

export const cmsApi = {
  getBootstrap: (lang = 'id') => apiClient.get('/cms/bootstrap', { params: { lang } }),
  getSettings: (lang = 'id') => apiClient.get('/cms/settings', { params: { lang } }),
  getHomepage: (lang = 'id') => apiClient.get('/cms/homepage', { params: { lang } }),
  getMenu: (location, lang = 'id') => apiClient.get(`/cms/menus/${location}`, { params: { lang } }),
  getPages: (lang = 'id') => apiClient.get('/cms/pages', { params: { lang } }),
  getPage: (slug, lang = 'id') => apiClient.get(`/cms/pages/${slug}`, { params: { lang } }),
  getBanners: (placement = 'homepage', lang = 'id') => apiClient.get(`/cms/banners/${placement}`, { params: { lang } }),
  getFooter: (lang = 'id') => apiClient.get('/cms/footer', { params: { lang } }),
  getPopups: (lang = 'id') => apiClient.get('/cms/popups', { params: { lang } }),
};

export default cmsApi;
