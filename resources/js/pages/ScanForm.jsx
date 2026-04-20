import { useState } from "react";
import ScanInput from "../components/ScanInput";
import { startScan } from "../services/api";

export default function ScanForm({ onScanCreated }) {
  const [loading, setLoading] = useState(false);

  const handleScan = async (url) => {
    setLoading(true);

    const data = await startScan(url);

    setLoading(false);

    if (data.scan_id) {
      onScanCreated(data.scan_id);
    }
  };

  return (
    <div>
      <h2>Start Scan</h2>
      <ScanInput onScan={handleScan} />
      {loading && <p>Starting scan...</p>}
    </div>
  );
}
