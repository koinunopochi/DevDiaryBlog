import React from 'react';
import { LucideIcon, Loader2 } from 'lucide-react';

interface SubmitButtonProps {
  icon?: LucideIcon;
  children: React.ReactNode;
  disabled?: boolean;
  className?: string;
  isLoading?: boolean;
}

const SubmitButton: React.FC<SubmitButtonProps> = ({
  icon: Icon,
  children,
  disabled = false,
  className = '',
  isLoading = false,
}) => {
  return (
    <button
      type="submit"
      disabled={disabled || isLoading}
      className={`w-full sm:w-auto flex items-center justify-center bg-background-secondary text-primary hover:bg-accent2 font-bold py-2 px-4 rounded transition-colors duration-200 text-sm sm:text-base ${
        disabled || isLoading ? 'opacity-50 cursor-not-allowed' : ''
      } ${className}`}
    >
      {isLoading ? (
        <Loader2 className="animate-spin mr-2" size={18} />
      ) : (
        Icon && <Icon size={18} className="mr-2" />
      )}
      {children}
    </button>
  );
};

export default SubmitButton;
