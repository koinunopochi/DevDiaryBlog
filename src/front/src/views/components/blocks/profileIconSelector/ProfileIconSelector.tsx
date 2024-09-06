import React, { useState, useEffect } from 'react';
import Icon from '@components/atoms/icon/Icon';

interface ProfileIconSelectorProps {
  icons: string[];
  selectedIcon?: string;
  onSelectIcon: (icon: string) => void;
}

const ProfileIconSelector: React.FC<ProfileIconSelectorProps> = ({
  icons,
  selectedIcon,
  onSelectIcon,
}) => {
  const [internalSelectedIcon, setInternalSelectedIcon] = useState<
    string | undefined
  >(selectedIcon);

  useEffect(() => {
    setInternalSelectedIcon(selectedIcon);
  }, [selectedIcon]);

  const handleIconClick = (icon: string) => {
    setInternalSelectedIcon(icon);
    onSelectIcon(icon);
  };

  return (
    <div className="grid grid-cols-4 gap-4">
      {icons.map((icon:string, index:number) => (
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
