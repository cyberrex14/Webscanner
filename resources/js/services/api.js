const BASE_URL = "https://127.0.0.1:8000/api";

export async function startScan(target_url) {
  const res = await fetch(`${BASE_URL}/scan`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json"
    },
    body: JSON.stringify({ target_url })
  });

  return res.json();
}

export async function getScanResult(scanId) {
  const res = await fetch(`${BASE_URL}/scan/${scanId}`, {
    headers: {
      "Accept": "application/json"
    }
  });

  return res.json();
}
