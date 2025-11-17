import React from "react";
import ReactDOM from "react-dom/client";
import { Component as FileUploader } from "@/components/ui/file-uploader";

const container = document.getElementById("rag-training-root");

if (container) {
  const root = ReactDOM.createRoot(container);
  root.render(<FileUploader />);
}
