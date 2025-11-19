import React from "react";
import ReactDOM from "react-dom/client";
import FileUploader from "@/components/ui/file-uploader";

const container = document.getElementById("rag-training-root");

if (container) {
  const root = ReactDOM.createRoot(container);
  const element = container as HTMLElement;
  const uploadUrl = element.dataset.uploadUrl || "";
  const filesUrl = element.dataset.filesUrl || "";
  const downloadUrl = element.dataset.downloadUrl || "";
  const zipDownloadUrl = element.dataset.zipDownloadUrl || "";
  const deleteUrl = element.dataset.deleteUrl || "";
  root.render(
    <FileUploader
      uploadUrl={uploadUrl}
      filesUrl={filesUrl}
      downloadUrl={downloadUrl}
      zipDownloadUrl={zipDownloadUrl}
      deleteUrl={deleteUrl}
    />
  );
}
