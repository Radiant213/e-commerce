import apiClient from './client';

export const reviewsApi = {
  getProductReviews: async (productId, page = 1) => {
    const response = await apiClient.get(`/products/${productId}/reviews`, {
      params: { page },
    });
    return response.data;
  },

  submitReview: async (productId, reviewData) => {
    const response = await apiClient.post(`/products/${productId}/reviews`, reviewData);
    return response.data;
  },

  deleteReview: async (reviewId) => {
    const response = await apiClient.delete(`/reviews/${reviewId}`);
    return response.data;
  },
};

export default reviewsApi;
