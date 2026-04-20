import { useState } from "react";

export default function ScanInput({ onScan }) {
  const [url, setUrl] = useState("");

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!url) return;
    onScan(url);
  };

  return (
    <form onSubmit={handleSubmit}>
      <input
        type="text"
        placeholder="https://target.com"
        value={url}
        onChange={(e) => setUrl(e.target.value)}
      />
      <button type="submit">Scan</button>
    </form>
  );
}
