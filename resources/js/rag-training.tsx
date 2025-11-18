import React from "react";
import ReactDOM from "react-dom/client";
import FileUploader from "@/components/ui/file-uploader";

const container = document.getElementById("rag-training-root");

if (container) {
  const root = ReactDOM.createRoot(container);
  const element = container as HTMLElement;
  const uploadUrl = element.dataset.uploadUrl || "";
  const filesUrl = element.dataset.filesUrl || "";
  root.render(<FileUploader uploadUrl={uploadUrl} filesUrl={filesUrl} />);
}
