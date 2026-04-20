import { useEffect, useState } from "react";
import { getScanResult } from "../services/api";
import ResultTable from "../components/ResultTable";

export default function ResultPage({ scanId }) {
  const [scan, setScan] = useState(null);

  useEffect(() => {
    if (!scanId) return;

    const interval = setInterval(async () => {
      const data = await getScanResult(scanId);

      setScan(data);

      if (data.status === "done" || data.status === "failed") {
        clearInterval(interval);
      }
    }, 2000);

    return () => clearInterval(interval);
  }, [scanId]);

  if (!scan) return <p>Loading...</p>;

  return (
    <div>
      <h2>Scan Status: {scan.status}</h2>
      <ResultTable results={scan.results || []} />
    </div>
  );
}
