import React from 'react';
import {
  ChevronLeft,
  ChevronRight,
  ChevronsLeft,
  ChevronsRight,
} from 'lucide-react';

interface PaginationProps {
  currentPage: number;
  totalPages: number;
  onPageChange: (page: number) => void;
}

const Pagination: React.FC<PaginationProps> = ({
  currentPage,
  totalPages,
  onPageChange,
}) => {
  const getPageNumbers = () => {
    const pageNumbers = [];
    const maxVisiblePages = 5;
    const halfVisible = Math.floor(maxVisiblePages / 2);

    if (totalPages <= maxVisiblePages + 2) {
      for (let i = 1; i <= totalPages; i++) {
        pageNumbers.push(i);
      }
    } else {
      pageNumbers.push(1);

      if (currentPage <= halfVisible + 1) {
        for (let i = 2; i <= maxVisiblePages; i++) {
          pageNumbers.push(i);
        }
        pageNumbers.push('...');
      } else if (currentPage >= totalPages - halfVisible) {
        pageNumbers.push('...');
        for (let i = totalPages - maxVisiblePages + 1; i < totalPages; i++) {
          pageNumbers.push(i);
        }
      } else {
        pageNumbers.push('...');
        for (
          let i = currentPage - halfVisible + 1;
          i <= currentPage + halfVisible - 1;
          i++
        ) {
          pageNumbers.push(i);
        }
        pageNumbers.push('...');
      }

      pageNumbers.push(totalPages);
    }

    return pageNumbers;
  };

  const pageNumbers = getPageNumbers();

  const buttonBaseClass =
    'p-2 rounded-full transition-all duration-200 ease-in-out';
  const buttonEnabledClass = 'bg-gray-200 text-gray-600 hover:bg-gray-300';
  const buttonDisabledClass = 'bg-gray-100 text-gray-400 cursor-not-allowed';

  return (
    <nav className="flex justify-center items-center space-x-2 mt-4">
      <button
        onClick={() => onPageChange(1)}
        disabled={currentPage === 1}
        className={`${buttonBaseClass} ${currentPage === 1 ? buttonDisabledClass : buttonEnabledClass}`}
        aria-label="最初のページへ"
      >
        <ChevronsLeft size={20} />
      </button>
      <button
        onClick={() => onPageChange(currentPage - 1)}
        disabled={currentPage === 1}
        className={`${buttonBaseClass} ${currentPage === 1 ? buttonDisabledClass : buttonEnabledClass}`}
        aria-label="前のページへ"
      >
        <ChevronLeft size={20} />
      </button>
      {pageNumbers.map((number, index) => (
        <button
          key={index}
          onClick={() => typeof number === 'number' && onPageChange(number)}
          className={`px-3 py-1 rounded-full transition-all duration-200 ease-in-out ${
            currentPage === number
              ? 'bg-blue-500 text-white  cursor-not-allowed'
              : number === '...'
                ? 'bg-transparent text-gray-600 cursor-default'
                : 'bg-gray-200 text-gray-600 hover:bg-gray-300'
          }`}
          disabled={number === '...' || number === currentPage}
        >
          {number}
        </button>
      ))}
      <button
        onClick={() => onPageChange(currentPage + 1)}
        disabled={currentPage === totalPages}
        className={`${buttonBaseClass} ${currentPage === totalPages ? buttonDisabledClass : buttonEnabledClass}`}
        aria-label="次のページへ"
      >
        <ChevronRight size={20} />
      </button>
      <button
        onClick={() => onPageChange(totalPages)}
        disabled={currentPage === totalPages}
        className={`${buttonBaseClass} ${currentPage === totalPages ? buttonDisabledClass : buttonEnabledClass}`}
        aria-label="最後のページへ"
      >
        <ChevronsRight size={20} />
      </button>
    </nav>
  );
};

export default Pagination;
