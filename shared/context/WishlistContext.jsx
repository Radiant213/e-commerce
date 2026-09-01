import React, { createContext, useContext, useState, useEffect, useCallback } from 'react';
import wishlistApi from '../api/wishlist';
import { useAuth } from './AuthContext';

const WishlistContext = createContext(null);

export const WishlistProvider = ({ children }) => {
  const { isAuthenticated } = useAuth();
  const [wishlistItems, setWishlistItems] = useState([]);
  const [wishlistedProductIds, setWishlistedProductIds] = useState(new Set());
  const [isLoading, setIsLoading] = useState(false);

  const fetchWishlist = useCallback(async () => {
    if (!isAuthenticated) {
      setWishlistItems([]);
      setWishlistedProductIds(new Set());
      return;
    }

    try {
      setIsLoading(true);
      const res = await wishlistApi.getWishlist();
      const items = res.data || [];
      setWishlistItems(items);
      setWishlistedProductIds(new Set(items.map((item) => item.product_id)));
    } catch (err) {
      console.error('Failed fetching wishlist:', err);
    } finally {
      setIsLoading(false);
    }
  }, [isAuthenticated]);

  useEffect(() => {
    fetchWishlist();
  }, [fetchWishlist]);

  const isWishlisted = (productId) => {
    return wishlistedProductIds.has(Number(productId));
  };

  const toggleWishlist = async (productId) => {
    if (!isAuthenticated) {
      throw new Error('Silakan login terlebih dahulu untuk menyimpan ke wishlist.');
    }

    try {
      const res = await wishlistApi.toggleWishlist(productId);
      setWishlistedProductIds((prev) => {
        const next = new Set(prev);
        if (res.is_wishlisted) {
          next.add(Number(productId));
        } else {
          next.delete(Number(productId));
        }
        return next;
      });
      await fetchWishlist();
      return res;
    } catch (err) {
      console.error('Error toggling wishlist:', err);
      throw err;
    }
  };

  return (
    <WishlistContext.Provider
      value={{
        wishlistItems,
        totalWishlist: wishlistItems.length,
        isLoading,
        isWishlisted,
        toggleWishlist,
        fetchWishlist,
      }}
    >
      {children}
    </WishlistContext.Provider>
  );
};

export const useWishlist = () => {
  const context = useContext(WishlistContext);
  if (!context) {
    throw new Error('useWishlist must be used within a WishlistProvider');
  }
  return context;
};

export default WishlistContext;
