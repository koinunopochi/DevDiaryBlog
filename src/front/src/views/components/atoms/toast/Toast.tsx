import React, { useState, useEffect, useCallback } from 'react';
import { X, CheckCircle, AlertCircle } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';

export type ToastType = 'success' | 'error' | 'custom';

export interface ToastProps {
  message: React.ReactNode;
  type: ToastType;
  duration?: number;
  onClose?: () => void;
}

const Toast: React.FC<ToastProps> = ({ message, type, duration, onClose }) => {
  const [isVisible, setIsVisible] = useState(true);

  useEffect(() => {
    if (duration && duration > 0) {
      const timer = setTimeout(() => {
        setIsVisible(false);
        if (onClose) {
          onClose();
        }
      }, duration);
      return () => clearTimeout(timer);
    }
  }, [duration, onClose]);

  const handleClose = useCallback(() => {
    setIsVisible(false);
    if (onClose) {
      onClose();
    }
  }, [onClose]);

  if (!isVisible) return null;

  const getBackgroundColor = () => {
    switch (type) {
      case 'success':
        return 'bg-green-500 dark:bg-green-700';
      case 'error':
        return 'bg-red-500 dark:bg-red-700';
      default:
        return 'bg-blue-500 dark:bg-blue-700';
    }
  };

  const Icon =
    type === 'success' ? CheckCircle : type === 'error' ? AlertCircle : null;

  return (
    <AnimatePresence>
      {isVisible && (
        <motion.div
          className="fixed top-4 left-0 right-0 flex justify-center items-center z-50"
          initial={{ opacity: 0, y: -50 }}
          animate={{ opacity: 1, y: 0 }}
          exit={{ opacity: 0, y: -50 }}
          transition={{ type: 'spring', stiffness: 500, damping: 30 }}
        >
          <div
            className={`flex items-center justify-between p-2 sm:p-4 mx-2 sm:mx-0 text-white dark:text-gray-200 rounded-lg shadow-lg ${getBackgroundColor()} transition-colors duration-200 max-w-sm w-full sm:w-auto backdrop-blur-sm bg-opacity-90 dark:bg-opacity-90`}
          >
            {/* アイコンとメッセージを左寄せ */}
            <div className="flex items-center min-w-0">
              {Icon && (
                <div className="flex-shrink-0 mr-2">
                  <Icon className="w-5 h-5" />
                </div>
              )}
              <div className="flex-grow min-w-0 text-sm sm:text-base">
                {message}
              </div>
            </div>

            {/* 閉じるボタンを右寄せ */}
            <div className="flex-shrink-0 ml-2">
              <button
                onClick={handleClose}
                className="p-1 rounded-full hover:bg-white/10 transition-colors duration-200"
                aria-label="Close"
              >
                <X className="w-4 h-4 sm:w-5 sm:h-5" />
              </button>
            </div>
          </div>
        </motion.div>
      )}
    </AnimatePresence>
  );
};

export default Toast;
