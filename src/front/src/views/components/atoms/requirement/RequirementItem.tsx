import React from 'react';
import BaseStatus from '@components/atoms/status/BaseStatus';

interface RequirementItemProps {
  met: boolean;
  isInitial: boolean;
  children: React.ReactNode;
}

const RequirementItem: React.FC<RequirementItemProps> = ({
  met,
  isInitial,
  children,
}) => (
  <li className="flex items-center space-x-2">
    <BaseStatus
      status={isInitial ? 'initial' : met ? 'success' : 'error'}
      size={16}
    />
    <span
      className={
        isInitial ? 'text-gray-400' : met ? 'text-green-600' : 'text-gray-600'
      }
    >
      {children}
    </span>
  </li>
);

export default RequirementItem;
