import React from 'react';
import { Info, AlertTriangle, AlertOctagon } from 'lucide-react';

interface CustomNoteProps extends React.HTMLAttributes<HTMLDivElement> {
  className?: string;
}

const CustomNote: React.FC<CustomNoteProps> = ({
  className = '',
  children,
  ...props
}) => {
  let Icon = Info;
  if (className.includes('warn')) Icon = AlertTriangle;
  if (className.includes('alert')) Icon = AlertOctagon;

  return (
    <div
      className={`custom-note ${className} flex items-start p-4 my-4 rounded-md`}
      {...props}
    >
      <div className="flex-shrink-0 self-center mr-3">
        <Icon className="w-5 h-5" />
      </div>
      <div className="flex-grow">{children}</div>
    </div>
  );
};

export default CustomNote;
