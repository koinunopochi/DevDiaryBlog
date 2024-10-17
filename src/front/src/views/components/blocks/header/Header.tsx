import React, { useEffect, useState } from 'react';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/react';
import { User, LogOut, ChevronDown } from 'lucide-react';
import { twMerge } from 'tailwind-merge';
import { useNavigate } from 'react-router-dom';
import WithCharacterLog from '@/img/with_character_logo.svg';
import ThemeToggle from '@/views/components/atoms/themeToggle/ThemeToggle';
import { useTheme } from '@/contexts/ThemeContext';

type UserProfile = {
  displayName: string;
  bio: string;
  avatarUrl: string;
  socialLinks: string[];
};

export interface HeaderProps {
  onLogin?: () => void;
  onLogout?: () => void;
  onCreateAccount?: () => void;
}

const Avatar = ({
  src,
  alt,
  fallback,
}: {
  src: string | undefined;
  alt: string | undefined;
  fallback: React.ReactNode;
}) => (
  <div className="h-8 w-8 rounded-full overflow-hidden bg-gray-200">
    {src ? (
      <img src={src} alt={alt} className="h-full w-full object-cover" />
    ) : (
      <div className="h-full w-full flex items-center justify-center text-gray-500">
        {fallback}
      </div>
    )}
  </div>
);

export const Header = ({ onLogin, onLogout, onCreateAccount }: HeaderProps) => {
  const [userProfile, setUserProfile] = useState<UserProfile | null>(null);
  const navigate = useNavigate();
  const { theme } = useTheme();

  useEffect(() => {
    const storedProfile = localStorage.getItem('profile');
    if (storedProfile) {
      try {
        setUserProfile(JSON.parse(storedProfile));
      } catch (error) {
        console.error('Error parsing profile from localStorage:', error);
      }
    }
  }, []);

  const toMyPage = () => {
    const user = localStorage.getItem('user');
    if (user) {
      try {
        const { name } = JSON.parse(user);
        if (name) {
          navigate(`/${name}`);
        } else {
          throw new Error('User name not found');
        }
      } catch (error) {
        console.error('Error parsing user from localStorage:', error);
        alert('エラーが発生しました。ログインしてください。');
      }
    } else {
      alert('マイページにアクセスするにはログインしてください。');
    }
  };

  const menuItemClass = ({ active }: { active: boolean }) =>
    twMerge(
      'flex items-center w-full text-left px-4 py-2 text-sm',
      active
        ? 'bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-gray-100'
        : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600'
    );

  const buttonBaseStyle = `
    px-4 py-2 text-sm font-medium rounded-md
    focus:outline-none focus:ring-2 focus:ring-offset-2
    transition-colors duration-200
  `;

  return (
    <header className="border-b-2 ">
      <div className="mx-auto px-4 sm:px-6 lg:px-8 py-2 flex justify-between items-center">
        <div className="flex items-center">
          <img src={WithCharacterLog} alt="ロゴ" width={130} height={100} />
        </div>
        <div>
          {userProfile ? (
            <Menu as="div" className="relative inline-block text-left">
              <MenuButton className="inline-flex items-center justify-center w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                <Avatar
                  src={userProfile.avatarUrl}
                  alt={userProfile.displayName}
                  fallback={userProfile.displayName.charAt(0)}
                />
                <span className="ml-2">{userProfile.displayName}</span>
                <ChevronDown className="ml-2 h-5 w-5" aria-hidden="true" />
              </MenuButton>

              <MenuItems className="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                <div className="py-1">
                  <MenuItem>
                    {({ active }) => (
                      <button
                        onClick={toMyPage}
                        className={menuItemClass({ active })}
                      >
                        <User className="mr-2" />
                        マイページ
                      </button>
                    )}
                  </MenuItem>
                  <MenuItem>
                    {({ active }) => (
                      <ThemeToggle className={menuItemClass({ active })} />
                    )}
                  </MenuItem>
                  <MenuItem>
                    {({ active }) => (
                      <button
                        onClick={onLogout}
                        className={menuItemClass({ active })}
                      >
                        <LogOut className="mr-2" />
                        ログアウト
                      </button>
                    )}
                  </MenuItem>
                </div>
              </MenuItems>
            </Menu>
          ) : (
            <div className="flex flex-wrap items-center gap-2">
              <button>
                <ThemeToggle className="ml-2" />
              </button>
              <button
                onClick={onLogin}
                className={`${buttonBaseStyle} hover:opacity-90`}
                style={{
                  backgroundColor: theme.colors.primary,
                  color: theme.colors.textInverted,
                  border: `1px solid ${theme.colors.primary}`,
                }}
              >
                ログイン
              </button>
              <button
                onClick={onCreateAccount}
                className={`${buttonBaseStyle} hover:bg-opacity-10`}
                style={{
                  backgroundColor: 'transparent',
                  color: theme.colors.primary,
                  border: `1px solid ${theme.colors.primary}`,
                }}
              >
                新規登録
              </button>
            </div>
          )}
        </div>
      </div>
    </header>
  );
};
