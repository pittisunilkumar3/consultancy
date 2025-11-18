import React from "react";
import ReactDOM from "react-dom/client";
import FileUploader from "@/components/ui/file-uploader";

const container = document.getElementById("rag-training-root");

if (container) {
  const root = ReactDOM.createRoot(container);
  const uploadUrl = (container as HTMLElement).dataset.uploadUrl || "";
  root.render(<FileUploader uploadUrl={uploadUrl} />);
}
