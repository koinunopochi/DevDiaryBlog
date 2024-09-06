import React from 'react';
import { Loader2, CheckCircle2, XCircle, Circle } from 'lucide-react';

export type BaseStatusType = 'initial' | 'loading' | 'error' | 'success';

interface BaseStatusProps {
  status: BaseStatusType;
  size?: number;
  className?: string;
}

const BaseStatus: React.FC<BaseStatusProps> = ({
  status,
  size = 20,
  className = '',
}) => {
  const iconProps = {
    size,
    className: `${className} ${getColorClass(status)}`,
  };

  return (
    <span className="inline-flex items-center justify-center">
      {status === 'initial' && <Circle {...iconProps} />}
      {status === 'loading' && (
        <Loader2
          {...iconProps}
          className={`${iconProps.className} animate-spin`}
        />
      )}
      {status === 'error' && <XCircle {...iconProps} />}
      {status === 'success' && <CheckCircle2 {...iconProps} />}
    </span>
  );
};

function getColorClass(status: BaseStatusType): string {
  switch (status) {
    case 'initial':
      return 'text-gray-300';
    case 'loading':
      return 'text-blue-500';
    case 'error':
      return 'text-red-500';
    case 'success':
      return 'text-green-500';
    default:
      return '';
  }
}

export default BaseStatus;
