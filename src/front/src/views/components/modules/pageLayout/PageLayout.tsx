import React from 'react';
import BasePageLayout from '@components/modules/basePageLayout/BasePageLayout';
import { Header } from '@components/blocks/header/Header';
import { Outlet, useNavigate } from 'react-router-dom';
import AuthService from '@/services/AuthService';
import TabNavigation from '@components/atoms/tabNavigation/TabNavigation';

interface PageLayoutProps {
  authService: AuthService;
}

const PageLayout: React.FC<PageLayoutProps> = ({ authService }) => {
  const navigate = useNavigate();

  const handleLogout = async () => {
    await authService.logout();
    navigate('/login');
  };

  return (
    <BasePageLayout className="flex flex-col">
      <Header
        onLogin={() => navigate('/login')}
        onLogout={handleLogout}
        onCreateAccount={() => navigate('/register')}
      />
      <TabNavigation />
      <main className="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-background-main text-primary">
        <Outlet />
      </main>
      <footer className="py-4 bg-background-secondary text-accent1">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
          © {new Date().getFullYear()} Your Company Name. All rights reserved.
        </div>
      </footer>
    </BasePageLayout>
  );
};

export default PageLayout;
