import React, { MouseEventHandler, useState } from 'react';

interface IconProps {
  src: string;
  alt: string;
  size?: 'w-6 h-6' | 'w-8 h-8' | 'w-10 h-10';
  shape?: 'rounded-full' | 'rounded-lg' | '';
  isButton?: boolean;
  onClick?: MouseEventHandler<HTMLDivElement>;
}

const Icon: React.FC<IconProps> = ({
  src,
  alt,
  size = 'w-6 h-6',
  shape = 'rounded-full',
  isButton = false,
  onClick = () => {},
}) => {
  const [isHovered, setIsHovered] = useState(false);

  const baseStyles = `bg-cover bg-center ${size} ${shape}`;
  const buttonStyles = isButton ? 'cursor-pointer' : '';
  const rippleStyles = isButton && isHovered ? 'after:animate-ripple' : '';

  return (
    <div
      className={`${baseStyles} ${buttonStyles} ${rippleStyles} relative overflow-hidden after:content-[''] after:absolute after:inset-0 after:bg-white after:opacity-0 after:rounded-full`}
      style={{ backgroundImage: `url(${src})` }}
      onClick={onClick}
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
      role={isButton ? 'button' : ''}
      tabIndex={isButton ? 0 : -1}
      aria-label={alt}
    />
  );
};

export default Icon;
