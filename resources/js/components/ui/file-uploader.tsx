import React, { useEffect, useRef, useState } from "react";
import { X, Loader2, FileText, CheckCircle2, AlertCircle } from "lucide-react";

interface FilePreviewProps {
  file: File;
  onRemove: () => void;
}

const FilePreview: React.FC<FilePreviewProps> = ({ file, onRemove }) => {
  const [previewUrl, setPreviewUrl] = useState<string | null>(null);

  useEffect(() => {
    if (file && file.type.startsWith("image/")) {
      const url = URL.createObjectURL(file);
      setPreviewUrl(url);
      return () => {
        URL.revokeObjectURL(url);
      };
    }
    setPreviewUrl(null);
    return undefined;
  }, [file]);

  return (
    <div className="tw-flex tw-items-center tw-gap-3 tw-bg-gray-50 tw-rounded-lg tw-p-2 tw-shadow-sm tw-border tw-border-gray-200 tw-mb-2 animate-fade-in">
      {previewUrl ? (
        <img
          src={previewUrl}
          alt="Preview"
          className="tw-w-16 tw-h-16 tw-object-cover tw-rounded-md tw-border tw-border-gray-200"
        />
      ) : (
        <div className="tw-w-16 tw-h-16 tw-flex tw-items-center tw-justify-center tw-bg-gray-200 tw-rounded-md tw-text-gray-500">
          <FileText className="tw-w-6 tw-h-6" />
        </div>
      )}
      <div className="tw-flex-1 tw-truncate">
        <div className="tw-font-medium tw-text-gray-800 tw-truncate">{file.name}</div>
      </div>
      <button
        type="button"
        onClick={onRemove}
        className="tw-ml-2 tw-px-2 tw-py-1 tw-text-xs tw-flex tw-items-center tw-gap-1 tw-bg-red-50 tw-text-red-600 tw-rounded hover:tw-bg-red-100 tw-transition tw-border tw-border-red-200 tw-shadow-sm"
        aria-label="Remove file"
      >
        <X className="tw-w-4 tw-h-4" />
      </button>
    </div>
  );
};

interface ToastProps {
  message: string;
  type: "success" | "error";
  onClose: () => void;
}

const Toast: React.FC<ToastProps> = ({ message, type, onClose }) => (
  <div
    className={`tw-fixed tw-top-6 tw-right-6 tw-z-50 tw-px-4 tw-py-3 tw-rounded tw-shadow-lg tw-text-white animate-fade-in ${
      type === "success" ? "tw-bg-green-600" : "tw-bg-red-600"
    }`}
    role="alert"
  >
    <div className="tw-flex tw-items-center tw-gap-2">
      {type === "success" ? (
        <CheckCircle2 className="tw-w-4 tw-h-4" />
      ) : (
        <AlertCircle className="tw-w-4 tw-h-4" />
      )}
      <span>{message}</span>
      <button
        onClick={onClose}
        className="tw-ml-3 tw-text-white/80 hover:tw-text-white tw-text-lg"
        aria-label="Close"
      >
        <X className="tw-w-4 tw-h-4" />
      </button>
    </div>
  </div>
);

