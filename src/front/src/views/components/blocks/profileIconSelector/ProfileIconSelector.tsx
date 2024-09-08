import React, { useState, useEffect } from 'react';
import Icon from '@components/atoms/icon/Icon';

interface ProfileIconSelectorProps {
  icons: Array<string> | (() => Promise<string[]>);
  selectedIcon?: string;
  onSelectIcon: (icon: string) => void;
}

const ProfileIconSelector: React.FC<ProfileIconSelectorProps> = ({
  icons,
  selectedIcon,
  onSelectIcon,
}) => {
  const [internalSelectedIcon, setInternalSelectedIcon] = useState<string | undefined>(selectedIcon);
  const [loadedIcons, setLoadedIcons] = useState<string[]>([]);
  const [isLoading, setIsLoading] = useState<boolean>(true);

  useEffect(() => {
    setInternalSelectedIcon(selectedIcon);
  }, [selectedIcon]);

  useEffect(() => {
    const loadIcons = async () => {
      setIsLoading(true);
      try {
        let resolvedIcons: string[];
        if (Array.isArray(icons)) {
          resolvedIcons = icons;
        } else {
          resolvedIcons = await icons();
        }
        setLoadedIcons(resolvedIcons);
      } catch (error) {
        console.error('アイコンの読み込みに失敗しました', error);
        setLoadedIcons([]);
      } finally {
        setIsLoading(false);
      }
    };

    loadIcons();
  }, [icons]);

  const handleIconClick = (icon: string) => {
    setInternalSelectedIcon(icon);
    onSelectIcon(icon);
  };

  if (isLoading) {
    return <div>アイコンを読み込んでいます...</div>;
  }

  if (loadedIcons.length === 0) {
    return <div>アイコンが見つかりませんでした。</div>;
  }

  return (
    <div className="grid grid-cols-4 gap-4">
      {loadedIcons.map((icon: string, index: number) => (
        <div
          key={index}
          className="relative w-24 h-24 flex items-center justify-center"
        >
          <div
            className={`absolute inset-0 rounded-lg transition-all duration-200 ${
              internalSelectedIcon === icon
                ? 'bg-blue-100 shadow-lg'
                : 'hover:bg-gray-100'
            }`}
          />
          <Icon
            src={icon}
            alt={`Profile icon ${index + 1}`}
            size="w-20 h-20"
            shape="rounded-lg"
            isButton={true}
            onClick={() => handleIconClick(icon)}
            className={`relative z-10 transition-transform duration-200 hover:scale-105 ${
              internalSelectedIcon === icon ? 'ring-2 ring-blue-500' : ''
            }`}
          />
        </div>
      ))}
    </div>
  );
};

export default ProfileIconSelector;
