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
      {icons.map((icon, index) => (
        <div
          key={index}
          className={`p-1 rounded-lg transition-all duration-200 hover:scale-110 ${
            internalSelectedIcon === icon
              ? 'bg-blue-100 shadow-lg'
              : 'bg-transparent'
          }`}
        >
          <Icon
            src={icon}
            alt={`Profile icon ${index + 1}`}
            size="w-20 h-20"
            shape="rounded-lg"
            isButton={true}
            onClick={() => handleIconClick(icon)}
            className={`${
              internalSelectedIcon === icon ? 'ring-2 ring-blue-500' : ''
            }`}
          />
        </div>
      ))}
    </div>
  );
};

export default ProfileIconSelector;