export const Component: React.FC = () => {
  const [selectedFiles, setSelectedFiles] = useState<File[]>([]);
  const [isDragging, setIsDragging] = useState(false);
  const [uploading, setUploading] = useState(false);
  const [progress, setProgress] = useState(0);
  const [toast, setToast] = useState<{
    message: string;
    type: "success" | "error";
  } | null>(null);
  const fileInputRef = useRef<HTMLInputElement>(null);

  const handleFiles = (files: FileList) => {
    const fileArr = Array.from(files);
    setSelectedFiles((prev) => [
      ...prev,
      ...fileArr.filter(
        (f) => !prev.some((p) => p.name === f.name && p.size === f.size)
      ),
    ]);
  };

  const handleFileChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    if (event.target.files && event.target.files.length > 0) {
      handleFiles(event.target.files);
    }
    event.target.value = "";
  };

  const handleButtonClick = () => {
    fileInputRef.current?.click();
  };

  const handleDrop = (event: React.DragEvent<HTMLDivElement>) => {
    event.preventDefault();
    setIsDragging(false);
    if (event.dataTransfer.files && event.dataTransfer.files.length > 0) {
      handleFiles(event.dataTransfer.files);
    }
  };

  const handleDragOver = (event: React.DragEvent<HTMLDivElement>) => {
    event.preventDefault();
    setIsDragging(true);
  };

  const handleDragLeave = () => {
    setIsDragging(false);
  };

  const handleRemoveFile = (index: number) => {
    setSelectedFiles((prev) => prev.filter((_, i) => i !== index));
    if (fileInputRef.current) {
      fileInputRef.current.value = "";
    }
  };

  const handleUpload = () => {
    if (selectedFiles.length === 0) return;
    setUploading(true);
    setProgress(0);

    const interval = window.setInterval(() => {
      setProgress((prev) => {
        if (prev >= 100) {
          window.clearInterval(interval);
          setUploading(false);
          setToast({ message: "Files uploaded successfully!", type: "success" });
          setSelectedFiles([]);
          return 100;
        }
        return prev + 10;
      });
    }, 120);
  };

  return (
    <div className="tw-flex tw-flex-col tw-items-center tw-justify-center tw-min-h-screen tw-bg-gradient-to-br tw-from-gray-50 tw-to-gray-200 tw-px-4 tw-w-full">
      <div className="tw-bg-white tw-rounded-2xl tw-shadow-xl tw-p-10 tw-w-full tw-max-w-md tw-border tw-border-gray-100 animate-fade-in">
        <h1 className="tw-text-3xl tw-font-bold tw-mb-8 tw-text-center tw-text-gray-900 tw-tracking-tight">
          Upload Files
        </h1>
        <input
          ref={fileInputRef}
          type="file"
          className="tw-hidden"
          onChange={handleFileChange}
          aria-label="File input"
          multiple
        />
        <div
          className={`tw-w-full tw-flex tw-flex-col tw-items-center tw-justify-center tw-border-2 tw-border-dashed tw-rounded-xl tw-transition-all tw-duration-200 tw-mb-5 tw-cursor-pointer ${
            isDragging
              ? "tw-border-blue-500 tw-bg-blue-50"
              : "tw-border-gray-300 tw-bg-gray-50 hover:tw-border-blue-400"
          }`}
          style={{ minHeight: 120 }}
          onClick={handleButtonClick}
          onDrop={handleDrop}
          onDragOver={handleDragOver}
          onDragLeave={handleDragLeave}
        >
          <div className="tw-flex tw-flex-col tw-items-center tw-py-6">
            <span className="tw-text-4xl tw-mb-2 tw-animate-bounce">📁</span>
            <span className="tw-text-gray-700 tw-font-medium">
              Drag & drop files here, or
              <span className="tw-text-blue-600 tw-underline"> browse</span>
            </span>
            <span className="tw-text-xs tw-text-gray-400 tw-mt-1">
              (PNG, JPG, PDF, etc. up to 5MB each)
            </span>
          </div>
        </div>
        {selectedFiles.length === 1 && (
          <div className="tw-mb-4">
            <FilePreview
              file={selectedFiles[0]}
              onRemove={() => handleRemoveFile(0)}
            />
          </div>
        )}
        {selectedFiles.length > 1 && (
          <div className="tw-mb-4" style={{ maxHeight: 180, overflowY: "auto" }}>
            <div className="tw-flex tw-flex-wrap tw-gap-2">
              {selectedFiles.map((file, idx) => (
                <span
                  key={file.name + file.size}
                  className="tw-inline-flex tw-items-center tw-max-w-xs tw-px-3 tw-py-1 tw-rounded-full tw-bg-gray-100 tw-text-gray-800 tw-text-sm tw-font-medium tw-shadow-sm tw-border tw-border-gray-200 tw-truncate"
                  style={{ minWidth: 0 }}
                  title={file.name}
                >
                  <span className="tw-truncate tw-max-w-[120px] tw-text-xs">
                    {file.name}
                  </span>
                  <button
                    type="button"
                    onClick={() => handleRemoveFile(idx)}
                    className="tw-ml-2 tw-p-0.5 tw-rounded-full hover:tw-bg-red-100 tw-text-red-600 tw-transition tw-border tw-border-transparent focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-red-200"
                    aria-label="Remove file"
                  >
                    <X className="tw-w-4 tw-h-4" />
                  </button>
                </span>
              ))}
            </div>
          </div>
        )}
        {uploading && (
          <div className="tw-w-full tw-bg-gray-200 tw-rounded-full tw-h-3 tw-mb-4 tw-overflow-hidden animate-fade-in">
            <div
              className="tw-bg-green-500 tw-h-3 tw-rounded-full tw-transition-all tw-duration-300"
              style={{ width: `${progress}%` }}
            />
          </div>
        )}
        <button
          type="button"
          disabled={selectedFiles.length === 0 || uploading}
          onClick={handleUpload}
          className={`tw-w-full tw-py-2 tw-px-4 tw-rounded-lg tw-font-semibold tw-text-base tw-transition focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-gray-400 focus:tw-ring-opacity-50 tw-shadow-lg tw-flex tw-items-center tw-justify-center tw-gap-2 ${
            selectedFiles.length > 0 && !uploading
              ? "tw-bg-gray-700 tw-text-white hover:tw-bg-gray-800 active:tw-scale-95"
              : "tw-bg-gray-300 tw-text-gray-500 tw-cursor-not-allowed"
          }`}
          style={{ minHeight: 40 }}
        >
          {uploading && <Loader2 className="tw-animate-spin tw-h-6 tw-w-6 tw-text-white" />}
          {uploading ? "Uploading..." : "Upload"}
        </button>
      </div>
      {toast && (
        <Toast
          message={toast.message}
          type={toast.type}
          onClose={() => setToast(null)}
        />
      )}
    </div>
  );
};

export default Component;
