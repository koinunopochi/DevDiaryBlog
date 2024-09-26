import React from 'react';

interface LinkCardProps {
  url: string;
  imageUrl: string | null;
  title: string | null;
}

const LinkCard: React.FC<LinkCardProps> = ({ url, imageUrl, title }) => {
  return (
    <a
      href={url}
      target="_blank"
      rel="noopener noreferrer"
      className="block w-full mx-auto my-4 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 transition-colors duration-300"
    >
      <div className="flex">
        <div className="flex-grow p-2 sm:p-3 min-w-0 flex flex-col justify-between">
          <h3 className="text-sm sm:text-base font-semibold text-gray-800 dark:text-white break-words line-clamp-2 sm:line-clamp-3 mb-1">
            {title || 'No title available'}
          </h3>
          <p className="text-xs text-gray-400 dark:text-gray-500 truncate">
            {url.length > 40 ? url.slice(0, 37) + '...' : url}
          </p>
        </div>
        <div className="w-px bg-gray-200 dark:bg-gray-700"></div>
        <div className="flex-shrink-0 w-16 sm:w-20">
          {imageUrl ? (
            <img
              src={imageUrl}
              alt={title || 'Link image'}
              className="w-full h-full object-cover object-center"
            />
          ) : (
            <div className="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-700">
              <span className="text-xs sm:text-sm text-gray-400 dark:text-gray-500">
                No Image
              </span>
            </div>
          )}
        </div>
      </div>
    </a>
  );
};

export default LinkCard;
