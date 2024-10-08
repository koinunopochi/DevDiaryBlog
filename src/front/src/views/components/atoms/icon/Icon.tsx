import React, { MouseEventHandler, useState, useEffect } from 'react';
import defaultIconError from '@img/default-icon-error.png';

interface IconProps {
  src: string;
  alt: string;
  size?:
    | 'w-6 h-6'
    | 'w-8 h-8'
    | 'w-10 h-10'
    | 'w-20 h-20'
    | 'w-5 h-5 sm:w-6 sm:h-6'
    | 'w-16 h-16 sm:w-20 sm:h-20';
  shape?: 'rounded-full' | 'rounded-lg' | '';
  isButton?: boolean;
  onClick?: MouseEventHandler<HTMLDivElement>;
  href?: string;
  className?: string;
  defaultSrc?: string;
  errorMessage?: string;
}

const Icon: React.FC<IconProps> = ({
  src,
  alt,
  size = 'w-6 h-6',
  shape = 'rounded-full',
  isButton = false,
  onClick = () => {},
  href,
  className = '',
  defaultSrc = defaultIconError,
  errorMessage = '画像の取得に失敗しました',
}) => {
  const [isHovered, setIsHovered] = useState(false);
  const [imageSrc, setImageSrc] = useState(src);
  const [hasError, setHasError] = useState(false);

  const displayErrorMessage =
    src === '' ? '画像が選択されていません' : errorMessage;

  useEffect(() => {
    const img = new Image();
    img.src = src;
    img.onload = () => {
      setImageSrc(src);
      setHasError(false);
    };
    img.onerror = () => {
      setImageSrc(defaultSrc);
      setHasError(true);
    };
  }, [src, defaultSrc]);

  const baseStyles = `bg-cover bg-center ${size} ${shape}`;
  const buttonStyles = isButton ? 'cursor-pointer' : '';
  const rippleStyles = isButton && isHovered ? 'after:animate-ripple' : '';

  const icon = (
    <div className="flex flex-col items-center">
      <div
        className={`${baseStyles} ${buttonStyles} ${rippleStyles} relative overflow-hidden after:content-[''] after:absolute after:inset-0 after:bg-white dark:after:bg-night-sky after:opacity-0 after:rounded-full ${className}`}
        style={{ backgroundImage: `url(${imageSrc})` }}
        onClick={onClick}
        onMouseEnter={() => setIsHovered(true)}
        onMouseLeave={() => setIsHovered(false)}
        role={isButton ? 'button' : undefined}
        tabIndex={isButton ? 0 : undefined}
        aria-label={alt}
      />
      {hasError && (
        <p className="text-red-500 dark:text-red-400 mt-2 text-sm text-center max-w-[200px]">
          {displayErrorMessage}
        </p>
      )}
    </div>
  );

  return href ? (
    <a href={href} target="_blank" rel="noopener noreferrer">
      {icon}
    </a>
  ) : (
    icon
  );
};

export default Icon;
