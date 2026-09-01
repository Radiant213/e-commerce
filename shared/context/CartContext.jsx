import React, { createContext, useContext, useState, useEffect, useCallback } from 'react';
import cartApi from '../api/cart';
import { useAuth } from './AuthContext';

const CartContext = createContext(null);

export const CartProvider = ({ children }) => {
  const { isAuthenticated } = useAuth();
  const [cart, setCart] = useState(null);
  const [items, setItems] = useState([]);
  const [total, setTotal] = useState(0);
  const [totalItems, setTotalItems] = useState(0);
  const [isLoading, setIsLoading] = useState(false);
  const [isDrawerOpen, setIsDrawerOpen] = useState(false);

  const fetchCart = useCallback(async () => {
    if (!isAuthenticated) {
      setCart(null);
      setItems([]);
      setTotal(0);
      setTotalItems(0);
      return;
    }

    try {
      setIsLoading(true);
      const data = await cartApi.getCart();
      setCart(data.cart);
      setItems(data.items || []);
      setTotal(data.total || 0);
      setTotalItems(data.total_items || 0);
    } catch (err) {
      console.error('Failed fetching cart:', err);
    } finally {
      setIsLoading(false);
    }
  }, [isAuthenticated]);

  useEffect(() => {
    fetchCart();
  }, [fetchCart]);

  const addToCart = async (productId, quantity = 1) => {
    if (!isAuthenticated) {
      throw new Error('Silakan login terlebih dahulu untuk menambahkan produk ke keranjang.');
    }

    try {
      setIsLoading(true);
      const res = await cartApi.addItem(productId, quantity);
      await fetchCart();
      setIsDrawerOpen(true);
      return res;
    } finally {
      setIsLoading(false);
    }
  };

  const updateQuantity = async (cartItemId, quantity) => {
    try {
      setIsLoading(true);
      const res = await cartApi.updateItem(cartItemId, quantity);
      await fetchCart();
      return res;
    } finally {
      setIsLoading(false);
    }
  };

  const removeItem = async (cartItemId) => {
    try {
      setIsLoading(true);
      const res = await cartApi.removeItem(cartItemId);
      await fetchCart();
      return res;
    } finally {
      setIsLoading(false);
    }
  };

  const clearCart = async () => {
    try {
      setIsLoading(true);
      const res = await cartApi.clearCart();
      setCart(null);
      setItems([]);
      setTotal(0);
      setTotalItems(0);
      return res;
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <CartContext.Provider
      value={{
        cart,
        items,
        total,
        totalItems,
        isLoading,
        isDrawerOpen,
        setIsDrawerOpen,
        fetchCart,
        addToCart,
        updateQuantity,
        removeItem,
        clearCart,
      }}
    >
      {children}
    </CartContext.Provider>
  );
};

export const useCart = () => {
  const context = useContext(CartContext);
  if (!context) {
    throw new Error('useCart must be used within a CartProvider');
  }
  return context;
};

export default CartContext;
