import React, { MouseEventHandler } from 'react';

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
  const baseStyles = `bg-cover bg-center ${size} ${shape}`;
  const buttonStyles = isButton ? 'cursor-pointer hover:opacity-80' : '';

  return (
    <div
      className={`${baseStyles} ${buttonStyles}`}
      style={{ backgroundImage: `url(${src})` }}
      onClick={onClick}
      role={isButton ? 'button' : ''}
      tabIndex={isButton ? 0 : -1}
      aria-label={alt}
    />
  );
};

export default Icon;
