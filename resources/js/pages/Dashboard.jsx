import { useState } from "react";
import ScanForm from "./ScanForm";
import ResultPage from "./ResultPage";

export default function Dashboard() {
  const [scanId, setScanId] = useState(null);

  return (
    <div>
      <h1>NilufarSec Scanner</h1>

      <ScanForm onScanCreated={setScanId} />

      {scanId && <ResultPage scanId={scanId} />}
    </div>
  );
}
