import React, { useEffect, useRef, useState } from "react";
import axios from "axios";
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
    <div className="tw-flex tw-items-center tw-gap-3 tw-bg-gray-50 tw-rounded-lg tw-p-2 tw-shadow-sm tw-border tw-border-gray-200 tw-mb-2 tw-animate-fade-in">
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
    className={`tw-fixed tw-top-6 tw-right-6 tw-z-50 tw-px-4 tw-py-3 tw-rounded tw-shadow-lg tw-text-white tw-animate-fade-in ${
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

type UploadedFile = {
  original_name: string;
  path: string;
  url: string;
  size: number;
  mime_type: string | null;
};

const formatFileSize = (bytes: number | null | undefined): string => {
  if (bytes === undefined || bytes === null || Number.isNaN(bytes)) {
    return "-";
  }
  if (bytes === 0) return "0 B";

  const units = ["B", "KB", "MB", "GB"];
  const index = Math.min(
    Math.floor(Math.log(bytes) / Math.log(1024)),
    units.length - 1
  );
  const value = bytes / Math.pow(1024, index);
  return `${value.toFixed(2)} ${units[index]}`;
};

const getFileTypeLabel = (mimeType: string | null, name: string): string => {
  if (mimeType) {
    if (mimeType === "application/pdf") return "PDF";
    if (mimeType.startsWith("image/")) {
      return mimeType.replace("image/", "").toUpperCase();
    }
    return mimeType.toUpperCase();
  }

  const parts = name.split(".");
  if (parts.length > 1) {
    return parts[parts.length - 1].toUpperCase();
  }

  return "FILE";
};

interface FileUploaderProps {
  uploadUrl: string;
  filesUrl: string;
}

export const Component: React.FC<FileUploaderProps> = ({ uploadUrl, filesUrl }) => {
  const [selectedFiles, setSelectedFiles] = useState<File[]>([]);
  const [isDragging, setIsDragging] = useState(false);
  const [uploading, setUploading] = useState(false);
  const [progress, setProgress] = useState(0);
  const [toast, setToast] = useState<{
    message: string;
    type: "success" | "error";
  } | null>(null);
  const [uploadedFiles, setUploadedFiles] = useState<UploadedFile[]>([]);
  const [loadingFiles, setLoadingFiles] = useState(false);
  const [filesError, setFilesError] = useState<string | null>(null);
  const [search, setSearch] = useState("");
  const [selectedPaths, setSelectedPaths] = useState<string[]>([]);
  const fileInputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    if (!filesUrl) {
      return;
    }

    const fetchFiles = async () => {
      setLoadingFiles(true);
      setFilesError(null);

      try {
        const response = await axios.get(filesUrl);
        const files = (response.data?.files || []) as UploadedFile[];
        setUploadedFiles(files);
      } catch (error) {
        setFilesError("Failed to load existing files");
      } finally {
        setLoadingFiles(false);
      }
    };

    fetchFiles();
  }, [filesUrl]);

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

  const toggleSelectFile = (path: string) => {
    setSelectedPaths((prev) =>
      prev.includes(path) ? prev.filter((p) => p !== path) : [...prev, path]
    );
  };

  const handleSelectAllVisible = (checked: boolean, visibleFiles: UploadedFile[]) => {
    if (checked) {
      const next = Array.from(
        new Set([...selectedPaths, ...visibleFiles.map((file) => file.path)])
      );
      setSelectedPaths(next);
    } else {
      const visiblePaths = new Set(visibleFiles.map((file) => file.path));
      setSelectedPaths((prev) => prev.filter((p) => !visiblePaths.has(p)));
    }
  };

  const handleRemoveSelected = () => {
    if (!selectedPaths.length) return;

    setUploadedFiles((prev) => prev.filter((file) => !selectedPaths.includes(file.path)));
    setSelectedPaths([]);
  };

  const handleRemoveSingleFromList = (path: string) => {
    setUploadedFiles((prev) => prev.filter((file) => file.path !== path));
    setSelectedPaths((prev) => prev.filter((p) => p !== path));
  };

  const handleDownloadFile = (file: UploadedFile) => {
    window.open(file.url, "_blank");
  };

  const handleDownloadSelected = () => {
    if (!selectedPaths.length) return;

    const map = new Map(uploadedFiles.map((file) => [file.path, file]));
    selectedPaths.forEach((path) => {
      const file = map.get(path);
      if (file) {
        window.open(file.url, "_blank");
      }
    });
  };

  const handleUpload = async () => {
    if (!uploadUrl || selectedFiles.length === 0) {
      return;
    }

    const formData = new FormData();
    selectedFiles.forEach((file) => {
      formData.append("files[]", file);
    });

    setUploading(true);
    setProgress(0);

    try {
      const response = await axios.post(uploadUrl, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
        onUploadProgress: (event) => {
          if (event.total) {
            const percent = Math.round((event.loaded * 100) / event.total);
            setProgress(percent);
          }
        },
      });

      setProgress(100);
      const message =
        (response.data && (response.data.message as string)) ||
        "Files uploaded successfully!";
      const newFiles = (response.data?.files || []) as UploadedFile[];
      setUploadedFiles((prev) => [...newFiles, ...prev]);
      setToast({ message, type: "success" });
      setSelectedFiles([]);
      setSelectedPaths([]);
    } catch (error: any) {
      setProgress(0);
      let message = "Failed to upload files";

      if (error.response?.data?.message) {
        message = error.response.data.message;
      }

      setToast({ message, type: "error" });
    } finally {
      setUploading(false);
    }
  };

  const query = search.trim().toLowerCase();
  const filteredFiles = uploadedFiles.filter((file) => {
    if (!query) return true;

    const name = file.original_name.toLowerCase();
    const type = getFileTypeLabel(file.mime_type, file.original_name).toLowerCase();

    return name.includes(query) || type.includes(query);
  });

  const totalSize = uploadedFiles.reduce((sum, file) => sum + (file.size || 0), 0);
  const selectedCount = selectedPaths.length;
  const allVisibleSelected =
    filteredFiles.length > 0 &&
    filteredFiles.every((file) => selectedPaths.includes(file.path));

  return (
    <div className="tw-flex tw-flex-col tw-items-center tw-justify-center tw-min-h-screen tw-bg-gradient-to-br tw-from-gray-50 tw-to-gray-200 tw-px-4 tw-w-full">
      <div className="tw-bg-white tw-rounded-2xl tw-shadow-xl tw-p-10 tw-w-full tw-max-w-md tw-border tw-border-gray-100 tw-animate-fade-in">
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
          <div className="tw-w-full tw-bg-gray-200 tw-rounded-full tw-h-3 tw-mb-4 tw-overflow-hidden tw-animate-fade-in">
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
      <div className="tw-w-full tw-max-w-4xl tw-mt-8 tw-bg-white tw-rounded-2xl tw-shadow tw-border tw-border-gray-100 tw-p-6">
        <div className="tw-flex tw-flex-col tw-gap-4">
          <div className="tw-flex tw-items-center tw-justify-between">
            <div>
              <div className="tw-text-sm tw-font-semibold tw-text-gray-900">
                Files ({uploadedFiles.length})
              </div>
              <div className="tw-text-xs tw-text-gray-500">
                Total: {formatFileSize(totalSize)}
              </div>
            </div>
            <div className="tw-flex tw-items-center tw-gap-2">
              <input
                type="text"
                value={search}
                onChange={(event) => setSearch(event.target.value)}
                placeholder="Search by name or type"
                className="tw-text-xs tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-w-48 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-gray-400 focus:tw-ring-opacity-40"
              />
              <button
                type="button"
                onClick={handleButtonClick}
                className="tw-text-xs tw-px-3 tw-py-2 tw-rounded-lg tw-bg-gray-800 tw-text-white hover:tw-bg-gray-900 tw-transition tw-shadow-sm"
              >
                Add files
              </button>
              <button
                type="button"
                disabled={!uploadedFiles.length}
                onClick={() => {
                  setUploadedFiles([]);
                  setSelectedPaths([]);
                }}
                className={`tw-text-xs tw-px-3 tw-py-2 tw-rounded-lg tw-border tw-transition ${
                  uploadedFiles.length
                    ? "tw-border-gray-300 tw-text-gray-700 hover:tw-bg-gray-50"
                    : "tw-border-gray-200 tw-text-gray-400 tw-cursor-not-allowed"
                }`}
              >
                Remove all
              </button>
            </div>
          </div>

          <div className="tw-flex tw-items-center tw-justify-between tw-text-xs tw-text-gray-600">
            <div className="tw-flex tw-items-center tw-gap-2">
              <input
                type="checkbox"
                className="tw-rounded tw-border-gray-300 tw-text-gray-800 focus:tw-ring-gray-400"
                checked={allVisibleSelected && filteredFiles.length > 0}
                onChange={(event) =>
                  handleSelectAllVisible(event.target.checked, filteredFiles)
                }
              />
              <span>
                {selectedCount}/{uploadedFiles.length} selected
              </span>
            </div>
            <div className="tw-flex tw-items-center tw-gap-2">
              <button
                type="button"
                disabled={!selectedCount}
                onClick={handleDownloadSelected}
                className={`tw-text-xs tw-px-3 tw-py-1.5 tw-rounded-lg tw-border tw-transition ${
                  selectedCount
                    ? "tw-border-gray-300 tw-text-gray-700 hover:tw-bg-gray-50"
                    : "tw-border-gray-200 tw-text-gray-400 tw-cursor-not-allowed"
                }`}
              >
                Download selected
              </button>
              <button
                type="button"
                disabled={!selectedCount}
                onClick={handleRemoveSelected}
                className={`tw-text-xs tw-px-3 tw-py-1.5 tw-rounded-lg tw-border tw-transition ${
                  selectedCount
                    ? "tw-border-red-300 tw-text-red-600 hover:tw-bg-red-50"
                    : "tw-border-gray-200 tw-text-gray-400 tw-cursor-not-allowed"
                }`}
              >
                Remove selected
              </button>
            </div>
          </div>

          <div className="tw-border tw-border-gray-200 tw-rounded-lg tw-overflow-hidden tw-bg-white">
            {loadingFiles ? (
              <div className="tw-py-6 tw-text-center tw-text-xs tw-text-gray-500">
                Loading files...
              </div>
            ) : filesError ? (
              <div className="tw-py-6 tw-text-center tw-text-xs tw-text-red-600">
                {filesError}
              </div>
            ) : !filteredFiles.length ? (
              <div className="tw-py-6 tw-text-center tw-text-xs tw-text-gray-500">
                No files found.
              </div>
            ) : (
              <div className="tw-text-xs">
                <div className="tw-grid tw-grid-cols-[auto,1fr,auto,auto] tw-gap-3 tw-px-3 tw-py-2 tw-bg-gray-50 tw-text-gray-600 tw-font-semibold">
                  <div className="tw-flex tw-items-center tw-gap-2">
                    <span className="tw-w-4" />
                    <span>Name</span>
                  </div>
                  <div>Type</div>
                  <div className="tw-text-right">Size</div>
                  <div className="tw-text-right">Actions</div>
                </div>
                {filteredFiles.map((file) => (
                  <div
                    key={file.path}
                    className="tw-grid tw-grid-cols-[auto,1fr,auto,auto] tw-gap-3 tw-px-3 tw-py-2 tw-border-t tw-border-gray-100 hover:tw-bg-gray-50"
                  >
                    <div className="tw-flex tw-items-center tw-gap-2">
                      <input
                        type="checkbox"
                        className="tw-rounded tw-border-gray-300 tw-text-gray-800 focus:tw-ring-gray-400"
                        checked={selectedPaths.includes(file.path)}
                        onChange={() => toggleSelectFile(file.path)}
                      />
                      <button
                        type="button"
                        onClick={() => handleDownloadFile(file)}
                        className="tw-text-left tw-text-gray-900 tw-font-medium tw-truncate hover:tw-text-blue-600 hover:tw-underline"
                        title={file.original_name}
                      >
                        {file.original_name}
                      </button>
                    </div>
                    <div className="tw-flex tw-items-center tw-text-gray-600 tw-truncate">
                      {getFileTypeLabel(file.mime_type, file.original_name)}
                    </div>
                    <div className="tw-flex tw-items-center tw-justify-end tw-text-gray-700">
                      {formatFileSize(file.size)}
                    </div>
                    <div className="tw-flex tw-items-center tw-justify-end tw-gap-2">
                      <button
                        type="button"
                        onClick={() => handleDownloadFile(file)}
                        className="tw-text-xs tw-px-2 tw-py-1 tw-rounded-lg tw-border tw-border-gray-300 tw-text-gray-700 hover:tw-bg-gray-50 tw-transition"
                      >
                        Open
                      </button>
                      <button
                        type="button"
                        onClick={() => handleRemoveSingleFromList(file.path)}
                        className="tw-text-xs tw-px-2 tw-py-1 tw-rounded-lg tw-border tw-border-red-300 tw-text-red-600 hover:tw-bg-red-50 tw-transition"
                      >
                        Remove
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
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
