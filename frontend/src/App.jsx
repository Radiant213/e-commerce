import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from '@shared/context/AuthContext';
import { CartProvider } from '@shared/context/CartContext';
import { WishlistProvider } from '@shared/context/WishlistContext';
import { LanguageProvider } from '@shared/context/LanguageContext';
import { ToastProvider } from './components/Toast';

import Navbar from './components/Navbar';
import Footer from './components/Footer';
import CartDrawer from './components/CartDrawer';

import HomePage from './pages/HomePage';
import ProductsPage from './pages/ProductsPage';
import ProductDetailPage from './pages/ProductDetailPage';
import WishlistPage from './pages/WishlistPage';
import CheckoutPage from './pages/CheckoutPage';
import DashboardPage from './pages/DashboardPage';
import LoginPage from './pages/LoginPage';
import RegisterPage from './pages/RegisterPage';
import ForgotPasswordPage from './pages/ForgotPasswordPage';
import ResetPasswordPage from './pages/ResetPasswordPage';

function App() {
  return (
    <LanguageProvider>
      <AuthProvider>
        <CartProvider>
          <WishlistProvider>
            <ToastProvider>
              <Router>
                <div className="min-h-screen flex flex-col bg-[#FAFAFA] text-slate-900 selection:bg-slate-900 selection:text-white">
                  <Navbar />
                <CartDrawer />
                <main className="flex-grow">
                  <Routes>
                    <Route path="/" element={<HomePage />} />
                    <Route path="/products" element={<ProductsPage />} />
                    <Route path="/products/:slug" element={<ProductDetailPage />} />
                    <Route path="/wishlist" element={<WishlistPage />} />
                    <Route path="/checkout" element={<CheckoutPage />} />
                    <Route path="/dashboard" element={<DashboardPage />} />
                    <Route path="/orders" element={<Navigate to="/dashboard?tab=orders" replace />} />
                    <Route path="/login" element={<LoginPage />} />
                    <Route path="/register" element={<RegisterPage />} />
                    <Route path="/forgot-password" element={<ForgotPasswordPage />} />
                    <Route path="/reset-password" element={<ResetPasswordPage />} />
                  </Routes>
                </main>
                <Footer />
              </div>
            </Router>
          </ToastProvider>
        </WishlistProvider>
      </CartProvider>
    </AuthProvider>
    </LanguageProvider>
  );
}

export default App;
